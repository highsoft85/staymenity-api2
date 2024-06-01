<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Webhooks;

use App\Services\Hostfully\BaseHostfullyService;

class Show extends BaseHostfullyService
{
    /**
     * @param string $uid
     * @return array
     */
    public function __invoke(string $uid): array
    {
        $data = $this->apiGet('/webhooks/' . $uid, [
            //
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
