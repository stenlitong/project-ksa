@if(Auth::user()->hasRole('crew'))

    @extends('../layouts.base')

    @section('title', 'Create Task')

    @section('container')
        <div class="row">
            @include('crew.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    <h1 class="mt-3 mb-5"  style="text-align: center">Create Task</h1>
                    
                    <div>
                        <form method="POST" action="">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Tug</label>
                                    <select class="form-control" name="tugName" id="tugName" style=" height:50px;" required>
                                        @foreach($tugs as $t)
                                            <option value="{{ $t -> tugName }}">{{ $t -> tugName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Barge (Optional)</label>
                                    <select class="form-control" name="bargeName" id="bargeName" style=" height:50px;" required>
                                            <option value="">None</option>
                                        @foreach($barges as $b)
                                            <option value="{{ $b -> bargeName }}">{{ $b -> bargeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jetty">Jetty</label>
                                    <input name="Jetty" type="number" min="1" class="form-control" id="Jetty" placeholder="Input Jetty dalam angka..."
                                        style=" height: 50px" required>
                                </div>
                            </div>
                            <div class="form-row d-flex justify-content-center mt-5">
                                <div class="form-group col-md-5">
                                    <label>Please choose type of tasks : </label>
                                    <select class="form-control" name="bargeName" id="bargeName" style=" height:50px;" required>
                                        <option value="" disabled>Choose Task...</option>
                                        <option value="Operational Shipment">Operational Shipment</option>
                                        <option value="Operational Transhipment">Operational Transhipment</option>
                                        <option value="Return Cargo">Return Cargo</option>
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
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif