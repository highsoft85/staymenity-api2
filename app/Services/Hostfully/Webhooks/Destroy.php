<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Webhooks;

use App\Services\Hostfully\BaseHostfullyService;

class Destroy extends BaseHostfullyService
{
    /**
     * @param string $uid
     * @return array
     */
    public function __invoke(string $uid): array
    {
        $data = $this->apiDeleteRaw('/webhooks/' . $uid, [
            //
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return [];
    }
}
