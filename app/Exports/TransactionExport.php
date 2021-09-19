<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;


class TransactionExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return Transaction::query()->select(['order_id', 'crew_id', 'boatName', 'itemName', 'quantity', 'serialNo', 'codeMasterItem', 'noPr', 'department', 'company', 'prDate', 'note'])->where('id', $this->id);
    }

    public function headings(): array{
        return ['Order ID', 'Crew ID', 'Nama Kapal', 'Nama Barang', 'Qty', 'Serial Number / Part Number', 'Code Master Item', 'No. PR', 'Department', 'Perusahaan', 'Tanggal PR', 'Note'];
    }
}
