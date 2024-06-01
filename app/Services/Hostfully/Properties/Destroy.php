<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Properties;

use App\Services\Hostfully\BaseHostfullyService;

class Destroy extends BaseHostfullyService
{
    /**
     * @param string $id
     * @param array $data
     * @return array
     */
    public function __invoke(string $id, array $data): array
    {
        $data = $this->apiDeleteRaw('/properties/' . $id, $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return [];
    }
}
