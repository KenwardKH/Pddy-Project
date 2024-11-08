<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function home()
{
    return view('pengguna.pengguna_home');
}
}