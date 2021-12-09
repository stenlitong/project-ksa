<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class adminRegisController extends Controller
{
    public function view()
    {
        return view('registeradmin');
    }
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_induk_pegawai' => ['required', 'string', 'max:6', 'min:6', 'unique:users'],
            'email' => ['required', 'string', 'email:rfc,dns', 'ends_with:ptksa.id', 'max:255', 'unique:users'],
            'cabang' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'no_induk_pegawai' => $request->no_induk_pegawai,
            'email' => $request->email,
            'cabang' => $request->cabang,
            'password' => Hash::make($request->password),
        ]);
        $user->attachRole($request->role_id);
        event(new Registered($user));

        Auth::login($user);
    }
}
