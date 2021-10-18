<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class PRExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function query()
    {
        $orderDetail = OrderDetail::join('order_heads', 'order_details.orders_id', '=', 'order_heads.order_id')->where('order_heads.order_id', $this->order_id)->select('boatName', 'department', 'prDate', 'noPr', 'itemName', 'quantity', 'items.unit', 'items.serialNo', 'codeMasterItem', 'note');

        return $orderDetail;
    }

    public function headings(): array{
        return ['Nama Kapal', 'Department/Bagian', 'Tanggal PR', 'Nomor PR', 'Nama Barang', 'Quantity', 'Satuan', 'Serial No', 'Code Master Item', 'Note'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:J1')->applyFromArray([
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
