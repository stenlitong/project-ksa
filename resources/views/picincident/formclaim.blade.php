@extends('../layouts.base')

@section('title', 'PicIncident-Upload-Form')

@section('container')
<x-guest-layout>
    <div class="row">
        @include('picincident.sidebarincident')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="row">
                <div class="col" style="margin-top: 5%">
                    <div class="jumbotron jumbotron-fluid" >
                        <div class="container">
                        <h4 class="display-4" style="margin-top: -3%; margin-left: 30%" >Create Form Claim Insurance</h4>
                            {{-- <p class="lead">please only upload file size max 1MB with .PDF format only .
                            <br>
                                Please upload your RPK Document !
                            </p> --}}
                            <form method="POST" action="/picincident/formclaim/submitform">
                                @csrf
                                <div class="form-row">
                                    <div class="col-lg-3">
                                        <x-label for="name" :value="__('Name : ')" style="margin-top: 4%; margin-left: 1%" />
                                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" placeholder="Enter Name" :value="old('name')" required autofocus />

                                        <x-label for="dateincident" :value="__('Tgl Incident : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="dateincident" class="block mt-1 w-full" type="date" name="dateincident"  required autofocus />

                                        <x-label for="dateclaim" :value="__('Tgl.Form Claim : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="dateclaim" class="block mt-1 w-full" type="date" name="dateclaim"  required autofocus />

                                        <x-label for="jenisincident" :value="__('Jenis Incident : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <select name="jenisincident" id="jenisincident" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autofocus>
                                            <option selected disabled value="">Please Choose...</option>
                                            <option value="TP" id="picAdmin">TP</option>
                                            <option value="HM" id="picIncident">HM</option>
                                        </select>

                                        <x-label for="Item_name" :value="__('Item : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="Item_name" class="block mt-1 w-full" type="text" name="Item_name" placeholder="Enter Item Name" :value="old('Item_name')" required autofocus />
                                
                                    </div>
                                    <div class="col-lg-3">
                                        <x-label for="FormClaim" :value="__('No. FormClaim : ')" style="margin-top: 4%; margin-left: 1%"  />
                                        <x-input id="FormClaim" class="block mt-1 w-full" type="text" name="FormClaim" placeholder="Enter No. FormClaim" :value="old('FormClaim')" required autofocus />

                                        <x-label for="TOW" :value="__('TOW : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="TOW" class="block mt-1 w-full" type="number" name="TOW" placeholder="Enter TOW" :value="old('TOW')" required autofocus />
                                        
                                        <x-label for="TotalSumInsurade" :value="__('Total Sum Insurade : ')" style="margin-top: 2%; margin-left: 1%"  />
                                        <x-input id="TotalSumInsurade" class="block mt-1 w-full" type="number" name="TotalSumInsurade" placeholder="Enter Total Sum Insurade" :value="old('TotalSumInsurade')" required autofocus />
                                        
                                        <x-label for="Deductible" :value="__('Deductible : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="Deductible" class="block mt-1 w-full" type="number" name="Deductible" placeholder="Enter Deductible" :value="old('Deductible')" required autofocus />

                                        <x-label for="Amount" :value="__('Amount : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="Amount" class="block mt-1 w-full" type="number" name="Amount" placeholder="Enter Amount" :value="old('Amount')" required autofocus />
                                        
                                    </div>
                                    <div class="col-lg-3">
                                        <x-label for="Surveyor" :value="__('Surveyor : ')" style="margin-top: 4%; margin-left: 1%" />
                                        <x-input id="Surveyor" class="block mt-1 w-full" type="text" name="Surveyor" placeholder="Enter Surveyor's Name" :value="old('Surveyor')" required autofocus />

                                        <x-label for="TugBoat" :value="__('TugBoat : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="TugBoat" class="block mt-1 w-full" type="text" name="TugBoat" placeholder="Enter TugBoat Name" :value="old('TugBoat')" required autofocus />

                                        <x-label for="Incident" :value="__('Incident : ')" style="margin-top: 2%; margin-left: 1%" />
                                        <x-input id="Incident" class="block mt-1 w-full" type="text" name="Incident" placeholder="Enter Incident" :value="old('Incident')" required autofocus />
                                        
                                    </div>
                                </div>

                                
                                <div>
                                    <x-label for="Description" :value="__('Description : ')" style="margin-top: 2%; margin-left: 1%;"  />
                                    <textarea class="form-control" name="reasonbox" required id="Description" required autofocus rows="3"></textarea>
                                    <button class="btn btn-dark" type="submit" id="addcart" style="margin-left: 80%; margin-top: 2%; width: 20%;">Add To List</button>
                                    <button class="btn btn-outline-danger" id="createform" name="createform" style="margin-left: 80%; margin-top: 1%; width: 20%;">Create Form</button>
                                </div>

                            </form>

                            @error('reasonbox')
                            <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
                                Alasan Wajib Diisi
                            </div>
                            @enderror
                            
                            @if ($success = Session::get('message'))
                                <div class="alert alert-success alert-block" id="message">
                                    <strong>{{ $success }}</strong>
                                </div>
                            @endif
{{-- show form table --}}
                            @forelse($claims as $claim )
                            <table class="table" style="margin-top: 2%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Jenis Incident</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Deductible</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td scope="col">{{$loop->index+1}}</td>
                                            <td scope="col">{{$claim->jenis_incident}}</td>
                                            <td scope="col">{{$claim->item}}</td>
                                            <td scope="col">{{$claim->deductible}}</td>
                                            <td scope="col">{{$claim->description}}</td>
                                            <td scope="col">{{$claim->amount}}</td>
                                            <td scope="col">
                                                <form action="/picincident/formclaim/destroy/{{$claim->id}}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" id="Deletepost" onClick="return confirm('Are you sure?')" class="btn btn-danger">Delete</button>
                                                    <button type="submit" id="realsub" onClick="return confirm('Are you sure?')" style="display: none" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr> forms request not yet added </tr>
                                        @endforelse
                                </tbody>
                            </table>
                                <script>
                                    document.getElementById('createform').addEventListener('click', openDialog);
                                    function openDialog() {
                                        document.getElementById('realsub').click();
                                    }
                                </script>
                                <script>
                                    setTimeout(function(){
                                    $("div.alert").remove();
                                    }, 5000 ); // 5 secs
                                </script>
                                <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </main>
</div>
</x-guest-layout>
@endsection