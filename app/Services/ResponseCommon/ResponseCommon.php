<?php

declare(strict_types=1);

namespace App\Services\ResponseCommon;

use App\Services\Toastr\Toastr;
use BadMethodCallException;
use Illuminate\Support\Str;

class ResponseCommon
{
    /**
     * @var array $aData
     */
    private $aData = [];

    /**
     * @var string $method
     */
    private $method = null;

    /**
     * Ajax return success with default properties
     *
     * @param array $aData
     * @return $this
     */
    public function success(array $aData = [])
    {
        $this->method = __FUNCTION__;

        $this->aData = array_merge([
            'success' => true,
        ], $aData);

        return $this;
    }

    /**
     * Ajax return error with default properties
     *
     * @param array $aData
     * @return $this
     */
    public function error(array $aData = [])
    {
        $this->method = __FUNCTION__;

        $this->aData = array_merge([
            'success' => false,
        ], $aData);

        return $this;
    }

    /**
     * Ajax return json error with status 422
     *
     * @param string $text
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonError($text, $key = 'error')
    {
        return response()->json([
            $key => [$text],
        ], 422);
    }

    /**
     * Ajax return error with default properties
     *
     * @param array $aData
     * @return $this
     */
    public function jsonErrorMessage(array $aData = [])
    {
        $this->method = 'error';

        $this->aData = array_merge([
            'success' => false,
        ], $aData);

        return $this;
    }

    /**
     * @param array|string $message
     * @return $this
     */
    public function withMessage($message)
    {
        $this->aData['toastr'] = (new Toastr($message))->{$this->method}();

        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        return $this->aData;
    }

    /**
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(int $code = 422)
    {
        $this->aData['status_code'] = $code;
        return response()->json($this->aData, $code);
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = in_array($method, ['success', 'error', 'warning', 'info']) ? $method : $this->method;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function apiMessage(string $message)
    {
        $this->aData['message'] = $message;

        return $this;
    }
}
