@if(Auth::user()->hasRole('crew'))

    @extends('../layouts.base')

    @section('title', 'Create Task')

    @section('container')
        <div class="row">
            @include('crew.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 mt-3">
                    
                    <form action="" method="post">
                        @csrf

                        <div class="form-row">
                            <div class="card border-dark mb-3" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">F/A Jetty</h5>
                                    <h6 class="card-subtitle mb-2 text-muted text-center text-center">Start Date & Time</h6>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="card border-dark mb-3" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">F/A Jetty</h5>
                                    <h6 class="card-subtitle mb-2 text-muted text-center text-center">Start Date & Time</h6>
                                    
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </main>
            
        </div>
    @endsection
@else
    @include('../layouts/notAuthorized')
@endif