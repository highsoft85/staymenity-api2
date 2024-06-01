<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Transformers\Api\UserTransformer;
use App\Services\Environment;
use App\Services\Image\ImageType;
use App\Services\Model\UserServiceModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class Update extends ApiController
{
    /**
     * @param UpdateRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(UpdateRequest $request)
    {
        $oUser = $this->authUser($request);

        $data = $request->all();

        if (isset($data['current_role']) && !is_null($data['current_role'])) {
            (new UserServiceModel($oUser))->setCurrentRole($data['current_role']);
            return responseCommon()->apiSuccess();
        }
        $oService = (new UserServiceModel($oUser));

        if (isset($data['phone'])) {
            if (!$oService->checkPhoneUnique($data['phone'])) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.unique', ['attribute' => 'phone']),
                ]);
            }
            if (!isset($data['phone_verified']) || (int)$data['phone_verified'] !== 1) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.must_be_verified', ['attribute' => 'phone']),
                ]);
            }
        }

        $oService->saveMainInfo($data);

        if (isset($data['description'])) {
            $oService->saveDetails($data);
        }
        if (isset($data['place_id'])) {
            $oService->saveLocation($data['place_id']);
        }

        // создание нового пароля
        if (isset($data['current_password'])) {
            $result = $oService->savePassword($data['current_password'], $data['new_password']);
            if (!$result['success']) {
                return responseCommon()->validationMessages(null, [
                    'current_password' => $result['message'],
                ]);
            }
        }

        // создание пароля
        if (isset($data['password'])) {
            $result = $oService->savePassword(null, $data['password']);
            if (!$result['success']) {
                return responseCommon()->validationMessages(null, [
                    'password' => $result['message'],
                ]);
            }
        }

        $files = $request->allFiles();
        if (isset($files['image']) && !empty($files['image'])) {
            $image = $files['image'];
            $validation = Validator::make($data, [
                'image' => ['max:5000', 'mimes:jpg,jpeg,gif,png'],
            ]);
            if ($validation->fails() && config('app.env') !== Environment::TESTING) {
                $messages = responseCommon()->validationGetMessages($validation);
                $message = array_shift($messages);
                return responseCommon()->validationMessages(null, [
                    'image' => $message,
                ]);
            }
            $type = ImageType::MODEL;
            $filters = $options = (new \App\Cmf\Project\User\UserController())->image[$type]['filters'];
            imageUpload($image, $oUser, $type, $filters, [
                'unique' => true,
            ]);
        }

        return responseCommon()->apiSuccess();
    }
}
