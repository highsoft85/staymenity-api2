<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithColumnWidths, WithStyles
{
    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function collection()
    {
        $list[] = [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'E-mail',
            'phone' => 'Phone',
            'roles' => 'Roles',
            'last_login_at' => 'Last Login',
            'banned_at' => 'Banned',
            'status' => 'Status',
            'created_at' => 'Created',
        ];
        User::ordered()->chunk(100, function ($oUsers) use (&$list) {
            foreach ($oUsers as $oUser) {
                /** @var User $oUser */
                $list[] = [
                    'id' => $oUser->id,
                    'first_name' => $oUser->first_name,
                    'last_name' => $oUser->last_name,
                    'email' => $oUser->email,
                    'phone' => $oUser->phone,
                    'roles' => $oUser->roles()->pluck('title')->toArray(),
                    'last_login_at' => !is_null($oUser->last_login_at)
                        ? $oUser->last_login_at->format('m/d/Y H:i:s')
                        : '',
                    'banned_at' => !is_null($oUser->banned_at)
                        ? $oUser->banned_at->format('m/d/Y H:i:s')
                        : '',
                    'status' => $oUser->statusText,
                    'created_at' => $oUser->created_at->format('m/d/Y H:i:s'),
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
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            //'K' => 20,
            //'L' => 20,
            //'M' => 20,
            //'N' => 20,
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
