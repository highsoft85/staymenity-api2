<?php

declare(strict_types=1);

namespace App\Services\Hostfully;

use App\Services\Database\RedisRateService;
use App\Services\Hostfully\Transformers\LeadTransformer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Response;

class BaseHostfullyService
{
    /**
     * @var bool
     */
    protected $enabled = false;

    /**
     * @var null|string
     */
    protected $token = null;

    /**
     * @var null|string
     */
    protected $key = null;

    /**
     * @var null|string
     */
    protected $url = null;

    /**
     * @var null|string
     */
    protected $errorMessage = null;

    /**
     * @var int
     */
    protected $rate = 0;

    /**
     * @var int
     */
    protected $version = 2;

    /**
     * @var RedisRateService
     */
    protected $rater;

    /**
     * BaseHostfullyService constructor.
     */
    public function __construct()
    {
        $this->enabled = config('hostfully.enabled');
        $this->token = config('hostfully.api.token');
        $this->url = config('hostfully.api.url-v2');
        $this->version = 2;
        $this->rater = (new RedisRateService('hostfully', 1000));
        loggerHostfully($this->rater->get(), 'RATE ACTUAL');
    }

    /**
     *
     */
    protected function setLeadsVersion()
    {
        $this->url = config('hostfully.api.url-v1');
        $this->key = config('hostfully.api.key');
        $this->version = 1;
        return $this;
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $type
     */
    private function prepareRequest(string $url, array $data, string $type)
    {
        $this->rater->increment();
        $this->url = $this->getUrl($url);
        loggerHostfully($this->url, $type);
        loggerHostfully($data, 'DATA');
    }

    /**
     * @param Response $response
     * @param bool $isArray
     * @return array
     */
    private function returnJson(Response $response, bool $isArray = true): array
    {
        if ($this->version === 1) {
            $oTransformer = (new LeadTransformer());
            $data = $response->json();
            if ($isArray) {
                return collect($data)->transform(function ($item) use ($oTransformer) {
                    return $oTransformer->transformFromV1ToV2($item);
                })->toArray();
            }
            return $oTransformer->transformFromV1ToV2($data);
        }
        return $response->json();
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function apiGet(string $url, array $data = []): array
    {
        $this->prepareRequest($url, $data, 'GET');
        $response = Http::withHeaders($this->headers())->get(getUrl($this->url, $data));
        if (!$response->successful()) {
            $this->setError($response);
        }
        return $this->returnJson($response);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function apiPost(string $url, array $data = []): array
    {
        $this->prepareRequest($url, $data, 'POST');
        $response = Http::withHeaders($this->headers())->post($this->url, $data);
        if (!$response->successful()) {
            $this->setError($response);
        }
        return $this->returnJson($response, false);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function apiPostRaw(string $url, array $data = []): array
    {
        $this->prepareRequest($url, $data, 'POST');
        $response = Http::withHeaders($this->headers([
            'Content-Type' => 'text/plain',
        ]))
            ->contentType('text/plain')
            //->bodyFormat('none')
            ->post($this->url, $data);
        if (!$response->successful()) {
            $this->setError($response);
        }
        return $this->returnJson($response, false);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function apiPut(string $url, array $data = []): array
    {
        $this->prepareRequest($url, $data, 'PUT');
        $response = Http::withHeaders($this->headers())->put($this->url, $data);
        if (!$response->successful()) {
            $this->setError($response);
        }
        return $this->returnJson($response, false);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function apiDelete(string $url, array $data = []): array
    {
        $this->prepareRequest($url, $data, 'DELETE');
        $response = Http::withHeaders($this->headers())->delete($this->url, $data);
        if (!$response->successful()) {
            $this->setError($response);
        }
        return $this->returnJson($response);
    }

    /**
     * @param string $url
     * @param array $data
     * @return null
     */
    protected function apiDeleteRaw(string $url, array $data = [])
    {
        $this->prepareRequest($url, $data, 'DELETE');
        $response = Http::withHeaders($this->headers())->delete($this->url, $data);
        if (!$response->successful()) {
            $this->setError($response);
        }
        return null;
    }

    /**
     * @param string $url
     * @return string
     */
    private function getUrl(string $url): string
    {
        if (!Str::startsWith($url, '/')) {
            $url = '/' . $url;
        }
        return $this->url . '' . $url;
    }

    /**
     * @return array
     */
    private function headers(array $headers = []): array
    {
        if ($this->version === 1) {
            return array_merge([
                'Content-Type' => 'application/json',
                'X-HOSTFULLY-APIKEY' => $this->key,
            ], $headers);
        }
        return array_merge([
            'Content-Type' => 'application/json',
            'X-HOSTFULLY-APIKEY' => $this->token,
        ], $headers);
    }

    /**
     * @return bool
     */
    protected function isSuccess(): bool
    {
        return is_null($this->errorMessage);
    }

    /**
     * @return string|null
     */
    protected function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     */
    private function setError(\Illuminate\Http\Client\Response $response)
    {
        $this->errorMessage = $response->json()['apiErrorMessage'] ?? 'Error';
        loggerHostfully($this->errorMessage, 'ERROR');
        $this->errorMessage .= ':url:' . $this->url;
    }
}
