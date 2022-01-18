<?php

namespace App\Exports;

use App\Models\OperationalBoatData;
use App\Models\Tug;
use App\Models\Barge;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DailyReportsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    public function __construct($taskType, $tugId, $bargeId, $month, $year)
    {
        $this -> taskType = $taskType;
        $this -> tug_id = $tugId;
        $this -> barge_id = $bargeId;
        $this -> month = $month;
        $this -> year = $year;
    }


    public function query()
    {  
        $arrivalPOD = $this -> taskType == 'Operational Shipment' || $this -> taskType == 'Operational Transhipment' ? 'arrivalPODGeneral' : 'arrivalPODCargo';

        $startAsideMV = $this -> taskType == 'Operational Transhipment' ? 'startAsideMVTranshipment' : 'startAsideMVCargo';

        $asideMV = $this -> taskType == 'Operational Transhipment' ? 'asideMVTranshipment' : 'asideMVCargo';

        $commMV = $this -> taskType == 'Operational Transhipment' ? 'commMVTranshipment' : 'commMVCargo';

        $compMV = $this -> taskType == 'Operational Transhipment' ? 'compMVTranshipment' : 'compMVCargo';

        $cOffMV = $this -> taskType == 'Operational Transhipment' ? 'cOffMVTranshipment' : 'cOffMVCargo';

        $departure = $this -> taskType == 'Operational Transhipment' ? 'departureJetty' : 'departureTime';

        $operationalData = OperationalBoatData::join('tugs', 'tugs.id', '=', 'operational_boat_data.tug_id')
        ->join('barges', 'barges.id', '=', 'operational_boat_data.barge_id')
        ->where('status', 'Finalized')
        ->where('tug_id', $this -> tug_id)
        ->where('barge_id', $this -> barge_id)
        ->where('taskType', $this -> taskType)
        ->whereMonth('operational_boat_data.created_at', $this -> month)
        ->whereYear('operational_boat_data.created_at', $this -> year)
        ->select('tugName', 'bargeName', 'from', 'to', DB::raw('CONCAT(MONTHNAME(operational_boat_data.created_at),"/",YEAR(operational_boat_data.created_at)) as Shipment'), 'cargoAmountEnd', 'jetty', 'faVessel',
                'arrivalPOL', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', $arrivalPOD, $startAsideMV, $asideMV, $commMV, $compMV, $cOffMV, $departure, 'sailingToJetty', 'berthing', 'prepareLdg', 'ldgTime', 'ldgRate', 'unberthing', 'document', 'sailingToMV', 'maneuver', 'dischTime', 'dischRate', 'cycleTime');

        return $operationalData;
    }

    public function headings(): array{
        return ['Tug', 'Barge', 'From', 'To', 'Shipment', 'Quantity', 'Jetty', 'F/A Vessel', 'Arrival POL', 
        'Start Aside (L)', 'Aside (L)', 'Commence Load (L)', 'Completed Loading (L)', 'C/OFF(L)',
        'D.O.H', 'D.O.B', 'Departure POD', 'Arrival POD', 'Start Aside MV', 'Aside MV', 'Comm MV', 'Compp MV', 'C/Off MV', 'Departure To', 
        'Sailing To Jetty', 'Berthing', 'Prepare Ldg', 'Ldg Time', 'Ldg Rate', 'Unberthing', 'Document', 'Sailing To MV', 'Maneuver', 'Disch Time', 'Disch Rate / Day', 'Cycle Time'];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:AJ1')->applyFromArray([
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
