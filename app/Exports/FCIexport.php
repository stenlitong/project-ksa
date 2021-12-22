<?php

namespace App\Exports;

use App\Models\FCIexports;
use App\Models\formclaims;
use App\Models\headerformclaim;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\Fromview;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class FCIexport implements FromQuery , ShouldAutoSize , WithHeadings , WithEvents 
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
                $event->sheet->getStyle('A1:P1')->applyFromArray([
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
                            'color' => ['argb' => 'FFFF0000']],
                    ]
                ]);
                // $event->sheet->appendRows(array(
                //     array(' '),
                //     array('Nomor PR', $this->noPr),
                //     array('Nomor PO', $this->noPo),
                //     array('Nama Kapal', $this->boatName),
                //     array('Tipe PPN', $this->ppn . ' %'),
                //     array('Diskon', $this->discount . ' %'),
                //     array('Total Harga', 'Rp. ' . number_format($this->price, 2, ",", ".")),
                //     array('Alamat Pengiriman Invoice', $this->invoiceAddress),
                //     array('Alamat Pengiriman Barang', $this->itemAddress),
                //     array('Cabang', $this->cabang),
                // ), $event);
                $event->sheet->getDelegate()->getStyle('A:P')
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); 
                $event->sheet->getDelegate()->getStyle('A:P')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            }
        ];
    }

    public function __construct($identify)
    {
        $this->file_id = $identify;
    }

    public function query()
    {

        $formclaim = formclaims::where('header_id', $this->file_id);
        return $formclaim;
    }

}
