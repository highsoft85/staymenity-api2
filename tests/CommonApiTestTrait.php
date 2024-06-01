<?php

namespace Tests;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Testing\TestResponse;
use finfo;
use Laravel\Sanctum\Sanctum;

trait CommonApiTestTrait
{
    /**
     * @param string $url
     * @param array $data
     * @param User|null $oUser
     * @param bool $toArray
     * @param string|null $token
     * @return mixed
     */
    protected function apiGet(
        string $url,
        array $data = [],
        ?User $oUser = null,
        bool $toArray = true,
        ?string $token = null
    ) {
        if (!is_null($oUser)) {
            Sanctum::actingAs($oUser, ['*']);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);

        $headers = [];
        if (!is_null($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $url = $this->getUrl(config('api.url') . '/api' . $url, $data);
        $response = $this->get($url, $headers);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @param User|null $oUser
     * @param bool $toArray
     * @param string|null $token
     * @return mixed
     */
    protected function apiPost(
        string $url,
        array $data = [],
        ?User $oUser = null,
        bool $toArray = true,
        ?string $token = null
    ) {
        if (!is_null($oUser) && is_null($token)) {
            Sanctum::actingAs($oUser, ['*']);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);

        $headers = [];
        if (!is_null($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $url = config('api.url') . '/api' . $url;
        $response = $this->post($url, $data, $headers);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @param User|null $oUser
     * @param bool $toArray
     * @return mixed
     */
    protected function apiDelete(string $url, array $data = [], ?User $oUser = null, bool $toArray = true)
    {
        if (!is_null($oUser)) {
            Sanctum::actingAs($oUser, ['*']);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $url = config('api.url') . '/api' . $url;
        $response = $this->delete($url, $data);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }


    /**
     * @param string $sFile
     * @return UploadedFile
     */
    protected function uploadedFile(string $sFile): UploadedFile
    {
        return UploadedFile::fake()->image($sFile);
    }


    /**
     * @param User $oUser
     * @param array $data
     * @return Request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function requestApiUser(User $oUser, array $data = []): Request
    {
        $response = (new LoginController())->login($this->requestCustom(new LoginRequest(), [
            'email' => $oUser->email,
            'password' => 'secret',
        ]));
        $token = $response['data']['token'];
        $request = new Request();
        $request->merge($data);
        $request->merge([
            'phpunit' => true,
        ]);
        $request->headers->set('Authorization', 'Bearer ' . $token);
        return $request;
    }

    /**
     * @param string $url
     * @param array $data
     * @return mixed
     */
    protected function postAjax(string $url, array $data = [])
    {
        return $this->post($url, $data, ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    }

    /**
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @return mixed|array
     */
    protected function postApi(string $url, array $data = [], bool $toArray = false)
    {
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->post(config('api.url') . $url, $data);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @return mixed|array
     */
    protected function postWeb(string $url, array $data = [], bool $toArray = false)
    {
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->post(config('app.url') . $url, $data);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param User $oUser
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @param string|null $token
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function postApiUser(User $oUser, string $url, array $data = [], bool $toArray = false, string $token = null)
    {
        if (is_null($token)) {
            $token = $this->tokenUserGet($oUser);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->post(config('api.url') . $url, $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param User $oUser
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @param string|null $token
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function putApiUser(User $oUser, string $url, array $data = [], bool $toArray = false, string $token = null)
    {
        if (is_null($token)) {
            $token = $this->tokenUserGet($oUser);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->put(config('api.url') . $url, $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param User $oUser
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @param string|null $token
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     */
    protected function getApiUser(User $oUser, string $url, array $data = [], bool $toArray = false, string $token = null)
    {
        if (is_null($token) && !is_null($oUser)) {
            $token = $this->tokenUserGet($oUser);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->get(config('api.url') . $url, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @param string|null $token
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getApiWithToken(string $url, array $data = [], bool $toArray = false, string $token = null)
    {
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->get(config('api.url') . $url, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param User $oUser
     * @param string $url
     * @param array $data
     * @param bool $toArray
     * @param string|null $token
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function deleteApiUser(User $oUser, string $url, array $data = [], bool $toArray = false, string $token = null)
    {
        if (is_null($token)) {
            $token = $this->tokenUserGet($oUser);
        }
        $data = array_merge([
            'phpunit' => true,
        ], $data);
        $response = $this->delete(config('api.url') . $url, $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param User $oUser
     * @return string|null
     */
    protected function tokenUserGet(User $oUser): ?string
    {
        try {
            $response = (new LoginController())->login($this->requestCustom(new LoginRequest(), [
                'email' => $oUser->email,
                'password' => 'secret',
            ]));
            return $response['data']['token'];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $url
     * @param bool $toArray
     * @return array|JsonResponse|TestResponse|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function getApi(string $url, bool $toArray = false)
    {
        $response = $this->get(config('api.url') . $url);
        if ($toArray) {
            /** @var \Illuminate\Testing\TestResponse $response */
            return json_decode($response->getContent(), true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    protected function getUrl(string $url, array $data = []): string
    {
        if (!empty($data)) {
            $parameters = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k1 => $v1) {
                                $parameters[] = $key . '[' . $k . ']' . '[' . $k1 . ']' . '=' . $v1;
                            }
                        } else {
                            $parameters[] = $key . '[' . $k . ']' . '=' . $v;
                        }
                    }
                } else {
                    $parameters[] = $key . '=' . $value;
                }
            }
            $url .= '?' . implode('&', $parameters);
        }
        return $url;
    }
}
