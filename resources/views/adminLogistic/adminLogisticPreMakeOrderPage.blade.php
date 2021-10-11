@if(Auth::user()->hasRole('adminLogistic'))
    @extends('../layouts.base')

    @section('title', 'Make Order Page')

    @section('container')
    <div class="row">
        @include('adminLogistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex-column container">
                <h1 class="mb-3" style="margin-top: 10%">Pilih Cabang</h1>

                @if (session('status'))
                    <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                        {{session('status')}}
                    </div>
                @endif

                <form action="/admin-logistic/create-order" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cabang">Cabang</label>
                        <select class="form-control" id="cabang" name="cabang">
                            <option selected disabled="">Choose...</option>
                            <option value="Jakarta" id="Jakarta">Jakarta</option>
                            <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                            <option value="Samarinda" id="Samarinda">Samarinda</option>
                            <option value="Bunati" id="Bunati">Bunati</option>
                            <option value="Babelan" id="Babelan">Babelan</option>
                            <option value="Berau" id="Berau">Berau</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </main>
    </div>
    @endsection

@endif