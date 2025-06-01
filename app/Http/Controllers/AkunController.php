<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    function viewAkun()
    {
        if(!isUserLoggedIn()){
            return redirect()->route('login');
        }
        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }
        $akun = Akun::paginate(10);

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

    public function editAkun(Request $request, $idAkun) {
        try {
            $request->validate([
            'nama' => 'required',
            'nohp' => 'required',
            'email' => 'required|email|unique:akun,email,' . $idAkun . ',idAkun',
            ]);

            $akun = Akun::where('idAkun', $idAkun)->first();

            if (!$akun) {
                return redirect()->route('view.akun')->with(['success' => false, 'message' => 'Akun not found'], 404);
            }

            $updateData = [
                'nama' => $request->nama,
                'nohp' => $request->nohp,
                'email' => $request->email,
                'peran' => $request->peran,
                'statusAkun' => $request->statusAkun,
            ];
            
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $akun->update($updateData);

            return redirect()->route('view.akun')->with('success', 'Informasi Staff berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah akun: ' . $e->getMessage())
                ->withInput();
        }
        
    }
    
        
    

    public function search(Request $request)
    {
        $query = $request->get('q'); // Search query from input

        // Search for suppliers with names that contain the query string
        $akun = Akun::where('nama', 'like', "%$query%")
                            ->select('idAkun', 'nama')
                            ->get();  // Only retrieve id and name fields for efficiency

        return response()->json($akun); // Return matched suppliers as JSON
    }
}
