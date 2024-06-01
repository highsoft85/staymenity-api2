<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

use App\Exports\ListingExport;
use Maatwebsite\Excel\Facades\Excel;

trait ListingExcelExportTrait
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $name = 'listings-export-' . now()->format('m-d-Y');
        return Excel::download(new ListingExport(), $name . '.xlsx');
    }
}
