<?php

declare(strict_types=1);

namespace App\Services\Transaction;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction
{
    /**
     * @var array
     */
    private $result = [];

    /**
     * transaction()->commit(function () {
     *
     * });
     *
     * @param mixed $function
     * @return array
     */
    public function commit($function): array
    {
        if (DB::transactionLevel() !== 0 || in_array(config('app.env'), ['testing'])) {
            return $this->withoutTransaction($function);
        }
        DB::beginTransaction();

        try {
            $data = $function();
            DB::commit();
            return $this->success($data);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e);
        }
    }

    /**
     * @param mixed $function
     * @return $this
     */
    public function commitAction($function)
    {
        DB::beginTransaction();

        try {
            $data = $function();
            DB::commit();
            $this->result = $this->success($data);
            return $this;
        } catch (\Exception $e) {
            DB::rollback();
            $this->result = $this->error($e);
            return $this;
        }
    }

    /**
     * Необходимо когда транзакция уже запущена
     * Решает проблему при тестировании
     * - General error: 1205 Lock wait timeout exceeded; try restarting transaction
     *
     * @param mixed $function
     * @return array
     */
    private function withoutTransaction($function): array
    {
        try {
            $data = $function();
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * @param mixed $data
     * @return array
     */
    private function success($data): array
    {
        return [
            'success' => true,
            'data' => $data,
        ];
    }

    /**
     * @param \Exception $e
     * @return array
     */
    private function error(\Exception $e): array
    {
        Log::error('Transaction error ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
        return array_merge([
            'success' => false,
        ], [
            'data' => [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ],
        ]);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->result['success'];
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->result['data']['message'];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->result['data'];
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->result['data'];
    }
}
