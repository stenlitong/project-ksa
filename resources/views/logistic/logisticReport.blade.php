@extends('../layouts.base')

@section('title', 'Logistic Report')

@section('container')
    <div class="row">
        @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="margin-left: 40%">Goods Report</h1>
                <br>
                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif
                {{-- change route --}}
                <form method="POST" action="{{ Route("logistic.report") }}">
                    @csrf
                    {{-- row 1 --}}
                    <div class="d-flex justify-content-around ml-3 mr-3">
                            <div class="form-group p-2">
                                <label for="itemName" class="mt-3 mb-3">Item Name</label>
                                <input name="itemName" type="text" class="form-control" id="itemName" placeholder="Enter Nama Barang"
                                    style="width: 400px">
                            </div>
    
                            <div class="form-group p-2">
                                <label for="sender" class="mt-3 mb-3">Sender</label>
                                <input name="sender" type="text" class="form-control" id="sender" placeholder="Enter Nama Yang Menyerahkan"
                                    style="width: 400px">
                            </div>
                    </div>
    
                    {{-- row 2 --}}
                    <div class="d-flex justify-content-around ml-3 mr-3">
                        <div class="form-group p-2">
                            <label for="quantity" class="mt-3 mb-3">Quantity</label>
                            <input name="quantity" type="text" class="form-control" id="quantity" placeholder="Enter quantity"
                                style="width: 400px">
                        </div>
    
                        <div class="form-group p-2">
                            <label for="receiver" class="mt-3 mb-3">Receiver</label>
                            <input name="receiver" type="text" class="form-control" id="receiver" placeholder="Enter Nama Yang Menerima"
                                style="width: 400px">
                        </div> 
                    </div>
    
                    {{-- row 3 --}}
                    <div class="d-flex justify-content-around ml-3 mr-3">
                        <div class="form-group p-2">
                            <label for="serialNo" class="mt-3 mb-3">Serial No / Part No.</label>
                            <input name="serialNo" type="text" class="form-control" id="serialNo" placeholder="Enter Serial No / Part No."
                                style="width: 400px">
                        </div>
    
                        <div class="form-group p-2">
                            <label for="date" class="mt-3 mb-3">Tanggal Menerima</label>
                            <br>
                                <input name="date" type="date" class="form-control" id="date" name="date" style="width: 400px">
                        </div>
                    </div>
                    <div class="d-flex justify-content-around ml-3 mr-3">
                        <div class="form-group p-2">
                            <label for="location" class="mt-3 mb-3">Location</label>
                            <input name="location" type="text" class="form-control" id="location" placeholder="Enter location"
                                style="width: 400px">
                        </div>
                    </div>
                
    
                    <br>
    
                    <button type="submit" class="btn btn-primary" style="margin-left: 44%">Submit report</button>
                </form>
            </div>
        </main>
    </div>
@endsection