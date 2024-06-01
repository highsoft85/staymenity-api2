<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Transformers;

use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Models\LeadsV1;
use App\Services\Hostfully\Models\Webhooks;
use Carbon\Carbon;

class WebhookTransformer
{
    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data)
    {
        return [

        ];
    }
}
