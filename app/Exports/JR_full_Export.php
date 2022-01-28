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

class JR_full_Export implements FromCollection , ShouldAutoSize , WithHeadings , WithEvents
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
               'FORM Permintaan Perbaikan'
            ],
            // table data
            [
            'No.',
            'cabang',
            '#ID JR',
            'Tanggal JR',
            'Nomor JR',
            'Created By',
            'Maintenance',
            'Nama Kapal',
            'Lokasi',
            'Quantity',
            'Keterangan',
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
        ->where('job_details.cabang', Auth::user()->cabang)
        ->orderBy('job_heads.updated_at', 'desc')->get();

        
        return $jobs;
    }
}
