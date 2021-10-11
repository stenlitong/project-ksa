<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\User;
Use \Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderInExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
{
    public function query()
    {
        // Find order from logistic role/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->orWhere('role_user.role_id' , '=', '4')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        return OrderDetail::query()->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('items', 'items.id', 'order_details.item_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->select('approved_at', 'itemName', 'items.serialNo', 'quantity', 'items.unit', 'supplierName', 'descriptions')->where('status', 'like', '%' . 'Order Completed' . '%', 'and', 'created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc');
    }

    public function headings(): array{
        return ['Tanggal Keluar', 'Item Barang Masuk', 'Serial Number', 'Qty', 'Satuan', 'No. Resi', 'Note'];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
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
