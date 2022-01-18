@if(Auth::user()->hasRole('adminOperational'))
    @extends('../layouts.base')

    @section('title', 'Add Tugboat')

    @section('container')
    <div class="row">
        @include('adminOperational.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="mt-5 mb-3 text-center">Tugboat List</h1>

            @if(session('status'))
                <div class="alert alert-success" style="width: 40%; margin-left: 30%">
                    {{ session('status') }}
                </div>
            @endif

            @error('tugName')
                <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                    Nama Tugboat Invalid
                </div>
            @enderror

            <div class="d-flex justify-content-around mb-3">
                <div class="input-group w-25">
                    @csrf
                    <input type="text" class="form-control" placeholder="Search Tugboat by name..." name="search" id="search" value="{{ old('search') }}">
                    <button class="btn btn-primary searchBtn" type="button" onclick="search()">Search</button>
                </div>
                <div class="mr-5">
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addTugboat" >Add</button>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="spinner-border spinner-border-lg text-danger" role="status" id="wait">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="w-75" id="table_data">
                    @csrf
                    @include('adminOperational.adminOperationalAddTugboatTable')
                </div>
            </div>
        </main>

        <!-- Modal #1 -->
        <div class="modal fade" id="addTugboat" tabindex="-1" role="dialog" aria-labelledby="addTugboat"
            aria-hidden="true" data-backdrop="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="addTugboatTitle" style="color: white">Add New Tugboat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/admin-operational/add-newtugboat">
                            @csrf
                            <div class="form-group">
                                <label for="tugName">Nama Tugboat</label>
                                <input type="text" class="form-control" id="tugName" name="tugName"
                                    placeholder="Input Nama Tugboat" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
            align-items: center;
        }
        .alert{
            text-align: center;
        }
        .modal-backdrop {
            height: 100%;
            width: 100%;
        }
    </style>

    <script>
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 3000); 
    </script>
    
    {{-- <script type="text/javascript">
        $(document).ready(function(){
        
            $(document).on('click', '.pagination a', function(event){
                
                event.preventDefault(); 
                console.log('clicked')
                // var page = $(this).attr('href').split('page=')[1];
                // fetch_data(page);
            });
            
            function fetch_data(page)
            {
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url:"{{ route('adminOperational.paginationTugboat') }}",
                    method:"PATCH",
                    data:{_token, page},
                    beforeSend: function(){
                    $('#table_data').hide();
                        spinner.style.visibility = 'visible';
                    },
                    success: function(data){
                        $('#table_data').html(data);
                        $('#table_data').show();
                        spinner.style.visibility = 'hidden';
                    }
                });
            }
        });
    </script> --}}

    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '.page-link', function(event){
                event.preventDefault();
                console.log('clicked');
                let page = $(this).attr('href').split('page=')[1];
                
                let _token = $('input[name=_token]').val();

                $.ajax({
                    url: "{{ route('adminOperational.paginationTugboat') }}",
                    method: "POST",
                    data: {
                        _token,
                        page
                    },
                    beforeSend: function(){
                    $('#table_data').hide();
                    spinner.style.visibility = 'visible';
                    },
                    success: function(data){
                        $('#table_data').html(data);
                        $('#table_data').show();
                        spinner.style.visibility = 'hidden';
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        let spinner = document.getElementById("wait");
        spinner.style.visibility = 'hidden';

        function search(){
            event.preventDefault();
            let searchData = document.getElementById('search').value;

            let _token = $('input[name=_token]').val();
            console.log(searchData);
            $.ajax({
                url: "{{ route('adminOperational.searchTugboat') }}",
                method: "POST",
                data: {
                    _token,
                    searchData
                },
                beforeSend: function(){
                    $('#table_data').hide();
                    spinner.style.visibility = 'visible';
                },
                success: function(data){
                    $('#table_data').html(data);
                    $('#table_data').show();
                    spinner.style.visibility = 'hidden';
                }
            })
        }
    </script>

    @endsection
@else
    @include('../layouts/notAuthorized')
@endif