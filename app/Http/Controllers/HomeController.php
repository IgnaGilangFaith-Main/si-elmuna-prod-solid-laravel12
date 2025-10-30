<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Menampilkan halaman dashboard admin
     */
    public function adminDashboard()
    {
        return view('admin.index');
    }
}
