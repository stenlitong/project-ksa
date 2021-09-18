@extends('../layouts.base')

@section('title', 'Crew Task')

@section('container')
    <div class="row">
        @include('crew.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                <h1 style="margin-left: 40%">Create Task</h1>
                <br>
                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif

                <form method="POST" action="">
                    @csrf
                    {{-- kiri --}}
                    <div class="d-flex justify-content-around ml-3 mr-3">
                        <div class="form-group p-2">
                            <label for="TugName" class="mt-3 mb-3">Nama Tug</label>
                            <br>
                            <select class="form-control" name="TugName" id="TugName" style="width: 400px; height:50px;">
                                <option selected>Choose...</option>
                                <option value="atk-309">atk-309</option>
                                <option value="atk-310">atk-310</option>
                                <option value="atk-311">atk-311</option>
                            </select>
                            <div class="form-group p-2">
                                <label for="BargeName" class="mt-3 mb-3">Nama Barge</label>
                                <select class="form-control" name="BargeName" id="BargeName" style="width: 400px; height:50px;">
                                    <option selected>Choose...</option>
                                    <option value="atk-309">atk-309</option>
                                    <option value="atk-310">atk-310</option>
                                    <option value="atk-311">atk-311</option>
                                </select>
                            </div>
                        </div>
                        {{-- tengah --}}
                        <div class="form-group p-2">
                            <div class="form-group p-2">
                                <label for="fromdest" class="mt-3 mb-3">From</label>
                                <br>
                                <select class="form-control" id="fromdest" name="fromdest" style="width: 200px; height:50px;">
                                    <option selected>Choose depart from...</option>
                                    <option value="Samarinda">Samarinda</option>
                                    <option value="Berau">Berau</option>
                                    <option value="Morosi">Morosi</option>
                                    <option value="Banjarmasin">Banjarmasin</option>
                                    <option value="Bunati">Bunati</option>
                                    <option value="Babelan">Babelan</option>
                                </select>
                                <div class="form-group p-2">
                                    <label for="todest" class="mt-3 mb-3">To</label>
                                    <select class="form-control" id="todest" name="todest" style="width: 200px; height:50px;">
                                        <option selected>Choose Destination...</option>
                                        <option value="Samarinda">Samarinda</option>
                                        <option value="Berau">Berau</option>
                                        <option value="Morosi">Morosi</option>
                                        <option value="Banjarmasin">Banjarmasin</option>
                                        <option value="Bunati">Bunati</option>
                                        <option value="Babelan">Babelan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- kanan --}}
                        <div class="form-group p-2">
                                <div class="form-group p-2">
                                    <label for="Cquantity" class="mt-3 mb-3">Cargo Quantity</label>
                                    <input name="quantity" type="text" class="form-control" id="Cquantity" placeholder="Enter cargo quantity"
                                        style="width: 200px">
                                </div>
                            
                                <div class="form-group p-2">
                                    <label for="jetty" class="mt-3 mb-3">Jetty</label>
                                    <input name="quantity" type="text" class="form-control" id="jetty" placeholder="Enter jetty"
                                        style="width: 200px">
                                </div>
                        </div>
                        <div class="form-group p-2">
                            <label for="Status" class="mt-3 mb-3">Status</label>
                            <br>
                            <select class="form-control" id="Status" name="Status" style="width: 200px; height:50px; ">
                                <option selected>Choose Status...</option>
                                <option value="Operational">Operational</option>
                                <option value="Standby">Standby</option>
                            </select>
                        </div>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary" style="margin-left: 44%">Submit Order</button>
                </form>
            </div>
        </main>
        
    </div>
@endsection
