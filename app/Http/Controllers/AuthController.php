<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Proses autentikasi user
     */
    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            sweetalert()->info('Selamat datang ' . Auth::user()->name . '!');
            return redirect()->intended('/dashboard');
        } else {
            sweetalert()->error('Login Gagal! Email atau Password Salah!');
            return redirect('/login')->withInput();
        }
    }

    /**
     * Logout user dan hapus session
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        sweetalert()->success('Berhasil logout!');
        return redirect('/login');
    }
}
