<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Leads;

use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\MockLeadsHostfullyTrait;
use App\Services\Hostfully\Transformers\LeadTransformer;

class Update extends BaseHostfullyService
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
            loggerHostfully('/leads/' . $uid, 'PUT');
            loggerHostfully($data, 'DATA');
            return $this->mockUpdateLead($uid, (new LeadTransformer())->transformFromV2ToV1Update($data));
        }
        slackInfo($data);
        $data = $this->setLeadsVersion()->apiPut('/leads/' . $uid, $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
