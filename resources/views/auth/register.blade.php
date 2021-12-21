<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous">
    </script>
</head>
<x-guest-layout>
    <x-auth-card >
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required placeholder="Input Nama..."
                    autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Input Email..."
                    required />
            </div>

            <!-- No Telp -->
            <div class="mt-4">
                <x-label for="user_noTelp" :value="__('Nomor Telepon')" />
                <div class="input-group mt-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="height: 45px">(+62)</div>
                    </div>
                    <input type="text" class="form-control" id="user_noTelp" name="user_noTelp"
                        style="border-radius: 8px; border-color: rgb(196, 194, 194);"
                        placeholder="Input nomor telepon dalam angka..."
                        value={{ old('user_noTelp') }}>
                </div>
            </div>

            <br>
            <!-- No Induk Pegawai -->
            <div>
                <x-label for="no_induk_pegawai" :value="__('No Induk Pegawai')" />

                <x-input id="no_induk_pegawai" class="block mt-1 w-full" type="text" name="no_induk_pegawai" 
                    :value="old('no_induk_pegawai')" required autofocus placeholder="Input Nomor Induk Pegawai..."/>
            </div>

            <br>

            <!-- No Telp -->
            <div class="mt-4">
                <x-label for="user_noTelp" :value="__('Nomor Telepon')" />
                <div class="input-group mt-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="height: 45px">(+62)
                        <input type="text" class="form-control" id="user_noTelp" name="user_noTelp"
                            style="border-radius: 8px; border-color: rgb(196, 194, 194);"
                            placeholder="Input nomor telepon dalam angka..."
                            value={{ old('user_noTelp') }}>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <!-- Select Option role -->
            <div>
                <x-label for="role_id" :value="__('Register as:')" />

                <select name="role_id" id="role_id"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required>
                    <option selected disabled value="">Choose...</option>
                    <option value="picSite" id="picSite" disabled @if (old('role_id') == 'picSite') selected="selected" @endif>PIC Site</option>
                    <option value="picIncident" id="picIncident" disabled @if (old('role_id') == 'picIncident') selected="selected" @endif>PIC Incident</option>
                    <option value="insurance" id="insurance" disabled @if (old('role_id') == 'insurance') selected="selected" @endif>Asuransi</option>
                    <option value="purchasing"  @if (old('role_id') == 'purchasing') selected="selected" @endif>Purchasing</option>
                    <option value="logistic"  @if (old('role_id') == 'logistic') selected="selected" @endif>Logistic</option>
                    <option value="supervisorLogistic"  @if (old('role_id') == 'supervisorLogistic') selected="selected" @endif>Supervisor Logistic</option>
                    <option value="crew"  @if (old('role_id') == 'crew') selected="selected" @endif>Crew</option>
                </select>
            </div>
            <br>

            <!-- Select Option Cabang -->
            <div>
                <x-label for="cabang" :value="__('Cabang:')" />

                <select name="cabang" id="cabang"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required>
                    <option selected disabled="">Choose...</option>
                    <option value="Jakarta" id="Jakarta" @if (old('cabang') == 'Jakarta') selected="selected" @endif>Jakarta</option>
                    <option value="Banjarmasin" id="Banjarmasin" @if (old('cabang') == 'Banjarmasin') selected="selected" @endif>Banjarmasin</option>
                    <option value="Samarinda" id="Samarinda" @if (old('cabang') == 'Samarinda') selected="selected" @endif>Samarinda</option>
                    <option value="Bunati" id="Bunati" @if (old('cabang') == 'Bunati') selected="selected" @endif>Bunati</option>
                    <option value="Babelan" id="Babelan" @if (old('cabang') == 'Babelan') selected="selected" @endif>Babelan</option>
                    <option value="Berau" id="Berau" @if (old('cabang') == 'Berau') selected="selected" @endif>Berau</option>
                    <option value="Kendari" id="Kendari" @if (old('cabang') == 'Kendari') selected="selected" @endif>Kendari</option>
                </select>
            </div>
            
            <script>
                 
                function myFunction() {
                    var x = document.getElementById("password");
                    var y = document.getElementById("password_confirmation");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                    if (y.type === "password") {
                        y.type = "text";
                    } else {
                        y.type = "password";
                    }
                }
                
                function selectopt(id)
                {
                    var e = document.getElementById("cabang");
                    e.selectedIndex=e.querySelector('option[value="'+id+'"]').index;
                }
                var dropdown = document.getElementById("role_id");
                dropdown.onchange = function(event){
                    
                    // if(dropdown.value=="picAdmin"){
                    //     selectopt('jakarta');
                    //     document.getElementById("samarinda").disabled = true;
                    //     document.getElementById("Banjarmasin").disabled = true;
                    //     document.getElementById("bunati").disabled = true; 
                    // }else{
                    //     document.getElementById("samarinda").disabled = false;
                    //     document.getElementById("Banjarmasin").disabled = false;
                    //     document.getElementById("bunati").disabled = false;   
                    // }
                    
                    if (dropdown.value=="picSite") {
                        document.getElementById("jakarta").disabled = true;
                        selectopt('samarinda');
    
                    } else {
                            document.getElementById("jakarta").disabled = false;
                        }
                }
            </script>
{{-- validation script --}}

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required />
            </div>

            <br>
            <div class="ml-1">
                <input type="checkbox" onclick="myFunction()" style="border-radius: 30%">
                <label for="">Show Password</label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4" href="{{ route('login') }}">
                    Register
                </x-button>
            </div>
            {{-- validation script --}}
            <script>
                function myFunction() {
                    var x = document.getElementById("password");
                    var y = document.getElementById("password_confirmation");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                    if (y.type === "password") {
                        y.type = "text";
                    } else {
                        y.type = "password";
                    }
                }
                function selectopt(id) {
                    var e = document.getElementById("cabang");
                    e.selectedIndex = e.querySelector('option[value="' + id + '"]').index;
                }
                var dropdown = document.getElementById("role_id");
                dropdown.onchange = function (event) {
                    if (dropdown.value == "picSite") {
                        document.getElementById("Jakarta").disabled = true;
                        document.getElementById("Berau").disabled = true;
                        selectopt('samarinda');
                    }else {
                        document.getElementById("Jakarta").disabled = false;
                    }
                    
                    if(dropdown.value == "purchasing"){
                        selectopt('Jakarta');
                        document.getElementById("Banjarmasin").disabled = true;
                        document.getElementById("Samarinda").disabled = true;
                        document.getElementById("Bunati").disabled = true;
                        document.getElementById("Babelan").disabled = true;
                        document.getElementById("Berau").disabled = true;
                        document.getElementById("Kendari").disabled = false;
                    }else{
                        document.getElementById("Jakarta").disabled = false;
                        document.getElementById("Samarinda").disabled = false;
                        document.getElementById("Banjarmasin").disabled = false;
                        document.getElementById("Bunati").disabled = false;
                        document.getElementById("Babelan").disabled = false;
                        document.getElementById("Berau").disabled = false;
                        document.getElementById("Kendari").disabled = false;
                    }
                }
            </script>
        </form>
    </x-auth-card>
</x-guest-layout>

</html>
