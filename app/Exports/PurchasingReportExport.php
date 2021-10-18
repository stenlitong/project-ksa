<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Models\OrderHead;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Carbon\Carbon;

class PurchasingReportExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
{
    public function query()
    {
        // Find order from logistic role but with different setup with the goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 30 days only
        return OrderHead::join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->select('prDate', 'noPr', 'suppliers.supplierName', 'noPo', 'boatName', 'descriptions')->orderBy('order_heads.updated_at', 'desc');
    }

    public function headings(): array{
        return ['Tanggal PR', 'Nomor PR', 'Supplier', 'Nomor PO', 'Nama Kapal', 'Keterangan'];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
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