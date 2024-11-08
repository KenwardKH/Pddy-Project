<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformasiPenggunaController extends Controller
{
    public function show()
    {
        return view('pengguna.profile');
    }
}
