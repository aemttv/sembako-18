<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    function viewAkun()
    {
        $akun = Akun::where('statusAkun', 1)->paginate(10);

        return view('account.akun', ['akun' => $akun]);
    }

    function viewTambahAkun() {
        return view('account.tambah');
    }

    function tambahAkun(Request $request) {
        
        DB::beginTransaction();

            foreach ($request->staff_input as $jsonItem) {
                $item = json_decode($jsonItem, true);

                $akun = new Akun();
                $akun->idAkun = Akun::generateNewId();
                $akun->nama = $item['nama'];
                $akun->email = $item['email'];
                $akun->password = bcrypt($item['password']);
                $akun->nohp = $item['no_hp'];
                $akun->peran = $item['status_peran'];
                $akun->statusAkun = 1;
                $akun->save();
            }

        DB::commit();

        return redirect()->route('view.akun')->with('success', 'Informasi Staff berhasil disimpan');
        
    }
}
