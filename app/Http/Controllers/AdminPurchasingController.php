<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\ApList;
use App\Models\OrderHead;
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
            'supplierNPWP' => 'required',
            'supplierNoRek' => 'nullable'
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

    public function deleteSupplier(Request $request, Supplier $suppliers){
        // Find the supplier by the id in the request params
        Supplier::destroy($suppliers->id);

        return redirect('/dashboard')->with('status', 'Deleted Successfully');
    }

    public function formApPage(){
        // Show the form AP page
        $apList = ApList::with('orderHead')->where('cabang', Auth::user()->cabang)->whereYear('created_at', date('Y'))->latest()->paginate(7);
        
        // Get all the supplier
        $suppliers = Supplier::latest()->get();

        // Default branch is Jakarta
        $default_branch = 'Jakarta';

        return view('adminPurchasing.adminPurchasingFormAp', compact('apList', 'default_branch', 'suppliers'));
    }

    public function formApPageBranch($branch){
        // Show the form AP page
        $apList = ApList::with('orderHead')->where('cabang', $branch)->whereYear('created_at', date('Y'))->latest()->paginate(7);
        
        // Get all the supplier
        $suppliers = Supplier::latest()->get();

        // Get the branch from the parameter
        $default_branch = $branch;

        return view('adminPurchasing.adminPurchasingFormAp', compact('apList', 'default_branch', 'suppliers'));
    }

    public function uploadFile(Request $request, $cabang){
        // Validate the file extension
        $request->validate([
            'doc_partial1' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial2' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial3' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial4' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial5' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',

            'doc_partial6' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial7' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial8' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial9' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial10' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            
            'doc_partial11' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial12' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial13' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial14' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial15' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',

            'doc_partial16' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial17' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial18' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial19' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial20' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
        ]);

        // Helper var
        $filename = 'doc_partial';

        // Loop through all the inputs and check if they submit the file or not, if they submit a file then input into the database
        for($i = 1 ; $i <=20 ; $i++){
            if($request -> hasFile($filename . $i)){

                // Helper var
                $dynamic_file = $filename . $i;
                $dynamic_status = 'status_partial' . $i;
                $dynamic_uploadTime = 'uploadTime_partial' . $i;

                // Check if file already exists
                $curr_file = ApList::find($request -> apListId)->pluck($dynamic_file)[0];

                // Then delete the file first, so it does not takes memory
                if($curr_file && Storage::exists('APList/' . $curr_file)){
                    unlink(storage_path('app/APList/' . $curr_file));
                }

                // Get the path for the file
                $path = $request -> $dynamic_file -> getClientOriginalName();

                // Save all additional information to the database
                ApList::find($request -> apListId)->update([
                    $dynamic_file => $path,
                    $dynamic_status => 'On Review',
                    $dynamic_uploadTime => date("d/m/Y")
                ]);

                // Store the file into storage folder, so it does not publicly accessible || the alternative way is store the files on public folder, but it is easier to access
                $request -> file($dynamic_file) -> storeAs('APList', $request -> $dynamic_file -> getClientOriginalName());
            }
        };
        
        return redirect('/admin-purchasing/form-ap')->with('status', 'Uploaded Successfully');
    }

    public function downloadFile(ApList $apList){
        // Find the file then download
        return Storage::download('/APList' . '/' . $apList->filename);
    }
}
