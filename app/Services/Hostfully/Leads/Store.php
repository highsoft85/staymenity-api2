<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Leads;

use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\MockLeadsHostfullyTrait;
use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Transformers\LeadTransformer;

class Store extends BaseHostfullyService
{
    use MockLeadsHostfullyTrait;

    /**
     * @param array $data
     * @return array
     */
    public function __invoke(array $data): array
    {
        if (envIsTesting()) {
            loggerHostfully('/leads', 'POST');
            loggerHostfully($data, 'DATA');
            if ($data[Leads::STATUS] === Leads::STATUS_BLOCKED) {
                return $this->mockCreateLead((new LeadTransformer())->transformFromV2ToV1CreateBlock($data));
            }
            return $this->mockCreateLead((new LeadTransformer())->transformFromV2ToV1Create($data));
        }
        $data = $this->setLeadsVersion()->apiPost('/leads', $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
