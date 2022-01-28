<?php

namespace App\Exports;

use App\Models\JobHead;
use App\Models\JobDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class JRExport implements FromCollection , ShouldAutoSize , WithHeadings , WithEvents
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id)
    {
        $this->identify = $id;
        $this->created_by = JobHead::join('job_details', 'job_details.jasa_id', '=', 'job_heads.id')
        ->where('job_details.cabang', '=' ,'job_heads.cabang')
        ->pluck('created_by');
        
        $this->check_by = JobHead::join('job_details', 'job_details.jasa_id', '=', 'job_heads.id')
        ->where('job_details.cabang', '=' ,'job_heads.cabang')
        ->pluck('check_by');

        $this->jrDate = JobHead::join('job_details', 'job_details.jasa_id', '=', 'job_heads.id')
        ->where('job_details.cabang', '=' ,'job_heads.cabang')
        ->pluck('jrDate');

        $this->cabang = JobDetails::where('jasa_id','=', $this->identify)->pluck('cabang')[0];
        $this->barge = JobDetails::where('jasa_id','=', $this->identify)->pluck('bargeName')[0];
        $this->tugBoat = JobDetails::where('jasa_id','=', $this->identify)->pluck('tugName')[0];
        $this->lokasi = JobDetails::where('jasa_id','=', $this->identify)->pluck('lokasi')[0];
        $this->quantity = JobDetails::where('jasa_id','=', $this->identify)->pluck('quantity')[0];
        $this->note = JobDetails::where('jasa_id','=', $this->identify)->pluck('note')[0];
    }

    public function headings(): array
    {
        return[
            //title
            [
               'FORM Permintaan Perbaikan'
            ],
            //top part
            [' '],
            ['Nama TugBoat:', $this->tugBoat],
            ['Nama Barge:',  $this->barge],
            ['tgl Permintaan :', $this->jrDate],
            ['lokasi Permintaan :', $this->lokasi],
            [' '],
            // table data
            [
            'No.',
            'Uraian',
            'Quantity'
            ]
        ];
    }

    public function collection()
    {
        $month_now = (int)(date('m'));

        if($month_now <= 3){
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-31');
            $str_month = 'Jan - Mar';
        }elseif($month_now > 3 && $month_now <= 6){
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-30');
            $str_month = 'Apr - Jun';
        }elseif($month_now > 6 && $month_now <= 9){
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-30');
            $str_month = 'Jul - Sep';
        }else{
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-31');
            $str_month = 'Okt - Des';
        }

        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        $jobs = JobDetails::join('job_heads', 'job_heads.id', '=', 'job_details.jasa_id')
        ->where('job_heads.status', 'like', 'Job Request Approved By Logistics')
        ->whereBetween('job_heads.created_at', [$start_date, $end_date])
        ->where('cabang', 'like', Auth::user()->cabang)
        ->orderBy('job_heads.updated_at', 'desc')->get();

        // dd($jobs);
        return $jobs;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true ,
                        // 'color' => ['argb' => 'ffffffff']
                    ]
                ]);
                $event->sheet->getStyle('A5:E5')->applyFromArray([
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
                    array($this->created_at),
                    array('Prepared by:' , ' ' , ' Disetujui :' , ' ' ,' ' ,'Diketahui :'),
                    array(' '),
                    array(' '),
                    array(' '),
                    array($this->created_by, ' ' , $this->check_by , ' ' ,' ' , 'maintance'),
                ), $event);
                
                $event->sheet->getDelegate()->getStyle('A:E')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
