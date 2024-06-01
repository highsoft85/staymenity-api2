<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Orders;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    /**
     * @param string $propertyUid
     * @param string $leadUid
     * @return array
     */
    public function __invoke(string $propertyUid, string $leadUid): array
    {
        $data = $this->apiGet('/orders', [
            'propertyUid' => $propertyUid,
            'leadUid' => $leadUid,
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
