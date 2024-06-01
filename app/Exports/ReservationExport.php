<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReservationExport implements FromCollection, WithColumnWidths, WithStyles
{
    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function collection()
    {
        $list[] = [
            'id' => 'ID',
            'user' => 'User',
            'listing' => 'Listing',
            'price' => 'Price',
            'total_price' => 'Total Price',
            'service_fee' => 'Service Fee',
            'start_at' => 'Start',
            'finish_at' => 'Finish',
            'payment' => 'Payment',
            'status' => 'Status',
            'created_at' => 'Created',
        ];
        Reservation::ordered()->chunk(100, function ($oReservations) use (&$list) {
            foreach ($oReservations as $oReservation) {
                /** @var Reservation $oReservation */
                $list[] = [
                    'id' => $oReservation->id,
                    'user' => '#' . $oReservation->user->id . ' ' . $oReservation->user->fullName,
                    'listing' => '#' . $oReservation->listing->id . ' ' . $oReservation->listing->title,
                    'price' => $oReservation->price,
                    'total_price' => $oReservation->total_price,
                    'service_fee' => $oReservation->service_fee,
                    'start_at' => $oReservation->start_at->format('m/d/Y H:i:s'),
                    'finish_at' => $oReservation->finish_at->addMinute()->format('m/d/Y H:i:s'),
                    'payment' => !is_null($oReservation->payment) ? '#' . $oReservation->payment->id : '',
                    'status' => $oReservation->statusText,
                    'created_at' => $oReservation->created_at->format('m/d/Y H:i:s'),
                ];
            }
        });
        return collect($list);
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 30,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'B' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'C' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'D' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'G' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'H' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'I' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'J' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'K' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
        ];
    }
}
