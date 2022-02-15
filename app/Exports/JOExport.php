<?php

namespace App\Exports;

use App\Models\JobDetails;
use Maatwebsite\Excel\Concerns\FromCollection;

class JOExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return JobDetails::all();
    }
}
