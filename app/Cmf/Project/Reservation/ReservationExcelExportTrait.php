<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

use App\Exports\ReservationExport;
use Maatwebsite\Excel\Facades\Excel;

trait ReservationExcelExportTrait
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $name = 'reservations-export-' . now()->format('m-d-Y');
        return Excel::download(new ReservationExport(), $name . '.xlsx');
    }
}
