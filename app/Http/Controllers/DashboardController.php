<?php
namespace App\Http\Controllers;

use Storage;
use Response;
use validator;
use Carbon\Carbon;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\ApList;
use App\Models\NoteSpgr;
use App\Models\spgrfile;
use App\Models\Supplier;
use App\Models\documents;
use App\Models\OrderHead;
use App\Models\documentrpk;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\documentberau;
use App\Models\ItemBelowStock;
use App\Models\documentJakarta;
use App\Models\documentsamarinda;
use Illuminate\Support\Facades\DB;
use App\Models\documentbanjarmasin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

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
        }
        elseif(Auth::user()->hasRole('picSite')){
           
            $year = date('Y');
            $month = date('m');
            
            // Fund Request view ----------------------------------------------------------
                if($request->tipefile == 'DANA'){
                    if ($request->cabang == 'Babelan'){
                        $filename = $request->viewdoc;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documents::whereColumn('created_at' , '<=', 'periode_akhir')
                        ->whereNotNull ($filename)
                        ->where($filename, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->pluck($filename)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
                    }
                    if ($request->cabang == 'Berau'){
                        $filename = $request->viewdoc;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')
                        ->whereNotNull ($filename)
                        ->where($filename, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->pluck($filename)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/" . $viewer);
                    }
                    if ($request->cabang == 'Banjarmasin'){
                        $filename = $request->viewdoc;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')
                        ->whereNotNull ($filename)
                        ->where($filename, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->pluck($filename)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/" . $viewer);
                    }
                    if ($request->cabang == 'Samarinda'){
                        $filename = $request->viewdoc;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')
                        ->whereNotNull ($filename)
                        ->where($filename, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->pluck($filename)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/" . $viewer);
                    }
                    if ($request->cabang == 'Jakarta'){
                        $filename = $request->viewdoc;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')
                        ->whereNotNull ($filename)
                        ->where($filename, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->pluck($filename)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('jakarta/' . $year . "/". $month . "/" . $viewer);
                    }
                }
            // RPK view ----------------------------------------------------------
                if($request->tipefile == 'RPK'){
                    if ($request->cabang == 'Babelan'){
                        $filenameRPK = $request->viewdocrpk;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentrpk::where('cabang' , $request->cabang)
                        ->whereNotNull ($filenameRPK)
                        ->where($filenameRPK, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->pluck($filenameRPK)[0];
                        // dd($viewer);
                        return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                    }
                    if ($request->cabang == 'Berau'){
                        $filenameRPK = $request->viewdocrpk;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentrpk::where('cabang' , $request->cabang)
                        ->whereNotNull ($filenameRPK)
                        ->where($filenameRPK, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->pluck($filenameRPK)[0]; 
                        // dd($viewer);
                        return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                    }
                    if ($request->cabang == 'Banjarmasin'){
                        $filenameRPK = $request->viewdocrpk;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentrpk::where('cabang' , $request->cabang)
                        ->whereNotNull ($filenameRPK)
                        ->where($filenameRPK, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->pluck($filenameRPK)[0]; 
                        // dd($viewer);
                        return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                    }
                    if ($request->cabang == 'Samarinda'){
                        $filenameRPK = $request->viewdocrpk;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentrpk::where('cabang' , $request->cabang)
                        ->whereNotNull ($filenameRPK)
                        ->where($filenameRPK, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->pluck($filenameRPK)[0]; 
                        // dd($viewer);
                        return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                    }
                    if ($request->cabang == 'Jakarta'){
                        $filenameRPK = $request->viewdocrpk;
                        $kapal_id = $request->kapal_nama;
                        $result = $request->result;
                        $viewer = documentrpk::where('cabang' , $request->cabang)
                        ->whereNotNull ($filenameRPK)
                        ->where($filenameRPK, 'Like', '%' . $result . '%')
                        ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->pluck($filenameRPK)[0]; 
                        // dd($viewer);
                        return Storage::disk('s3')->response('jakarta/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                    }
                }
            // SearchBar ---------------------------------------------------------
                // babelan search bar
                //check if search-bar is filled or not
                if(Auth::user()->cabang == "Babelan"){
                    if (Auth::user()->cabang == "Babelan" and $request->filled('search_kapal')) {
                        //search for nama kapal in picsite dashboard page dan show sesuai yang mendekati
                        //pakai whereColumn untuk membandingkan antar 2 value column agar munculkan data dari pembuatan sampai bulan akhir periode
                        $document = documents::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
        
                        //get DocRPK Data as long as the periode_akhir and search based (column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)
                        ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
                        
                        return view('picsite.picDashboard', compact('document','docrpk'));
                    }else{
                        //get DocRPK Data as long as the periode_akhir(column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        $document = documents::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        return view('picsite.picDashboard', compact('document','docrpk'));
                    }
                }
                if(Auth::user()->cabang == "Berau"){
                    if (Auth::user()->cabang == "Berau" and $request->filled('search_kapal')) {
                        //berau search bar
                        $documentberau = documentberau::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
        
                        //get DocRPK Data as long as the periode_akhir(column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)
                        ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
                        return view('picsite.picDashboard', compact('documentberau','docrpk'));
                    }else{
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        $documentberau = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        return view('picsite.picDashboard', compact('documentberau','docrpk'));
                    }
                }
                if(Auth::user()->cabang == "Banjarmasin"){
                    if (Auth::user()->cabang == "Banjarmasin" and $request->filled('search_kapal')) {
                        //banjarmasin search bar
                        $documentbanjarmasin = documentbanjarmasin::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
        
                        //get DocRPK Data as long as the periode_akhir(column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)
                        ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
                        return view('picsite.picDashboard', compact('documentbanjarmasin','docrpk'));
                    }else{
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        $documentbanjarmasin = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        return view('picsite.picDashboard', compact('documentbanjarmasin','docrpk'));
                    }
                }
                if(Auth::user()->cabang == "Samarinda"){
                    if (Auth::user()->cabang == "Samarinda" and $request->filled('search_kapal')) {
                        //samarinda search bar
                        $documentsamarinda = documentsamarinda::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
        
                        //get DocRPK Data as long as the periode_akhir(column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)
                        ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
                        return view('picsite.picDashboard', compact('documentsamarinda','docrpk'));
                    }else{
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        $documentsamarinda = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        return view('picsite.picDashboard', compact('documentsamarinda', 'docrpk'));
                    }
                }
                if(Auth::user()->cabang == "Jakarta"){
                    if (Auth::user()->cabang == "Jakarta" and $request->filled('search_kapal')) {
                        $documentjakarta = documentJakarta::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
    
                        //get DocRPK Data as long as the periode_akhir and search based (column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)
                        ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                        ->whereColumn('created_at' , '<=', 'periode_akhir')
                        ->orderBy('id', 'DESC')
                        ->latest()->get();
                        
                        return view('picsite.picDashboard', compact('documentjakarta','docrpk'));
                    }else{
                        //get DocRPK Data as long as the periode_akhir(column database)
                        $docrpk = DB::table('rpkdocuments')->where('cabang', Auth::user()->cabang)->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        $documentjakarta = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                        return view('picsite.picDashboard', compact('documentjakarta','docrpk'));
                    }
                }
        }
        elseif(Auth::user()->hasRole('picAdmin')){
            $year = date('Y');
            $month = date('m');
            // Dana view ----------------------------------------------------------
            if($request->tipefile == 'DANA'){
                if ($request->cabang == 'Babelan'){
                    $filename = $request->viewdoc;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documents::whereColumn('created_at' , '<=', 'periode_akhir')
                    ->whereNotNull ($filename)
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->pluck($filename)[0];
                    // dd($viewer);
                    return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
                }
                if ($request->cabang == 'Berau'){
                    $filename = $request->viewdoc;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')
                    ->whereNotNull ($filename)
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->pluck($filename)[0];
                    // dd($viewer);
                    return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/" . $viewer);
                }
                if ($request->cabang == 'Banjarmasin'){
                    $filename = $request->viewdoc;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')
                    ->whereNotNull ($filename)
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->pluck($filename)[0];
                    // dd($viewer);
                    return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/" . $viewer);
                }
                if ($request->cabang == 'Samarinda'){
                    $filename = $request->viewdoc;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')
                    ->whereNotNull ($filename)
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->pluck($filename)[0];
                    // dd($viewer);
                    return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/" . $viewer);
                }
                if ($request->cabang == 'Jakarta'){
                    $filename = $request->viewdoc;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')
                    ->whereNotNull ($filename)
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->pluck($filename)[0];
                    // dd($viewer);
                    // dd($request);
                    return Storage::disk('s3')->response('jakarta/' . $year . "/". $month . "/" . $viewer);
                }
            }
             // RPK view ----------------------------------------------------------
             if($request->tipefile == 'RPK'){
                if ($request->cabang == 'Babelan'){
                    $filenameRPK = $request->viewdocrpk;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentrpk::where('cabang' , $request->cabang)
                    ->whereNotNull ($filenameRPK)
                    ->where($filenameRPK, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->whereColumn('created_at' , '<=', 'periode_akhir')
                    ->pluck($filenameRPK)[0];
                    // dd($viewer);
                    return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                }
                if ($request->cabang == 'Berau'){
                    $filenameRPK = $request->viewdocrpk;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentrpk::where('cabang' , $request->cabang)
                    ->whereNotNull ($filenameRPK)
                    ->where($filenameRPK, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->whereColumn('created_at' , '<=', 'periode_akhir')
                    ->pluck($filenameRPK)[0]; 
                    // dd($viewer);
                    return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                }
                if ($request->cabang == 'Banjarmasin'){
                    $filenameRPK = $request->viewdocrpk;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentrpk::where('cabang' , $request->cabang)
                    ->whereNotNull ($filenameRPK)
                    ->where($filenameRPK, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->whereColumn('created_at' , '<=', 'periode_akhir')
                    ->pluck($filenameRPK)[0]; 
                    // dd($viewer);
                    return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                }
                if ($request->cabang == 'Samarinda'){
                    $filenameRPK = $request->viewdocrpk;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentrpk::where('cabang' , $request->cabang)
                    ->whereNotNull ($filenameRPK)
                    ->where($filenameRPK, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->whereColumn('created_at' , '<=', 'periode_akhir')
                    ->pluck($filenameRPK)[0]; 
                    // dd($viewer);
                    return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                }
                if ($request->cabang == 'Jakarta'){
                    $filenameRPK = $request->viewdocrpk;
                    $kapal_id = $request->kapal_nama;
                    $result = $request->result;
                    $viewer = documentrpk::where('cabang' , $request->cabang)
                    ->whereNotNull ($filenameRPK)
                    ->where($filenameRPK, 'Like', '%' . $result . '%')
                    ->where('nama_kapal', 'Like', '%' . $kapal_id . '%')
                    ->whereColumn('created_at' , '<=', 'periode_akhir')
                    ->pluck($filenameRPK)[0]; 
                    // dd($viewer);
                    return Storage::disk('s3')->response('jakarta/' . $year . "/". $month . "/RPK" . "/" . $viewer);
                }
            }

            //search filter based on cabang on picadmin dashboard page
                $searchresult = $request->search;
                if ($searchresult == 'All') {
                    $docrpk = DB::table('rpkdocuments')->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $document = documents::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentberau = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentbanjarmasin = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentsamarinda = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentjakarta = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                }
                elseif ($request->filled('search')) {
                    $document = DB::table('documents')->where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentberau = DB::table('beraudb')->where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentbanjarmasin = DB::table('banjarmasindb')->where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentsamarinda = DB::table('samarindadb')->where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentjakarta = documentJakarta::where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $docrpk = DB::table('rpkdocuments')->where('cabang', request('search'))->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    return view('picadmin.picAdminDashboard', compact('docrpk','document', 'documentberau' , 'documentbanjarmasin' , 'documentsamarinda','documentjakarta'));
                }
                else{{
                    $docrpk = DB::table('rpkdocuments')->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $document = documents::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentberau = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentbanjarmasin = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentsamarinda = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                    $documentjakarta = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                }};

            //Search bar
            //check if search-bar is filled or not
            if ($request->filled('search_kapal')) {
                //search for nama kapal in picsite dashboard page dan show sesuai yang mendekati
                //pakai whereColumn untuk membandingkan antar 2 value column agar munculkan data dari pembuatan sampai bulan akhir periode
                $document = documents::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();

                //berau search bar
                $documentberau = documentberau::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();

                $documentbanjarmasin = documentbanjarmasin::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();

                $documentsamarinda = documentsamarinda::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();

                $documentjakarta = documentJakarta::where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();

                //get DocRPK Data as long as the periode_akhir(column database)
                $docrpk = DB::table('rpkdocuments')
                ->where('nama_kapal', 'Like', '%' . $request->search_kapal . '%')
                ->whereColumn('created_at' , '<=', 'periode_akhir')
                ->orderBy('id', 'DESC')
                ->latest()->get();
                
                return view('picadmin.picAdminDashboard', compact('docrpk','document', 'documentberau' , 'documentbanjarmasin' , 'documentsamarinda', 'documentjakarta'));
             }else{
                 //get DocRPK Data as long as the periode_akhir(column database)
                $docrpk = DB::table('rpkdocuments')->whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                $document = documents::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                $documentberau = documentberau::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                $documentbanjarmasin = documentbanjarmasin::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                $documentsamarinda = documentsamarinda::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                $documentjakarta = documentJakarta::whereColumn('created_at' , '<=', 'periode_akhir')->latest()->get();
                return view('picadmin.picAdminDashboard', compact('docrpk', 'document', 'documentberau' , 'documentbanjarmasin' , 'documentsamarinda' , 'documentjakarta'));
            }

        }
        elseif(Auth::user()->hasRole('AsuransiIncident')){
            $year = date('Y');
            $month = date('m');
            $uploadspgr = spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->latest()->get();
            
            //Search bar
            //check if search-bar is filled or not
            if ($request->filled('search_no_formclaim')) {
                $uploadspgr = spgrfile::where('no_formclaim', 'Like', '%' . $request->search_no_formclaim . '%')
                ->orderBy('id', 'DESC')
                ->latest()->get();
            }

            // view spgr
            if($request->tipefile == 'SPGR'){
                $year = date('Y');
                $month = date('m');

                $cabang = $request->cabang;
                $result = $request->result;
                $filename = $request->viewspgrfile;
                $claim = $request->no_claim;

                $viewer = spgrfile::where('cabang', 'Jakarta')
                ->whereNotNull ($filename)
                ->where('no_formclaim', 'Like', '%' . $claim . '%')
                ->where($filename, 'Like', '%' . $result . '%')
                ->pluck($filename)[0];
                // dd($request);
                // dd($viewer);
                return Storage::disk('s3')->response('spgr/' . $year . "/". $month . "/" . $viewer);
            }
            
            return view('picincident.dashboardincident', compact('uploadspgr'));
        }
        elseif(Auth::user()->hasRole('InsuranceManager')){
            $year = date('Y');
            $month = date('m');
            $uploadspgr = spgrfile::whereMonth('created_at', date('y'))->latest()->get();
            
            //Search bar
            //check if search-bar is filled or not
                if ($request->filled('search_no_formclaim')) {
                    $uploadspgr = spgrfile::where('no_formclaim', 'Like', '%' . $request->search_no_formclaim . '%')
                    ->orderBy('id', 'DESC')
                    ->latest()->get();
                }

            // view spgr
                if($request->tipefile == 'SPGR'){
                    $year = date('Y');
                    $month = date('m');

                    $cabang = $request->cabang;
                    $result = $request->result;
                    $filename = $request->viewspgrfile;
                    $claim = $request->no_claim;

                    $viewer = spgrfile::where('cabang', 'Jakarta')
                    ->whereNotNull ($filename)
                    ->where('no_formclaim', 'Like', '%' . $claim . '%')
                    ->where($filename, 'Like', '%' . $result . '%')
                    ->pluck($filename)[0];
                    // dd($request);
                    // dd($viewer);
                    return Storage::disk('s3')->response('spgr/' . $year . "/". $month . "/" . $viewer);
                }
            return view('insurance.Dashboardinsurance', compact('uploadspgr'));
        }
    }
    public function checkStock(){
        $items_below_stock = ItemBelowStock::join('items', 'items.id', '=', 'item_below_stocks.item_id')->where('cabang', Auth::user()->cabang)->get();

        return $items_below_stock;
    }
}
