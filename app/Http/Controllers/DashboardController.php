<?php
namespace App\Http\Controllers;

use App\Models\ItemBelowStock;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\ApList;
use App\Models\OperationalBoatData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Storage;
use Response;
use validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use App\Models\Barge;
use App\Models\documents;
use App\Models\documentberau;
use App\Models\documentbanjarmasin;
use App\Models\documentrpk;
use App\Models\documentsamarinda;
use App\Models\spgrfile;
use App\Models\NoteSpgr;
use Illuminate\Http\Request;
use Matrix\Operators\Operator;

class DashboardController extends Controller
{
    public function index(Request $request){
        if(Auth::user()->hasRole('crew')){
            // Get all the order within the logged in user within 6 month
            $orderHeads = OrderHead::with('user')->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->latest()->paginate(7);

            // Get the orderDetail from orders_id within the orderHead table 
            // $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Request Completed (Crew)')
                ->orWhere('status', 'like', 'Request Rejected By Logistic');
            })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'Request In Progress By Logistic')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery');
            })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();

            return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));

        }elseif(Auth::user()->hasRole('logistic')){
            // Search functonality
            if(request('search')){
                $orderHeads = OrderHead::with('user')->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere( 'order_id', 'like', '%'. request('search') .'%');
                })->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->latest()->paginate(7)->withQueryString();
                //->whereBetween('created_at', [$start_date, $end_date])
            }else{
                $orderHeads = OrderHead::with('user')->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->latest()->paginate(7)->withQueryString();
            }

            // Get all the order detail
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Revised' . '%')
                ->orWhere('status', 'like', '%' . 'Finalized' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->count();

            $items_below_stock = $this -> checkStock();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'items_below_stock'));
            
        }elseif(Auth::user()->hasRole('supervisorLogistic') or Auth::user()->hasRole('supervisorLogisticMaster')){
            // Find order from logistic role, then they can approve and send it to the purchasing role
            // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
            $users = User::whereHas('roles', function($query){
                $query->where('name', 'logistic');
            })->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

            if(request('search')){
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere('order_id', 'like', '%'. request('search') .'%');
                })->whereYear('created_at', date('Y'))->latest()->paginate(6);
            }else{
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->whereYear('created_at', date('Y'))->latest()->paginate(6)->withQueryString();
            }

            // Then find all the order details from the orderHeads
            // $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->count();

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'In Progress By Supervisor')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereYear('created_at', date('Y'))->count();

            $items_below_stock = $this -> checkStock();

            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'items_below_stock'));

        }elseif(Auth::user()->hasRole('purchasing')){
            $default_branch = 'Jakarta';

            // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
            // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', $default_branch)->pluck('users.id');
            $users = User::whereHas('roles', function($query){
                $query->where('name', 'logistic');
            })->where('cabang', 'like', $default_branch)->pluck('users.id');
            
            if(request('search')){
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere('order_id', 'like', '%'. request('search') .'%');
                })->whereYear('created_at', date('Y'))->latest()->paginate(6);
            }else{
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->latest()->paginate(6)->withQueryString();
            }

            // Then find all the order details from the orderHeads
            // $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order In Progress By Supervisor')
                // ->orWhere('status', 'like', 'Order In Progress By Purchasing')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Revised' . '%')
                ->orWhere('status', 'like', '%' . 'Finalized' . '%')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress', 'default_branch'));

        }elseif(Auth::user()->hasRole('adminPurchasing')){
            // Show the form AP page
            $apList = ApList::with('orderHead')->where('cabang', Auth::user()->cabang)->whereYear('created_at', date('Y'))->latest()->paginate(7);
            
            // Get all the supplier
            $suppliers = Supplier::latest()->get();

            // Default branch is Jakarta
            $default_branch = 'Jakarta';

            return view('adminPurchasing.adminPurchasingFormAp', compact('apList', 'default_branch', 'suppliers'));
        }elseif(Auth::user()->hasRole('purchasingManager')){
            // Default branch is Jakarta, first time login
            $default_branch = 'Jakarta';

            // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
            // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
            $users = User::whereHas('roles', function($query){
                $query->where('name', 'logistic');
            })->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

            if(request('search')){
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere('order_id', 'like', '%'. request('search') .'%');
                })->whereYear('created_at', date('Y'))->latest()->paginate(6);
            }else{
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->latest()->paginate(6)->withQueryString();
            }

            // Then find all the order details from the orderHeads
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order In Progress By Supervisor')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Revised' . '%')
                ->orWhere('status', 'like', '%' . 'Finalized' . '%')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

            // Get all suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasingManager.purchasingManagerDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'default_branch', 'completed', 'in_progress'));
        }elseif(Auth::user()->hasRole('adminOperational')){

            // Sum The DAYS Of Each Condition, Not The Count Of The Ship
            $dok_days = OperationalBoatData::where('status', 'On Going')->sum('DOKDays'); // 
            $standbyDOK_days = OperationalBoatData::where('status', 'On Going')->sum('standbyDOKDays'); //

            // Ship Count
            $dok_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'DOK')->count();
            $perbaikan_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Perbaikan')->count();
            $kandas_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Kandas')->count();
            $tungguDOK_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Tunggu DOK')->count();
            $tungguTugboat_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Tunggu Tugboat atau Barge')->count();
            $tungguDokumen_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Tunggu Dokumen')->count();
            $standbyDOK_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Standby DOK')->count();
            $bocor_ship_count = OperationalBoatData::where('status', 'On Going')->where('condition', 'Bocor')->count();


            // $perbaikan_days = OperationalBoatData::where('status', 'On Going')->sum('perbaikanDays');
            // $kandas_days = OperationalBoatData::where('status', 'On Going')->sum('kandasDays');
            // $tungguDOK_days = OperationalBoatData::where('status', 'On Going')->sum('tungguDOKDays');
            // $tungguTug_days = OperationalBoatData::where('status', 'On Going')->sum('tungguTugDays');
            // $tungguDokumen_days = OperationalBoatData::where('status', 'On Going')->sum('tungguDokumenDays');
            // $bocor_days = OperationalBoatData::where('status', 'On Going')->sum('bocor');

            // formula => Total lost time : 
            // DOK - standby belum DOK
            $total_lost_time = $dok_days - $standbyDOK_days;

            // formula => AKTIF : 
            //  (31*total barge)-Total lost time:
            $total_barge = Barge::count();
            $aktif = (31 * $total_barge) - $total_lost_time;
            
            // formula => percentage ship's activity :
            // Aktif / (31*total barge) * 100
            $percentage_ship_activity = 0;
            if($total_barge > 0){
                $percentage_ship_activity = $aktif / (31 * $total_barge) * 100;
            }

            return view('adminOperational.adminOperationalDashboard', compact('dok_ship_count', 'perbaikan_ship_count', 'kandas_ship_count', 'tungguDOK_ship_count', 'tungguTugboat_ship_count', 'tungguDokumen_ship_count', 'standbyDOK_ship_count', 'bocor_ship_count', 'total_lost_time', 'percentage_ship_activity'));
        }elseif(Auth::user()->hasRole('picSite')){
            return view('picsite.picDashboard');
        }elseif(Auth::user()->hasRole('picAdmin')){
            if (request('search1') == 'All') {
                $document = DB::table('documents')->get();
                $documentberau = DB::table('beraudb')->get();
                $documentbanjarmasin = DB::table('banjarmasindb')->get();
                $documentsamarinda = DB::table('samarindadb')->get();
                $docrpk = DB::table('rpkdocuments')->get();
            }
            elseif (request('search1')) {
                $document = DB::table('documents')->where('cabang', request('search1'))->latest()->get();
                $documentberau = DB::table('beraudb')->where('cabang', request('search1'))->latest()->get();
                $documentbanjarmasin = DB::table('banjarmasindb')->where('cabang', request('search1'))->latest()->get();
                $documentsamarinda = DB::table('samarindadb')->where('cabang', request('search1'))->latest()->get();
                $docrpk = DB::table('rpkdocuments')->where('cabang', request('search1'))->latest()->get();  
            }
            else{
                $document = DB::table('documents')->get();
                $documentberau = DB::table('beraudb')->get();
                $documentbanjarmasin = DB::table('banjarmasindb')->get();
                $documentsamarinda = DB::table('samarindadb')->get();
                $docrpk = DB::table('rpkdocuments')->get();
            }
            return view('picadmin.picAdminDashboard' , compact('document', 'documentberau' , 'documentbanjarmasin', 'documentsamarinda', 'docrpk'));
        }elseif(Auth::user()->hasRole('picIncident')){
            
            return view('picincident.dashboardincident' );
        }elseif(Auth::user()->hasRole('insurance')){
            $spgrfile = spgrfile::where('cabang', 'Jakarta')->get();
            return view('insurance.Dashboardinsurance', compact('spgrfile'));
        }
    }
    public function checkStock(){
        $items_below_stock = ItemBelowStock::join('items', 'items.id', '=', 'item_below_stocks.item_id')->where('cabang', Auth::user()->cabang)->get();

        return $items_below_stock;
    }
}
