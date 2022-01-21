<?php

namespace App\Exports;

use App\Models\Rekapdana;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class RekapAdminExport implements FromQuery , ShouldAutoSize , WithHeadings , WithEvents
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return[
            //title
            [
               'Rekapulasi Dana'
            ],
            [
            // table data
            'No' ,
            'Nama File',
            'Cabang',
            'Nama TugBoat/Barge',
            'Periode Awal',
            'Periode Akhir',
            'Nilai Jumlah Di Ajukan'
            ]
        ];
    }
    public function query()
    {
        $RekapExpo = Rekapdana::whereColumn('created_at' , '<=', 'DateNote2');
        return $RekapExpo;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true ,
                        // 'color' => ['argb' => 'ffffffff']
                    ]
                ]);
                $event->sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => [
                        'bold' => true ,
                        // 'color' => ['argb' => 'ffffffff']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFF8080']
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                    ]]
                ]);
                
                $event->sheet->getDelegate()->getStyle('A:G')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
