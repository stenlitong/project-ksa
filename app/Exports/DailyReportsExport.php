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
        if($this -> taskType == 'Operational Transhipment'){
            $operationalData = OperationalBoatData::join('tugs', 'tugs.id', '=', 'operational_boat_data.tug_id')
            ->join('barges', 'barges.id', '=', 'operational_boat_data.barge_id')
            ->where('status', 'Finalized')
            ->where(function ($query){
                $query
                ->where('taskType', 'Operational Transhipment')
                ->orWhere('taskType', 'Return Cargo');
            })
            ->where('tug_id', $this -> tug_id)
            ->where('barge_id', $this -> barge_id)
            ->whereMonth('operational_boat_data.created_at', $this -> month)
            ->whereYear('operational_boat_data.created_at', $this -> year)
            ->select('tugName', 'bargeName', 'from', 'to', DB::raw('CONCAT(MONTHNAME(operational_boat_data.created_at),"/",YEAR(operational_boat_data.created_at)) as Shipment'), 'cargoAmountEnd', 'cargoAmountEndCargo', 'jetty', 'faVessel',
                    'arrivalPOL', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', 'arrivalPODGeneral', 'arrivalPODCargo', 'startAsideMVTranshipment', 'startAsideMVCargo', 'asideMVTranshipment', 'asideMVCargo', 'commMVTranshipment', 'commMVCargo', 'compMVTranshipment', 'compMVCargo', 'cOffMVTranshipment', 'cOffMVCargo', 'departureJetty', 'departureTime', 'sailingToJetty', 'berthing', 'prepareLdg', 'ldgTime', 'ldgRate', 'unberthing', 'document', 'sailingToMV', 'sailingToMVCargo', 'maneuver', 'maneuverCargo', 'dischTime', 'dischTimeCargo', 'dischRate', 'dischRateCargo', 'cycleTime', 'cycleTimeCargo');
        }else{
            $operationalData = OperationalBoatData::join('tugs', 'tugs.id', '=', 'operational_boat_data.tug_id')
            ->join('barges', 'barges.id', '=', 'operational_boat_data.barge_id')
            ->where('status', 'Finalized')
            ->where('tug_id', $this -> tug_id)
            ->where('barge_id', $this -> barge_id)
            ->where('taskType', 'Operational Shipment')
            ->whereMonth('operational_boat_data.created_at', $this -> month)
            ->whereYear('operational_boat_data.created_at', $this -> year)
            ->select('tugName', 'bargeName', 'from', 'to', 'customer', DB::raw('CONCAT(MONTHNAME(operational_boat_data.created_at),"/",YEAR(operational_boat_data.created_at)) as Shipment'), 'jetty', 'arrivalTime', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', 'arrivalPODGeneral', 'startAsidePOD', 'asidePod', 'commenceLoadPOD', 'completedLoadingPOD', 'cOffPOD', 'departurePOD', 'totalTime');
        }

        return $operationalData;
    }

    public function headings(): array{
        if($this -> taskType == 'Operational Transhipment'){
            return ['Tug', 'Barge', 'From', 'To', 'Shipment', 'Quantity Transhipment', 'Quantity Return Cargo', 'Jetty', 
            'F/A Vessel', 'Arrival POL', 
            'Start Aside (L)', 'Aside (L)', 'Commence Loading (L)', 'Complete Loading (L)', 'Cast Off (L)',
            'D.O.H', 'D.O.B', 'Departure POD', 'Arrival POD Transhipment', 'Arrival POD Return Cargo', 'Start Aside (MV) Transhipment', 'Start Aside (MV) Return Cargo', 'Aside (MV) Transhipment', 'Aside (MV) Cargo', 'Commence Loading (MV) Transhipment', 'Commence Loading (MV) Cargo', 'Complete Loading (MV) Transhipment', 'Complete Loading (MV) Cargo', 'Cast Off (MV) Transhipment', 'Cast Off (MV) Cargo', 'Departure To Jetty (Transhipment)', 'Departure To (Cargo)',
            'Sailing To Jetty', 'Berthing', 'Prepare Ldg', 'Ldg Time', 'Ldg Rate', 'Unberthing', 'Document', 'Sailing To MV Transhipment', 'Sailing To MV Cargo', 'Maneuver Transhipment', 'Maneuver Cargo', 'Disch Time Transhipment', 'Disch Time Cargo', 'Disch Rate / Day Transhipment', 'Disch Rate / Day Cargo', 'Cycle Time Transhipment', 'Cycle Time Cargo'];
        }else{
            return ['Tug', 'Barge', 'From', 'To', 'Customer', 'Shipment','Jetty', 
            'Time Arrival', 
            'Start Aside (L)', 'Aside (L)', 'Commence Loading (L)', 'Complete Loading (L)', 'Cast Off (L)',
            'Doc Overhand', 'Doc On Boat', 'Departure Time', 'Arrival POD', 'Start Aside POD', 'Aside POD', 'Commence Loading POD', 'Complete Loading POD', 'Cast Off POD', 'Departure Time POD', 
            'totalTime'];
        }
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:X1')->applyFromArray([
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
