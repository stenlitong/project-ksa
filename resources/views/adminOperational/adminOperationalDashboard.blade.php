@if(Auth::user()->hasRole('adminOperational'))
    @extends('../layouts.base')

    @section('title', 'Admin Operational Dashboard')

    @section('container')
    <div class="row">
        @include('adminOperational.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('../layouts/time')            

            <h1 class="mt-3 mb-3" style="text-align: center">Dashboard</h1>

            <div class="d-flex justify-content-center smaller-size" id="content">
                <div class="d-flex flex-wrap" style="width: 50%; overflow-y: auto; max-height: 600px">
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front color-green">
                            <h5 class="text-white mt-3 display-2">{{ $total_fleets }}</h5>
                            <h5 class="text-white mt-3">Total Fleets</h5>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <h5 class="text-white mt-3 display-2">{{ $on_sailing_count }}</h5>
                            <h5 class="text-white mt-3">On Sailing</h5>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <h5 class="text-white mt-3 display-2">{{ $loading_activity_count }}</h5>
                            <h5 class="text-white mt-3">Loading Activity</h5>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <h1 class="text-white mt-3 display-2">{{ $discharge_activity_count }}</h1>
                            <h5 class="text-white mt-3">Discharge Activity</h5>
                          </div>
                        </div>
                    </div>
                    <div class="d-flex">
                      <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <h1 class="text-white mt-3 display-2">{{ $standby_count }}</h1>
                            <h5 class="text-white mt-3">Standby</h5>
                          </div>
                        </div>
                      </div>
                      <div class="flip-card">
                          <div class="flip-card-inner">
                            <div class="flip-card-front">
                              <h1 class="text-white mt-3 display-2">{{ $repair_count }}</h1>
                              <h5 class="text-white mt-3">Repair</h5>
                            </div>
                          </div>
                      </div>
                      <div class="flip-card">
                          <div class="flip-card-inner">
                            <div class="flip-card-front">
                              <h1 class="text-white mt-3 display-2">{{ $docking_count }}</h1>
                              <h5 class="text-white mt-3">Docking</h5>
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="d-flex">
                      <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <h1 class="text-white mt-3 display-2">{{ $standby_docking_count }}</h1>
                            <h5 class="text-white mt-3">Standby Docking</h5>
                          </div>
                        </div>
                      </div>
                      <div class="flip-card">
                          <div class="flip-card-inner">
                            <div class="flip-card-front">
                              <h1 class="text-white mt-3 display-2">{{ $grounded_barge_count }}</h1>
                              <h5 class="text-white mt-3">Grounded Barge</h5>
                            </div>
                          </div>
                      </div>
                      <div class="flip-card">
                          <div class="flip-card-inner">
                            <div class="flip-card-front">
                              <h1 class="text-white mt-3 display-2">{{ $waiting_schedule_count }}</h1>
                              <h5 class="text-white mt-3">Waiting Schedule</h5>
                            </div>
                          </div>
                      </div>
                    </div>
                </div>
                <div class="right-section mt-3" style="width: 40%">
                    <div class="jumbotron jumbotron-fluid mx-2">
                        <div class="container">
                          <h1 class="display-4 text-wrap font-weight-bold text-center">Percentage Ship's Activity : </h1>
                          <div class="progress mb-3 mt-3" style="height: 5vh; max-width: 85%; margin-left: 8%">
                              <div class="progress-bar progress-bar-animated progress-bar-striped bg-success font-weight-bold" role="progressbar" style="width: {{ $percentage_ship_activity }}%;" aria-valuenow="{{ $percentage_ship_activity }}" aria-valuemin="0" aria-valuemax="100">
                                <h3 class="font-weight-bold">
                                  {{ $percentage_ship_activity }}%
                                </h3>
                              </div>
                          </div>
                          <h1 class="display-4 text-wrap font-weight-bold text-center mt-5">Total Lost Time : </h1>
                          <h2 class="display-3 text-wrap text-center text-secondary font-weight-bold">{{ $total_lost_time }} Days</h2>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <style>
        th{
            color: white;
        }
        td, th{
            word-wrap: break-word;
            min-width: 120px;
            max-width: 120px;
            text-align: center;
        }
        .alert{
            text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
        .feather-100{
            margin-top: 10px;
            width: 100px;
            height: 100px;
        }
        .jumbotron{
            background-color: #A4363A;
            color: white;
            border: 8px solid black;
            border-radius: 10px;
            height: 100%;
        }
        /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
        .flip-card {
            margin: 20px;
            background-color: transparent;
            width: 220px;
            height: 220px;
            perspective: 1000px;
        }

        /* This container is needed to position the front and back side */
        .flip-card-inner {
            border-radius: 50%;
            border: 8px solid black;
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        /* Position the front and back side */
        .flip-card-front{
          position: absolute;
          border-radius: 50%;
          width: 100%;
          height: 100%;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
        }

        .flip-card-front {
          background-color: #A01D23;      
        }

        .color-green {
          background-color: #86c91a;      
        }

        .text-white mt-3 display-2{
          font-size: 64px;
        }
        @media only screen and (max-width: 960px) {
            .smaller-size {
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .right-section{
                margin: 20px;
                margin-left: -30%;
            }
            .jumbotron{
                margin: 10px;
                height: 68vh;
                width: 84vw;
            }
        }
    </style>

    <script type="text/javascript">
        function refreshDiv(){
            $('#content').load(location.href + ' #content')
        }
        setInterval(refreshDiv, 60000);

        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
    </script>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif