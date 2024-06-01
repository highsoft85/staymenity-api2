<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Listing;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ListingExport implements FromCollection, WithColumnWidths, WithStyles
{
    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function collection()
    {
        $list[] = [
            'id' => 'ID',
            'host_name' => 'Host',
            'host_email' => 'Host E-mail',
            'title' => 'Title',
            'type' => 'Type',
            'price' => 'Price',
            'views' => 'Views',
            'reservations' => 'Reservations',
            'guests_site' => 'Guests',
            'rent_time_min' => 'Rent Time',
            'published_at' => 'Published',
            'banned_at' => 'Banned',
            'status' => 'Status',
            'created_at' => 'Created',
        ];
        Listing::ordered()->chunk(100, function ($oListings) use (&$list) {
            foreach ($oListings as $oListing) {
                /** @var Listing $oListing */
                $list[] = [
                    'id' => $oListing->id,
                    'host_name' => '#' . $oListing->user->id . ' ' . $oListing->user->fullName,
                    'host_email' => $oListing->user->email,
                    'title' => $oListing->title,
                    'type' => $oListing->type->title,
                    'price' => $oListing->price,
                    'views' => (string)visit()->count($oListing),
                    'reservations' => (string)$oListing->reservationsActive()->count(),
                    'guests_site' => $oListing->guests_size,
                    'rent_time_min' => $oListing->rent_time_min,
                    'published_at' => !is_null($oListing->published_at)
                        ? $oListing->published_at->format('m/d/Y H:i:s')
                        : '',
                    'banned_at' => !is_null($oListing->banned_at)
                        ? $oListing->banned_at->format('m/d/Y H:i:s')
                        : '',
                    'status' => $oListing->statusText,
                    'created_at' => $oListing->created_at->format('m/d/Y H:i:s'),
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
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
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
            'L' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'M' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'N' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ],
        ];
    }
}
