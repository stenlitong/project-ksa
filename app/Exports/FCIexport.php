<?php

namespace App\Exports;

use App\Models\FCIexports;
use App\Models\formclaims;
use App\Models\headerformclaim;
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

class FCIexport implements FromQuery , ShouldAutoSize , WithHeadings , WithEvents , WithHeadingRow
// , WithCustomStartCell
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headingRow(): int
    {
        return 1;
    }

    // public function startCell(): string
    // {
    //     return 'A12';
    // }

    public function __construct($identify)
    {
        $this->file_id = $identify;
        $this->created_at = formclaims::where('header_id', $identify)->pluck('created_at')[0];
        $this->name = formclaims::where('header_id', $identify)->pluck('name')[0];
        $this->no_FormClaim = formclaims::where('header_id', $identify)->pluck('no_FormClaim')[0];
        $this->tgl_formclaim = formclaims::where('header_id', $identify)->pluck('tgl_formclaim')[0];
        $this->tgl_insiden = formclaims::where('header_id', $identify)->pluck('tgl_insiden')[0];
        $this->incident = formclaims::where('header_id', $identify)->pluck('incident')[0];
        $this->surveyor = formclaims::where('header_id', $identify)->pluck('surveyor')[0];
        $this->TSI_Tugboat = formclaims::where('header_id', $identify)->pluck('TSI_Tugboat')[0];
        $this->TSI_barge = formclaims::where('header_id', $identify)->pluck('TSI_barge')[0];
        $this->mata_uang_TSI = formclaims::where('header_id', $identify)->pluck('mata_uang_TSI')[0];
        $this->barge = formclaims::where('header_id', $identify)->pluck('barge')[0];
        $this->tugBoat = formclaims::where('header_id', $identify)->pluck('tugBoat')[0];

        $this->jenis_incident = formclaims::where('header_id', $identify)->pluck('jenis_incident')->all();
        $this->item = formclaims::where('header_id', $identify)->pluck('item')->all();
        $this->description = formclaims::where('header_id', $identify)->pluck('description')->all();
        $this->deductible = formclaims::where('header_id', $identify)->pluck('deductible')->all();
        $this->amount = formclaims::where('header_id', $identify)->pluck('amount')->all();
        $this->mata_uang_amount = formclaims::where('header_id', $identify)->pluck('mata_uang_amount')->all();
    }

    public function headings(): array
    {
        return[
            //title
            [
               'FORM CLAIM INSURANCE'
            ],
            [
            // table data
            'No.',
            'jenis_incident',
            'item' ,
            'description',
            'deductible',
            'amount',
            'mata_uang_amount'
            ]
        ];
    }
    

    public function query()
    {
        $formclaim = formclaims::where('header_id', $this->file_id);
        return $formclaim;
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
                // Append row as very last
                $event->sheet->appendRows(array(
                    array(' '),
                    array('No FormClaim :', $this->no_FormClaim),
                    array('tgl FormClaim :', $this->tgl_formclaim),
                    array('tgl Insiden :', $this->tgl_insiden),
                    array('Incident :', $this->incident),
                    array('Surveyor :', $this->surveyor),
                    array('TSI Tugboat :', $this->mata_uang_TSI . ' . ' . $this->TSI_Tugboat),
                    array('TSI barge :', $this->mata_uang_TSI . ' . ' . $this->TSI_barge),
                    array('Barge :', $this->barge),
                    array('TugBoat :', $this->tugBoat),
                    array(' '),
                    // array(' '),
                    // array('No.','jenis_incident','item' ,'description','deductible','amount'),
                    // array(' ', $this->jenis_incident , $this->item, $this->description,$this->deductible , $this->amount . '-' . $this->mata_uang_amount),
                    array(' ',' ',' ',' ','Total:' , ' '),
                    array('Created At :', $this->created_at),
                    array(' '),
                    array(' '),
                    array('Prepared by:' , ' ' , ' ' , ' ' ,' ' ,'Checked by :'),
                    array(' '),
                    array(' '),
                    array(' '),
                    array($this->name, ' ' , ' ' , ' ' ,' ' , 'Yusmiati'),
                ), $event);
                
                $event->sheet->getDelegate()->getStyle('A:G')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
