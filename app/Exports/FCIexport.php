<?php

namespace App\Exports;

use App\Models\FCIexports;
use App\Models\formclaims;
use Maatwebsite\Excel\Concerns\Fromview;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FCIexport implements FromCollection , ShouldAutoSize , WithHeadings , WithEvents 
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'No.',
            'tgl_insiden','tgl_formclaim' , 'name', 
            'item' ,'jenis_incident','no_FormClaim',
            'barge','TSI_barge','TSI_Tugboat',
            'deductible','amount','surveyor',
            'tugBoat','incident','description'
            ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->getStyle('A1:P1')->applyFromArray(
                    [
                        'font' => [
                            'bold' => true ,
                            'color' => ['argb' => 'FFFFFFFF']
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['argb' => 'FF8B0000'],
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FFFF0000'],
                            ],
                        ]
                    ]
                );
            }
        ];
    }

    public function collection()
    {
        return formclaims::all();
    }

}
