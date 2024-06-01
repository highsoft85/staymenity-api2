<?php

declare(strict_types=1);

namespace App\Services\Hostfully\ExternalCalendars;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    /**
     * @param string $uid
     * @return array
     */
    public function __invoke(string $uid): array
    {
        $data = $this->apiGet('/externalcalendars', [
            'propertyUid' => $uid
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
