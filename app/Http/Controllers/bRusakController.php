<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class bRusakController extends Controller
{
    public function viewConfirmBRusak()
    {
        return view('menu.icare.rusak.confirm-bRusak');
    }
}
