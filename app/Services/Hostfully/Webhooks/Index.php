<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Webhooks;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    /**
     * @param string|null $agencyUid
     * @param array|null $data
     * @return array
     */
    public function __invoke(?string $agencyUid = null, ?array $data = null): array
    {
        if (is_null($data) && !is_null($agencyUid)) {
            $data = [
                'agencyUid' => $agencyUid,
            ];
        }
        $data = $this->apiGet('/webhooks', $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
