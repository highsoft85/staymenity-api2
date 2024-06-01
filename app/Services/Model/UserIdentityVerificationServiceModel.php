<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Environment;
use App\Services\Image\ImageType;
use App\Services\Image\Upload\ImageUploadIdentityBackService;
use App\Services\Image\Upload\ImageUploadIdentityFrontService;
use App\Services\Image\Upload\ImageUploadIdentitySelfieService;
use App\Services\Image\Upload\ImageUploadService;
use App\Services\Verification\VerificationAutohostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UserIdentityVerificationServiceModel
{
    /**
     * Удалить ли существующие верификации когда создается новая
     */
    const STORE_DELETE_EXISTING = true;

    /**
     * Загружать ли фотки сразу в автохост
     */
    const STORE_FAST_UPLOAD_IMAGES = false;

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var UserIdentity|null
     */
    private $oIdentity;

    /**
     * @var bool
     */
    private $exampleError = false;

    /**
     * UserServiceModel constructor.
     * @param User $oUser
     * @param UserIdentity|null $oIdentity
     */
    public function __construct(User $oUser, ?UserIdentity $oIdentity = null)
    {
        $this->oUser = $oUser;
        $this->oIdentity = $oIdentity;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setExampleError(bool $value)
    {
        $this->exampleError = $value;
        return $this;
    }

    /**
     * @return array
     */
    private function getUserData()
    {
        return [
            'first_name' => $this->oUser->first_name,
            'last_name' => $this->oUser->last_name,
            'email' => $this->oUser->email,
            'phone' => '+' . $this->oUser->phone,
        ];
    }

    /**
     * @param string $type
     * @return bool
     */
    private function canCreateNewVerification(string $type)
    {
        $oIdentity = $this->getUserVerificationByType($type);
        if (is_null($oIdentity)) {
            return true;
        }
        if ($oIdentity->created_at <= now()->subMinutes(15)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $type
     * @return bool
     */
    private function canCreateNewVerificationByStatus(string $type)
    {
        $oIdentity = $this->getUserVerificationByType($type);
        if (is_null($oIdentity)) {
            return true;
        }
        if ($oIdentity->status !== UserIdentity::STATUS_FAILED) {
            return true;
        }
        return false;
    }

    /**
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Model|UserIdentity|null
     */
    private function getUserVerificationByType(string $type)
    {
        return $this->oUser
            ->identities()
            ->where('type', $type)
            ->first();
    }

    /**
     * @param string $type
     * @return UserIdentity
     * @throws \Exception
     */
    public function create(string $type)
    {
        if (!$this->canCreateNewVerificationByStatus($type)) {
            throw new \Exception('Your verification was declined. Please contact us for information');
        }
        if (!$this->canCreateNewVerification($type)) {
            $oIdentity = $this->getUserVerificationByType($type);
            $time = $oIdentity->created_at->copy()->addMinutes(15)->format('m/d/Y H:i:s') . ' PST';
            throw new \Exception('New verification request can be submitted no earlier than ' . $time);
        }
        if (self::STORE_DELETE_EXISTING) {
            $oIdentities = $this->oUser
                ->identities()
                ->where('type', $type)
                ->get();
            if (count($oIdentities) !== 0) {
                foreach ($oIdentities as $oIdentity) {
                    $this->oIdentity = $oIdentity;
                    $this->delete();
                }
                $this->oIdentity = null;
            }
            $this->oUser->update([
                'identity_verified_at' => null,
            ]);
        }
        /** @var UserIdentity $oUserIdentity */
        $oUserIdentity = $this->oUser->identities()->where('type', $type)->first();
        if (!is_null($oUserIdentity)) {
            return $oUserIdentity;
        } else {
            $oService = (new VerificationAutohostService());
            $data = $oService->createReservation([
                'listing' => null,
                'guest' => $this->getUserData(),
            ], [
                'callback' => $this->getCallback(),
                //'skip_gp' => 1,
                'sync' => 1,
                //'async' => 1,
            ]);
            if (is_null($data)) {
                throw new \Exception($oService->getMessage());
            }
            /** @var UserIdentity $oUserIdentity */
            $oUserIdentity = $this->oUser->identities()->create($this->getCreateDataByResponseCreateReservation($data, $type));
            return $oUserIdentity;
        }
    }

    /**
     * @return string
     */
    private function getCallback()
    {
        $host = config('api.url');
        if (!envIsProduction()) {
            $host = 'https://api.staymenity.com';
        }
        return $host . '/api/autohost/callback?user_id=' . $this->oUser->id;
    }

    /**
     * @param array $data
     * @param string $type
     * @return array
     */
    private function getCreateDataByResponseCreateReservation(array $data, string $type)
    {
        if (isset($data['queued'])) {
            return [
                'type' => $type,
                'autohost_reservation_id' => $data['queued'],
                'status' => UserIdentity::STATUS_QUEUED,
            ];
        }
        return [
            'type' => $type,
            'autohost_reservation_id' => $data['id'],
        ];
    }

    /**
     * @return UserIdentity
     * @throws \Exception
     */
    public function update()
    {
        if ($this->oIdentity->isLocalKey()) {
            $oService = (new VerificationAutohostService());
            $data = $oService->createReservation([
                'guest' => $this->getUserData(),
            ]);
            if (is_null($data)) {
                throw new \Exception($oService->getMessage());
            }
            $this->oIdentity->update([
                'autohost_reservation_id' => $data['id'],
            ]);
        } else {
            $oService = (new VerificationAutohostService());
            $data = $oService->updateReservation($this->oIdentity->autohost_reservation_id, [
                'guest' => $this->getUserData(),
            ]);
            if (is_null($data)) {
                throw new \Exception($oService->getMessage());
            }
        }
        return $this->oIdentity;
    }

    /**
     * @param string $step
     * @param string $imageBase64
     * @return array
     * @throws \Exception
     */
    public function upload(string $step, string $imageBase64)
    {
        $id = $this->oIdentity->autohost_reservation_id;
        $type = $this->oIdentity->type;
        $oService = (new VerificationAutohostService());
        $data = $oService->uploadImage($id, $step, $type, $imageBase64);
        if (is_null($data)) {
            throw new \Exception($oService->getMessage());
        }
        if ($this->exampleError) {
            throw new \Exception('Cannot analyze image');
            //throw new \Exception('Example error');
        }
        return $data;
    }

    /**
     * @return array
     */
    public function checkStatus()
    {
        $id = $this->oIdentity->autohost_reservation_id;
        $oService = (new VerificationAutohostService());
        $data = $oService->checkStatus($id);
        // null будет только если {"error":"Missing front and back photos"}
        if (is_null($data)) {
            return [
                'status' => 'pending',
                'error' => $oService->getMessage(),
            ];
        }
        return $data;
    }

    /**
     * @return array
     */
    public function checkReservationStatus()
    {
        $id = $this->oIdentity->autohost_reservation_id;
        $oService = (new VerificationAutohostService());
        $data = $oService->checkReservationStatus($id);
        if (is_null($data)) {
            return [
                'status' => 'pending',
                //'error' => $oService->getMessage(),
            ];
        }
        return $data;
    }

    /**
     *
     */
    public function commonCheckStatusAndSaveResults()
    {
        // {"pms_status":"CONFIRMED","guestportal_completed":false}
        $this->checkReservationStatusAndSaveResults();

        // {"error":"Missing front and back photos"}
        //$this->checkStatusAndSaveResults();

        // проверка только по успешно загруженым фоткам
        //$this->checkStatusImagesAndSaveResults();
    }

    /**
     *
     */
    private function checkReservationStatusAndSaveResults()
    {
        $data = $this->checkReservationStatus();
        if (isset($data['supervised']) && $data['supervised'] === 'decline') {
            $this->statusFailed(['Verification set to be Declined by the manager']);
            return;
        }
        if (isset($data['supervised']) && $data['supervised'] === 'approve') {
            $this->statusSuccess();
            return;
        }
        if (isset($data['status']) && $data['status'] === 'passed') {
            $this->statusSuccess();
        } elseif (isset($data['status']) && $data['status'] === 'review') {
            // тут результаты по supervised
        } elseif (isset($data['status']) && $data['status'] === 'verified') {
            $this->statusSuccess();
        } elseif (isset($data['status']) && $data['status'] === 'pending') {
            $this->statusPending($data);
        } elseif (isset($data['errors'])) {
            $aErrors = is_array($data['errors'])
                ? $data['errors']
                : [$data['errors']];
            $this->statusFailed($aErrors);
        }
    }

    /**
     *
     */
    public function statusSuccess()
    {
        $this->oUser->update([
            'identity_verified_at' => now(),
        ]);
        $this->oIdentity->update([
            'errors' => null,
            'status' => UserIdentity::STATUS_SUCCESS,
        ]);
    }

    /**
     * @param array $data
     */
    public function statusPending(array $data = [])
    {
        $errors = null;
        $this->oUser->update([
            'identity_verified_at' => null,
        ]);
        if (isset($data['error'])) {
            $errors['status'] = $data['error'];
        }
        $this->oIdentity->update([
            'errors' => $errors,
            'status' => UserIdentity::STATUS_PENDING,
        ]);
    }

    /**
     * @param array $messages
     */
    public function statusFailed(array $messages)
    {
        $this->oUser->update([
            'identity_verified_at' => null,
        ]);
        if (array_key_exists(0, $messages)) {
            $messages = [
                'status' => implode(', ', $messages),
            ];
        }
        $errors = json_encode($messages);

        $this->oIdentity->update([
            'status' => UserIdentity::STATUS_FAILED,
            'errors' => $errors,
        ]);
    }

    /**
     *
     */
    private function checkStatusAndSaveResults()
    {
        $data = $this->checkStatus();
        if (isset($data['status']) && $data['status'] === 'passed') {
            $this->statusSuccess();
        } elseif (isset($data['status']) && $data['status'] === 'pending') {
            $this->statusPending($data);
        } elseif (isset($data['errors'])) {
            $aErrors = is_array($data['errors'])
                ? $data['errors']
                : [$data['errors']];
            $this->statusFailed($aErrors);
        }
    }

    /**
     * Сохранение статуса по успешно загруженным фотографиям
     */
    private function checkStatusImagesAndSaveResults()
    {
        $isSuccessFront = $this->oIdentity->image_front_status === 1;
        $isSuccessBack = $this->oIdentity->image_back_status === 1;
        $isSuccessSelfie = $this->oIdentity->image_selfie_status === 1;

        $isSuccess = $isSuccessFront && $isSuccessBack && $isSuccessSelfie;

        $isOkFront = $this->checkCodeIsOk($this->oIdentity->imageFrontResponseArray);
        $isOkBack = $this->checkCodeIsOk($this->oIdentity->imageBackResponseArray);
        $isOkSelfie = $this->checkCodeIsOk($this->oIdentity->imageSelfieResponseArray);

        $isOk = $isOkFront && $isOkBack && $isOkSelfie;

        if ($isSuccess && $isOk) {
            $this->statusSuccess();
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private function checkCodeIsOk(array $data)
    {
        return isset($data['code']) && $data['code'] === 'OK';
    }

    /**
     * @param string $key
     * @param string $message
     */
    public function saveErrorMessage(string $key, string $message)
    {
        $errors = array_merge($this->oIdentity->errorsArray, [
            $key => $message,
        ]);
        $this->statusFailed($errors);
    }

    /**
     * @return \App\Services\Transaction\Transaction
     */
    public function delete()
    {
        return transaction()->commitAction(function () {
            $oUserIdentity = $this->oIdentity;
            // удаление изображений после успешных удалений
            $type = ImageType::IDENTITY_TYPE_FRONT;
            $oImages = $oUserIdentity->imagesIdentityFront;
            $options = (new \App\Cmf\Project\UserIdentity\UserIdentityController())->image[$type];
            foreach ($oImages as $oImage) {
                (new ImageUploadIdentityFrontService())->delete($oUserIdentity, $oImage, $options);
            }
            // удаление изображений после успешных удалений
            $type = ImageType::IDENTITY_TYPE_BACK;
            $oImages = $oUserIdentity->imagesIdentityBack;
            $options = (new \App\Cmf\Project\UserIdentity\UserIdentityController())->image[$type];
            foreach ($oImages as $oImage) {
                (new ImageUploadIdentityBackService())->delete($oUserIdentity, $oImage, $options);
            }
            // удаление изображений после успешных удалений
            $type = ImageType::IDENTITY_TYPE_SELFIE;
            $oImages = $oUserIdentity->imagesIdentitySelfie;
            $options = (new \App\Cmf\Project\UserIdentity\UserIdentityController())->image[$type];
            foreach ($oImages as $oImage) {
                (new ImageUploadIdentitySelfieService())->delete($oUserIdentity, $oImage, $options);
            }
            $oUserIdentity->delete();
            return null;
        });
    }


    /**
     * @param string $key
     * @return string|null
     */
    public function getUploadTypeByKey(string $key)
    {
        switch ($key) {
            case 'image_front':
                return ImageType::IDENTITY_TYPE_FRONT;
            case 'image_back':
                return ImageType::IDENTITY_TYPE_BACK;
            case 'image_selfie':
                return ImageType::IDENTITY_TYPE_SELFIE;
        }
        return null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getStepByKey(string $key)
    {
        switch ($key) {
            case 'image_front':
                return UserIdentity::STEP_FRONT;
            case 'image_back':
                return UserIdentity::STEP_BACK;
            case 'image_selfie':
                return UserIdentity::STEP_SELFIE;
        }
        return null;
    }

    /**
     * @param UserIdentity $oIdentity
     * @param string $key
     * @return string|null
     */
    public function getImageBase64ByKey(UserIdentity $oIdentity, string $key)
    {
        switch ($key) {
            case 'image_front':
                return $oIdentity->frontImageBase64;
            case 'image_back':
                return $oIdentity->backImageBase64;
            case 'image_selfie':
                return $oIdentity->selfieImageBase64;
        }
        return null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getTitleImageByKey(string $key)
    {
        switch ($key) {
            case 'image_front':
                return 'Front image: ';
            case 'image_back':
                return 'Back image: ';
            case 'image_selfie':
                return 'Selfie image: ';
        }
        return null;
    }

    /**
     * @param UserIdentity $oIdentity
     * @param string $key
     * @param array $result
     * @param int $status
     * @return null
     */
    public function saveResponseByKey(UserIdentity $oIdentity, string $key, array $result, int $status)
    {
        $responseKey = null;
        $statusKey = null;
        switch ($key) {
            case 'image_front':
                $responseKey = 'image_front_response';
                $statusKey = 'image_front_status';
                break;
            case 'image_back':
                $responseKey = 'image_back_response';
                $statusKey = 'image_back_status';
                break;
            case 'image_selfie':
                $responseKey = 'image_selfie_response';
                $statusKey = 'image_selfie_status';
                break;
        }
        $oIdentity->update([
            $responseKey => $result,
            $statusKey => $status,
        ]);
        return null;
    }


    /**
     * @param UserIdentity $oIdentity
     * @param User $oUser
     * @param string $key
     * @param UploadedFile $file
     * @return array|JsonResponse
     */
    public function uploadImage(UserIdentity $oIdentity, User $oUser, string $key, UploadedFile $file)
    {
        $uploadType = $this->getUploadTypeByKey($key);
        imageUploadUserIdentity($file, $oIdentity, $uploadType);
        return $this->uploadImageByExisting($oIdentity, $key);
    }

    /**
     * @param UserIdentity $oIdentity
     * @param string $key
     * @return array|JsonResponse
     */
    public function uploadImageByExisting(UserIdentity $oIdentity, string $key)
    {
        if (!self::STORE_FAST_UPLOAD_IMAGES) {
            slackInfo($key, 'VERIFICATION_SERVICE skipUpload');
            return responseCommon()->success();
        }
        $image = $this->getImageBase64ByKey($oIdentity, $key);
        $step = $this->getStepByKey($key);

        if (is_null($image)) {
            return responseCommon()->validationMessages(null, [
                $key => $this->getTitleImageByKey($key) . 'Image is empty',
            ]);
        }
        $result = transaction()->commitAction(function () use ($image, $step) {
            return $this->upload($step, $image);
        });
        if (!$result->isSuccess()) {
            slackInfo($result->getErrorMessage(), 'VERIFICATION_SERVICE uploadImage');
            $this->saveErrorMessage($step, $result->getErrorMessage());
            $this->saveResponseByKey($oIdentity, $key, $result->getData(), 0);
            return responseCommon()->validationMessages(null, [
                $key => $this->getTitleImageByKey($key) . $result->getErrorMessage(),
            ]);
        }
        $this->saveResponseByKey($oIdentity, $key, $result->getData(), 1);
        slackInfo($key, 'VERIFICATION_SERVICE SUCCESS');
        return responseCommon()->success();
    }
}
