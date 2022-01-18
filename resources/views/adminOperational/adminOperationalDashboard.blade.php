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
                <div class="d-flex flex-wrap" style="width: 60%; overflow-y: auto; max-height: 600px">
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            {{-- <img src="/images/tool.svg" class="mt-3" alt=""> --}}
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">DOK</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $dok_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Perbaikan</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $perbaikan_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Kandas</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $kandas_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Tunggu DOK</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $tungguDOK_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Tunggu Tug Boat</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $tungguTug_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Tunggu Dokumen</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $tungguDokumen_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Standby DOK</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $standbyDOK_days }}</h1>
                          </div>
                        </div>
                    </div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                          <div class="flip-card-front">
                            <img src="/images/tool.svg" class="mt-3" alt="">
                            <h5 class="text-white mt-3">Bocor</h5>
                          </div>
                          <div class="flip-card-back d-flex flex-column align-items-center justify-content-center">
                            <h1 class="text-card-back">{{ $bocor_days }}</h1>
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
            border: 1px solid black;
            border-radius: 10px;
            height: 100%;
        }
        /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
        .flip-card {
            border-radius: 10px;
            margin: 20px;
            background-color: transparent;
            width: 300px;
            height: 200px;
            perspective: 1000px; /* Remove this if you don't want the 3D effect */
        }

        /* This container is needed to position the front and back side */
        .flip-card-inner {
            border-radius: 10px;
            border: 1px solid black;
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        /* Do an horizontal flip when you move the mouse over the flip box container */
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        /* Position the front and back side */
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; /* Safari */
            backface-visibility: hidden;
        }

        /* Style the front side (fallback if image is missing) */
        .flip-card-front {
            background-color: #A01D23;            
        }

        /* Style the back side */
        .flip-card-back {
            border-radius: 10px;
            background-color: beige;
            color: black;
            transform: rotateY(180deg);
        }
        .text-card-back{
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