<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2 Owners
        $owner1 = new Akun();
        $owner1->idAkun = Akun::generateNewId();
        $owner1->nama = 'Yo San';
        $owner1->email = 'sani.aliem@gmail.com';
        $owner1->password = bcrypt('sani123');
        $owner1->nohp = '085150658692';
        $owner1->alamat = 'Jl.Puri Widya Kencana';
        $owner1->peran = 1;  // 1 = Owner
        $owner1->statusAkun = 1;
        $owner1->save();

        $owner2 = new Akun();
        $owner2->idAkun = Akun::generateNewId();
        $owner2->nama = 'Lina';
        $owner2->email = 'nyonyalina88@gmail.com';
        $owner2->password = bcrypt('lina123');
        $owner2->nohp = '081233747373';
        $owner2->alamat = 'Jl.Puri Widya Kencana';
        $owner2->peran = 1;  // 1 = Owner
        $owner2->statusAkun = 1;
        $owner2->save();

        // 3 Staffs
        $staff1 = new Akun();
        $staff1->idAkun = Akun::generateNewId();
        $staff1->nama = 'Ranti';
        $staff1->email = 'rantxas14@gmail.com';
        $staff1->password = bcrypt('sembako18');
        $staff1->nohp = '081239273443';
        $staff1->alamat = 'Jl. Kenanga Raya';
        $staff1->peran = 2;  // 2 = Staff
        $staff1->statusAkun = 1;
        $staff1->save();

        $staff2 = new Akun();
        $staff2->idAkun = Akun::generateNewId();
        $staff2->nama = 'Lastiya';
        $staff2->email = 'lastiya492@gmail.com';
        $staff2->password = bcrypt('sembako18');
        $staff2->nohp = '08512398723412';
        $staff2->alamat = 'Jl. Mawar Putih';
        $staff2->peran = 2;  // 2 = Staff
        $staff2->statusAkun = 1;
        $staff2->save();

        // $staff3 = new Akun();
        // $staff3->idAkun = Akun::generateNewId();
        // $staff3->nama = 'Sohibbin';
        // $staff3->email = 'binhibin14@gmail.com';
        // $staff3->password = bcrypt('sembako18');
        // $staff3->nohp = '0813321527389';
        // $staff3->alamat = 'Jl. Anggrek Merah';
        // $staff3->peran = 2;  // 2 = Staff
        // $staff3->statusAkun = 1;
        // $staff3->save();
    }
}
