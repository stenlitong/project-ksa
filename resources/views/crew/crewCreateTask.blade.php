@if(Auth::user()->hasRole('crew'))

    @extends('../layouts.base')

    @section('title', 'Create Task')

    @section('container')
        <div class="row">
            @include('crew.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h1 class="mt-3 mb-5" style="text-align: center">Create Task</h1>
                    
                    @if(count($errors) > 0)
                        @foreach($errors->all() as $message)
                            <div class="alert alert-danger text-center" style="width: 40%; margin-left: 30%">
                                {{ $message }}
                            </div>
                        @endforeach
                    @endif

                    @if(session('failed'))
                        <div class="alert alert-danger text-center" style="width: 40%; margin-left: 30%">
                            {{ session('failed') }}
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success text-center" style="width: 40%; margin-left: 30%">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <form method="POST" action="/crew/create-task">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Tug</label>
                                    <select class="form-control" name="tug_id" id="tug_id" style=" height:50px;" required>
                                        @foreach($tugs as $t)
                                            <option value="{{ $t -> id }}">{{ $t -> tugName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Barge (Optional)</label>
                                    <select class="form-control" name="barge_id" id="barge_id" style=" height:50px;" required>
                                        @foreach($barges as $b)
                                            <option value="{{ $b -> id }}">{{ $b -> bargeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jetty">Jetty</label>
                                    <input name="jetty" type="text" class="form-control" id="jetty" maxlength="50" pattern="[A-Za-z]{2,}" placeholder="Input Jetty (min. 2 alpha)..."
                                        style=" height: 50px" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cargoAmountStart">Jumlah Kargo Awal</label>
                                    <div class="input-group">
                                        <input name="cargoAmountStart" type="number" min="1" class="form-control" id="cargoAmountStart" step="0.001" placeholder="Input Jumlah Kargo Awal Dalam Ton..."
                                        style=" height: 50px" required>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text" style="height: 50px">Ton</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Please choose type of tasks : </label>
                                    <select class="form-control" name="taskType" id="taskType" style=" height:50px;" required>
                                        <option value="" disabled>Choose Task...</option>
                                        <option value="Operational Shipment">Operational Shipment</option>
                                        <option value="Operational Transhipment">Operational Transhipment</option>
                                        {{-- <option value="Return Cargo">Return Cargo</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="mt-5 btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
            
        </div>

        <script type="text/javascript">
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000); 
        </script>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif