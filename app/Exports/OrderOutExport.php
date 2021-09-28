<?php

namespace App\Exports;

use App\Models\OrderHead;
use App\Models\User;
Use \Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderOutExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
{
    public function query()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');

        return OrderHead::query()->join('order_details', 'order_details.orders_id', '=', 'order_heads.order_id')->whereIn('user_id', $users)->select(['order_id', 'approved_at', 'itemName', 'serialNo', 'quantity', 'unit', 'noResi', 'descriptions'])->where('status', 'completed')->where('order_details.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc');
    }

    public function headings(): array{
        return ['Order ID', 'Tanggal Keluar', 'Item Barang Keluar', 'Serial Number', 'Qty', 'Satuan', 'No. Resi', 'Note'];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'color' => ['argb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'A01D23']
                    ]

                ]);
            }
        ];
    }
    
}
