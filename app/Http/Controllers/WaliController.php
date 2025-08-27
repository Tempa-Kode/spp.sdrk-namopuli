<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliController extends Controller
{
    public function profil()
    {
        $data = Auth::user()->siswa;
    }
}
