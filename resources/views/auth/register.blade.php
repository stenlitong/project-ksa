<head>
    <title>Register</title>
</head>
<x-guest-layout>
    <x-auth-card>
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

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <br>
            <!-- No Induk Pegawai -->
            <div>
                <x-label for="no_induk_pegawai" :value="__('No Induk Pegawai')" />

                <x-input id="no_induk_pegawai" class="block mt-1 w-full" type="text" name="no_induk_pegawai" :value="old('no_induk_pegawai')" required autofocus />
            </div>

            <br>

            <!-- Select Option role -->
            <div>
                <x-label for="role_id" :value="__('Register as:')" />

                <select name="role_id" id="role_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="picSite" id="picSite">PIC Site</option>
                    <option value="picAdmin" id="picAdmin">PIC Admin</option>
                    <option value="picIncident" id="picIncident">PIC Incident</option>
                    <option value="insurance" id="insurance">Asuransi</option>
                    <option value="adminOperational">Admin operational</option>
                    <option value="adminPurchase">Admin Purchasing</option>
                    <option value="purchasing">Purchasing</option>
                    <option value="logistic">Logistic</option>
                    <option value="crew">Crew</option>
                </select>
            </div>
            <br>
            
            <!-- Select Option Cabang -->
            <div>
                <x-label for="cabang" :value="__('Cabang:')" />

                <select name="cabang" id="cabang" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                    <option selected disabled="">Choose...</option>
                    <option value="Jakarta" id="jakarta">Jakarta</option>
                    <option value="Banjarmasin" id="Banjarmasin">Banjarmasin</option>
                    <option value="Samarinda" id="samarinda">Samarinda</option>
                    <option value="Morosi" id="morosi">Morosi</option>
                    <option value="Bunati" id ="bunati">Bunati</option>
                    <option value="Babelan"id ="babelan">Babelan</option>
                    <option value="Berau"id ="berau">Berau</option>
                </select>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <br>
            
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
            {{-- validation script --}}
            <script>
                function selectopt(id)
                {
                    var e = document.getElementById("cabang");
                    e.selectedIndex=e.querySelector('option[value="'+id+'"]').index;
                }
                var dropdown = document.getElementById("role_id");
                dropdown.onchange = function(event){
                    
                    if(dropdown.value=="picAdmin"){
                        selectopt('jakarta');
                        document.getElementById("samarinda").disabled = true;
                        document.getElementById("Banjarmasin").disabled = true;
                        document.getElementById("morosi").disabled = true;
                        document.getElementById("bunati").disabled = true;
                        document.getElementById("babelan").disabled = true;
                        document.getElementById("berau").disabled = true;
                    }else{
                        document.getElementById("samarinda").disabled = false;
                        document.getElementById("Banjarmasin").disabled = false;
                        document.getElementById("morosi").disabled = false;
                        document.getElementById("bunati").disabled = false;
                        document.getElementById("babelan").disabled = false;
                        document.getElementById("berau").disabled = false;
                    }
                    
                    if (dropdown.value=="picSite") {
                        document.getElementById("jakarta").disabled = true;
                        selectopt('samarinda');

                    } else {
                            document.getElementById("jakarta").disabled = false;
                        }
                }
            </script>
        </form>
    </x-auth-card>
</x-guest-layout>