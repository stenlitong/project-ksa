@extends('../layouts.base')

@section('title', 'Admin Purchasing Dashboard')

@section('container')
    <div class="row">
        @include('adminPurchasing.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('../layouts/time')

            <div class="row">
                <div class="col mt-3">
                    <div class="jumbotron bg-light jumbotron-fluid" style="border-radius: 25px;">
                        <div class="container">
                            <form method="POST" action="">
                                @csrf
                                <h1 class="mb-3" style="text-align: center">Please upload your form LIST AP</h1>
                                <img class="w-25 h-25" style="margin-left: 37%;" data-feather="upload">
                                <div class="custom-file w-50 mt-3" style="margin-left: 25%;">
                                    <input type="file" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <p class="mt-3" style="text-align: center">Format: zip/pdf</p>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary mb-2 btn-lg">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    {{-- <input id="input-b1" name="input-b1" type="file" class="file" data-browse-on-zone-click="true"> --}}
                </div>
            </div>

        </main>
    </div>
@endsection