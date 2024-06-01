<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Leads;

use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\MockLeadsHostfullyTrait;

class Destroy extends BaseHostfullyService
{
    use MockLeadsHostfullyTrait;

    /**
     * @param string $uid
     * @param array $data
     * @return array
     */
    public function __invoke(string $uid, array $data): array
    {
        if (envIsTesting()) {
            loggerHostfully('/leads/' . $uid, 'DELETE');
            loggerHostfully([], 'DATA');
            return $this->mockGetLead($uid);
        }
        $data = $this->setLeadsVersion()->apiDeleteRaw('/leads/' . $uid, $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return [];
    }
}
