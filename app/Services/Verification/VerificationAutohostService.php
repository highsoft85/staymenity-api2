<?php

declare(strict_types=1);

namespace App\Services\Verification;

use App\Models\UserIdentity;
use App\Services\Modelable\Imageable;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Validator;
use Illuminate\Support\Str;

class VerificationAutohostService
{
    use Imageable;

    // US / Canada / Mexico driver's license
    // Recommended steps are: front, back, selfie
    const TYPE_DRIVERS = 'drivers';

    // Passport
    // Recommended steps are: front, selfie
    const TYPE_PASSPORT = 'passport';

    // European ID
    // Recommended steps are: front, back, selfie
    const TYPE_EUROPEAN_ID = 'id';

    // Other ID
    // Recommended steps are: front, selfie
    const TYPE_ID = 'id';

    const STEP_FRONT = 'front';
    const STEP_BACK = 'back';
    const STEP_SELFIE = 'selfie';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string|null
     */
    private $message = null;

    /**
     * VerificationIdentityService constructor.
     */
    public function __construct()
    {
        $this->key = config('services.autohost.key');
        $this->url = config('services.autohost.url');
    }

    /**
     * @return array
     */
    private function headers()
    {
        return [
            'x-api-key' => $this->key,
        ];
    }

    /**
     * @param array $data
     * @param array $params
     * @return array|null
     */
    public function createReservation(array $data, array $params = [])
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities($data, 'AUTOHOST createReservation');
            loggerUserIdentities($params, 'Params');
            $this->url .= '/reservations';
            if (!empty($params)) {
                $this->url = $this->url . '?' . http_build_query($params);
            }
            loggerUserIdentities($this->url, 'REQUEST POST');
            $response = Http::withHeaders($this->headers())->post($this->url, $data);
            return $this->response($response);
        } else {
            // ответ, если autohost отключен
            return [
                'id' => UserIdentity::REV_ID_LOCAL_START . Str::random(16),
            ];
        }
    }

    /**
     * @param string $id
     * @param array $data
     * @return array|null
     */
    public function updateReservation(string $id, array $data)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities($data, 'AUTOHOST updateReservation');
            loggerUserIdentities($this->url . '/reservations/' . $id, 'REQUEST PUT');
            $response = Http::withHeaders($this->headers())->put($this->url . '/reservations/' . $id, $data);
            return $this->response($response);
        } else {
            // ответ, если autohost отключен
            return [
                'id' => UserIdentity::REV_ID_LOCAL_START . Str::random(16),
            ];
        }
    }

    /**
     * @param string $id
     * @return array|null
     */
    public function getReservation(string $id)
    {
        loggerUserIdentities(['id' => $id], 'AUTOHOST getReservation');
        loggerUserIdentities($this->url . '/reservations/' . $id, 'REQUEST GET');
        $response = Http::withHeaders($this->headers())->get($this->url . '/reservations/' . $id);
        return $this->response($response);
    }

    /**
     * @param string $id
     * @return array|null
     */
    public function checkReservationStatus(string $id)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities(['id' => $id], 'AUTOHOST checkReservationStatus');
            $url = '/reservations/' . $id . '/status';
            loggerUserIdentities($url, 'REQUEST GET');
            $response = Http::withHeaders($this->headers())->get($this->url . $url);
            return $this->response($response);
        } else {
            return [
                'status' => 'pending',
            ];
        }
    }

    /**
     * @param string $id
     * @return array|null
     */
    public function getReservationGuestPortal(string $id)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities(['id' => $id], 'AUTOHOST getReservationGuestPortal');
            $url = '/reservations/' . $id . '/guestportal';
            loggerUserIdentities($url, 'REQUEST GET');
            $response = Http::withHeaders($this->headers())->get($this->url . $url);
            return $this->response($response);
        } else {
            return [
                'status' => 'passed',
            ];
        }
    }

    /**
     * @param string $id
     * @param string $step
     * @param string $type
     * @param string $imageBase64
     * @return array|null
     */
    public function uploadImage(string $id, string $step, string $type, string $imageBase64)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities(['id' => $id, 'step' => $step, 'type' => $type], 'AUTOHOST uploadImage');
            $url = '/idcheck/upload/' . $id . '/' . $step . '/' . $type;
            loggerUserIdentities($this->url . $url, 'REQUEST POST');
            $response = Http::withHeaders($this->headers())->post($this->url . $url, [
                'imageBase64' => $imageBase64,
            ]);
            return $this->response($response);
        } else {
            $this->message = "Cannot analyze image";
            return [
                'code' => 'OK',
            ];
        }
    }

    /**
     * @param string $id
     * @return array|null
     */
    public function checkStatus(string $id)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities(['id' => $id], 'AUTOHOST checkStatus');
            $url = '/idcheck/status/' . $id;
            loggerUserIdentities($this->url . $url, 'REQUEST GET');
            $response = Http::withHeaders($this->headers())->get($this->url . $url);
            return $this->response($response);
        } else {
            return [
                'status' => 'passed',
            ];
        }
    }

    /**
     * @param string $id
     * @param string $step
     * @return array|null
     */
    public function checkStepStatus(string $id, string $step)
    {
        if (config('services.autohost.enabled')) {
            loggerUserIdentities(['id' => $id], 'AUTOHOST checkStepStatus');
            $url = '/idcheck/image/' . $id . '/' . $step;
            loggerUserIdentities($this->url . $url, 'REQUEST GET');
            $response = Http::withHeaders($this->headers())->get($this->url . $url);
            return $this->response($response);
        } else {
            return [
                'imageBase64' => 'string',
            ];
        }
    }

    /**
     * @param Response $response
     * @return array|null
     */
    private function response(Response $response)
    {
        $data = $response->json();
        $isSuccess = true;
        if ($response->status() !== 200) {
            loggerUserIdentities($response->body());
            $this->message = $response->json()['error'] ?? 'Error';
            $isSuccess = false;
        }
        if (isset($data['code']) && $data['code'] === 'FAILED' && isset($data['error'])) {
            $this->message = $data['error'];
            $isSuccess = false;
        }
        loggerUserIdentities($response->json(), 'RESPONSE');
        if (!$isSuccess) {
            return null;
        }
        return $response->json();
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $id
     * @return array
     */
    public function setReservationConfirmed(string $id)
    {
        if (config('services.autohost.enabled')) {
            $data = [
                'status' => 'CONFIRMED',
            ];
            loggerUserIdentities($data, 'AUTOHOST setReservationConfirmed');
            $url = '/reservations/' . $id;
            loggerUserIdentities($this->url . $url, 'REQUEST PUT');
            $response = Http::withHeaders($this->headers())->put($this->url . $url, $data);
            return $this->response($response);
        } else {
            return [
                'id' => 'string',
            ];
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function setReservationCancelled(string $id)
    {
        if (config('services.autohost.enabled')) {
            $data = [
                'status' => 'CANCELED',
            ];
            loggerUserIdentities($data, 'AUTOHOST setReservationConfirmed');
            $url = '/reservations/' . $id;
            loggerUserIdentities($this->url . $url, 'REQUEST PUT');
            $response = Http::withHeaders($this->headers())->put($this->url . $url, $data);
            return $this->response($response);
        } else {
            return [
                'id' => 'string',
            ];
        }
    }
}
