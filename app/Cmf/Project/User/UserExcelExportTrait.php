<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

trait UserExcelExportTrait
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $name = 'users-export-' . now()->format('m-d-Y');
        return Excel::download(new UserExport(), $name . '.xlsx');
    }
}
