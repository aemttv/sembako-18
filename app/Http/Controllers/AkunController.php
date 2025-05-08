<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    function viewAkun()
    {
        $akun = Akun::where('statusAkun', 1)->paginate(10);

        return view('account.akun', ['akun' => $akun]);
    }
}
