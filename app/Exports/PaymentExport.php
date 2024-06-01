<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Listing;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentExport implements FromCollection, WithColumnWidths, WithStyles
{
    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function collection()
    {
        $list[] = [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'reservation' => 'Reservation',
            'amount' => 'Amount',
            'amount_without_service' => 'Amount without Service',
            'service_fee' => 'Service Fee',
            'charge' => 'Charge',
            'status' => 'Status',
            'created_at' => 'Created',
        ];
        Payment::ordered()->chunk(100, function ($oPayments) use (&$list) {
            foreach ($oPayments as $oPayment) {
                /** @var Payment $oPayment */
                $list[] = [
                    'id' => $oPayment->id,
                    'from' => '#' . $oPayment->userFrom->id . ' ' . $oPayment->userFrom->fullName,
                    'to' => '#' . $oPayment->userTo->id . ' ' . $oPayment->userTo->fullName,
                    'reservation' => '#' . $oPayment->reservation->id . ' ' . $oPayment->reservation->paymentDescriptionDate,
                    'amount' => $oPayment->amount,
                    'amount_without_service' => $oPayment->amountWithoutService,
                    'service_fee' => $oPayment->service_fee,
                    'charge' => $oPayment->charges()->sum('amount'),
                    'status' => $oPayment->statusText,
                    'created_at' => $oPayment->created_at->format('m/d/Y H:i:s'),
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
            'D' => 30,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 20,
            'J' => 20,
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
        ];
    }
}
