<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payment;

use App\Exports\PaymentExport;
use Maatwebsite\Excel\Facades\Excel;

trait PaymentExcelExportTrait
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $name = 'payments-export-' . now()->format('m-d-Y');
        return Excel::download(new PaymentExport(), $name . '.xlsx');
    }
}
