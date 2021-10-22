<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\ApList;
use Illuminate\Support\Facades\Auth;
use Storage;

class AdminPurchasingController extends Controller
{
    public function addSupplier(Request $request){
        // Add supplier
        $validated = $request -> validate([
            'supplierName' => 'required',
            'noTelp' => 'required|numeric|digits_between:8,11',
            'supplierEmail' => 'required|email|unique:suppliers',
            'supplierAddress' => 'required',
            'supplierNPWP' => 'required'
        ]);
        
        Supplier::create($validated);

        return redirect('/dashboard')->with('status', 'Added Successfully');
    }

    public function editSupplier(Request $request, Supplier $suppliers){
        // Edit supplier
        $validated = $request -> validate([
            'supplierName' => 'required',
            'noTelp' => 'required|numeric|digits_between:8,11',
            'supplierEmail' => 'required|email',
            'supplierAddress' => 'required',
            'supplierNPWP' => 'required'
        ]);

        // Find the supplier's ID then update the value
        Supplier::find($suppliers->id)->update($validated);

        return redirect('/dashboard')->with('status', 'Edited Successfully');
    }

    public function formApPage(){
        // Show the form AP page
        $documents = ApList::where('cabang', Auth::user()->cabang)->latest()->get();

        return view('adminPurchasing.adminPurchasingFormAp', compact('documents'));
    }

    public function uploadFile(Request $request){
        // if(!empty( $request->except('_token'))){
            // if(count($request->file()) == 0){
                // dd('kosong');
            // }
        // }
        
        // dd(count($request->file()));
        // dd($request);

        // Validate the file extension must be pdf or zip
        $request->validate([
            'filename' => 'required|mimes:pdf,zip'
        ]);
        
        $path = $request->filename->getClientOriginalName();

        // Then create the AP List on the database
        ApList::create([
            'user_id' => Auth::user()->id,
            'cabang' => Auth::user()->cabang,
            'filename' => $path,
            'status' => 'On Review',
            'tracker' => 5,
            'submissionTime' => date("d/m/Y")
        ]);
        
        // Store it in storage folder, so it does not publicly accessible || the alternative way is store the files on public folder, but it is easier to access
        $request->file('filename')->storeAs('APList', $request->filename->getClientOriginalName());

        return redirect('/admin-purchasing/form-ap')->with('status', 'Uploaded Successfully');
    }

    public function downloadFile(ApList $apList){
        // Find the file then download
        return Storage::download('/APList' . '/'. 'folderBaru' .'/' . $apList->filename);
    }
}
