<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Indica tu usuario.',
            'password.required' => 'Indica tu contraseña.',
        ]);

        if (! Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], true)) {
            return back()->withInput($request->only('username'))->withErrors(['login' => 'Usuario o contraseña incorrectos.']);
        }

        $request->session()->regenerate();

        $return = $request->input('return_url');

        return redirect($return && str_starts_with($return, '/') ? $return : '/admin');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'invite_code' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4',
        ], [
            'username.unique' => 'Ese nombre de usuario ya existe.',
            'password.min' => 'Mínimo 4 caracteres',
        ]);

        if (! hash_equals((string) config('app.admin_invite_code'), $credentials['invite_code'])) {
            return back()->withInput($request->only('invite_code', 'username'))->withErrors(['register' => 'Código de invitación incorrecto.']);
        }

        $user = \App\Models\User::create([
            'username' => $credentials['username'],
            'name' => $credentials['username'],
            'email' => $credentials['username'].'@amaranta.local',
            'password' => $credentials['password'],
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect('/admin');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
