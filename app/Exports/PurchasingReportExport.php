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
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->orWhere('role_user.role_id' , '=', '4')->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 30 days only
        return OrderHead::join('suppliers', 'suppliers.id', '=', 'order_heads.id')->join('order_details', 'order_details.orders_id', '=', 'order_heads.order_id')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Order Completed' . '%', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->select('prDate', 'noPr', 'suppliers.supplierName', 'noPo', 'golongan', 'boatName', 'descriptions');
    }

    public function headings(): array{
        return ['Tanggal PR', 'Nomor PR', 'Supplier', 'Nomor Po', 'Golongan', 'Nama Kapal', 'Keterangan'];
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

// <?php

// namespace App\Exports;

// use Illuminate\Support\Facades\Auth;
// use App\Models\OrderHead;
// use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Events\AfterSheet;
// Use \Carbon\Carbon;

// class PurchasingReportExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function query()
//     {
//         // Find order from user/goods out
//         $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->orWhere('role_user.role_id' , '=', '4')->pluck('users.id');
        
//         // Find all the items that has been approved from the user | last 30 days only
//         return OrderHead::join('suppliers', 'suppliers.id', '=', 'order_heads.id')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Order Completed' . '%', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->select('prDate', 'noPr', 'suppliers.supplierName', 'noPo', 'boatName', 'descriptions');
//     }

//     public function headings(): array{
//         return ['Tanggal PR', 'Nomor PR', 'Supplier', 'Nomor Po', 'Nama Kapal', 'Keterangan', 'Golongan'];
//     }
    
//     public function registerEvents(): array
//     {
//         return [
//             AfterSheet::class => function (AfterSheet $event) {
//                 $event->sheet->getStyle('A1:G1')->applyFromArray([
//                     'font' => [
//                         'color' => ['argb' => 'FFFFFF']
//                     ],
//                     'fill' => [
//                         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
//                         'color' => ['argb' => 'A01D23']
//                     ]
//                 ]);
//             }
//         ];
//     }
// }
