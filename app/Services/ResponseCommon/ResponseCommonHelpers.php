<?php

declare(strict_types=1);

namespace App\Services\ResponseCommon;

use App\Services\Environment;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

/**
 * Class for response common helpers.
 */
class ResponseCommonHelpers
{
    /**
     * Ajax return success with default properties
     *
     * return $this->success([], 'Message')
     *
     * @param array $aData
     * @param string|null $message
     * @return array
     */
    public function success(array $aData = [], ?string $message = null)
    {
        $success = (new ResponseCommon())->success($aData);

        if (!is_null($message)) {
            $success = $success->withMessage($message);
        }
        return $success->build();
    }

    /**
     * Ajax return error with default properties
     *
     * return $this->error([
     *
     * ], 'Message')
     *
     * @param array $aData
     * @param string|null $message
     * @return array
     */
    public function error(array $aData = [], ?string $message = null): array
    {
        $success = (new ResponseCommon())->error($aData);

        if (isset($aData['type'])) {
            $success->setMethod($aData['type']);
        }

        if (!is_null($message)) {
            $success = $success->withMessage($message);
        }
        return $success->build();
    }

    /**
     * Ajax return json error with status 422
     *
     * @param array $aData
     * @param array|string|null $message Если массив, то ['text' => '', 'timeOut' => 3000 например]
     * @return JsonResponse
     */
    public function jsonError(array $aData = [], $message = null): JsonResponse
    {
        $success = (new ResponseCommon())->jsonErrorMessage($aData);

        if (isset($aData['type'])) {
            $success->setMethod($aData['type']);
        }

        if (!is_null($message)) {
            $success = $success->withMessage($message);
        }
        return $success->json();
    }

    /**
     * Log info with CPU value
     *
     * @param string $message
     */
    public function cpuLog(string $message = '')
    {
        $load = sys_getloadavg();
        info(json_encode($load) . ' - ' . $message);
    }

    /**
     * Log info with memory value
     *
     * @param string $message
     */
    public function memoryUsageLog(string $message = '')
    {
        $load = memory_get_usage();
        info(json_encode($load) . ' - ' . $message);
    }

    /**
     * Обертка для валидатора
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validation(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return Validator::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Вытащить все сообщения об ошибках
     *
     * @param null|\Illuminate\Contracts\Validation\Validator $validation
     * @param array $errors
     * @param array $data
     * @return JsonResponse
     */
    public function validationMessages($validation = null, $errors = [], $data = [])
    {
        $messages = $this->validationGetMessages($validation, $errors, $data);
        $returnData = [
            'success' => false,
            'errors' => $messages,
        ];
        if (!empty($data)) {
            $returnData = array_merge($returnData, $data);
        }
        return $this->jsonError($returnData);
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator|null $validation
     * @param array $errors
     * @param array $data
     * @return array
     */
    public function validationGetMessages($validation = null, $errors = [], $data = [])
    {
        $messages = [];
        if (!is_null($validation)) {
            $aMessages = $validation->getMessageBag()->toArray();

            foreach ($aMessages as $key => $aMessage) {
                $messages[$key] = $aMessage[0];
            }
        }
        $messages = array_merge($messages, $errors);
        return $messages;
    }

    /**
     * Для отправки ошибки, что запрос не ajax
     *
     * @return JsonResponse
     */
    public function mustBeAjax(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => 'The request must be AJAX',
        ]);
    }

    /**
     * @param array $aData
     * @param null|string $message
     * @return array
     */
    public function apiSuccess(array $aData = [], ?string $message = null)
    {
        $success = (new ResponseCommon())->success($aData);

        if (!is_null($message)) {
            $success = $success->apiMessage($message);
        }
        return $success->build();
    }

    /**
     * @param array $aData
     * @param string|null $message
     * @return array
     */
    public function apiDataSuccess(array $aData = [], ?string $message = null)
    {
        $data['data'] = $aData;
        $success = (new ResponseCommon())->success($data);

        if (!is_null($message)) {
            $success = $success->apiMessage($message);
        }
        return $success->build();
    }

    /**
     * @param object $object
     * @param string|null $message
     * @return array
     */
    public function apiDataObjectSuccess(object $object, ?string $message = null)
    {
        $data['data'] = $object;
        $success = (new ResponseCommon())->success($data);

        if (!is_null($message)) {
            $success = $success->apiMessage($message);
        }
        return $success->build();
    }

    /**
     * @param array $aData
     * @param string|null $message
     * @return array
     */
    public function apiDataError(array $aData = [], ?string $message = null)
    {
        $data['data'] = $aData;
        $success = (new ResponseCommon())->error($data);

        if (!is_null($message)) {
            $success = $success->apiMessage($message);
        }
        return $success->build();
    }

    /**
     * @param array $aData
     * @param LengthAwarePaginator|null $oResult
     * @param string|null $message
     * @return array
     */
    public function apiDataSuccessWithPagination(array $aData = [], ?LengthAwarePaginator $oResult = null, ?string $message = null)
    {
        if (!is_null($oResult)) {
            $aPagination = [
                'total' => $oResult->total(),
                'page' => request()->exists('page') ? (int)request()->get('page') : 1,
                'limit' => (int)$oResult->perPage(),
                'has_more_pages' => $oResult->hasMorePages(),
            ];
        } else {
            $aPagination = [
                'total' => 0,
                'page' => request()->exists('page') ? (int)request()->get('page') : 1,
                'limit' => 4,
                'has_more_pages' => false,
            ];
        }
        return $this->apiSuccess([
            'data' => $aData,
            'pagination' => $aPagination,
        ], $message);
    }

    /**
     * @param array $aData
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public function apiError(array $aData = [], ?string $message = null, $code = 401): JsonResponse
    {
        $success = (new ResponseCommon())->error($aData);

        if (!is_null($message)) {
            $success = $success->apiMessage($message);
        }
        return $success->json($code);
    }

    /**
     * @param array $aData
     * @return JsonResponse|array
     */
    public function apiNotFound(array $aData = [])
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return $this->error(array_merge($aData, [
                'message' => 'Not found',
                'status_code' => 404,
            ]));
        }
        return $this->apiError($aData, 'Not found', 404);
    }

    /**
     * @param array $aData
     * @return JsonResponse|array
     */
    public function apiUnauthorized(array $aData = [])
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return $this->error(array_merge($aData, [
                'message' => 'Unauthorized',
                'status_code' => 401,
            ]));
        }
        return $this->apiError($aData, 'Unauthorized', 401);
    }

    /**
     * @param array $aData
     * @return JsonResponse|array
     */
    public function apiAccessDenied(array $aData = [])
    {
        if (config('app.env') === Environment::DOCUMENTATION) {
            return $this->error(array_merge($aData, [
                'message' => 'Access denied',
                'status_code' => 403,
            ]));
        }
        return $this->apiError($aData, 'Access denied', 403);
    }

    /**
     * @param array $aData
     * @param string|null $message
     * @return JsonResponse
     */
    public function apiErrorBadRequest(array $aData = [], ?string $message = null): JsonResponse
    {
        //throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException(__('auth.banned'));
        return $this->apiError($aData, $message, 400);
    }
}
