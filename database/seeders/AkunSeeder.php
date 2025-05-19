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
        $owner = new Akun();
        $owner->idAkun = Akun::generateNewId();  // Generate new ID like A001, A002, etc.
        $owner->nama = 'Yo San';
        $owner->email = 'sani.aliem@gmail.com';
        $owner->password = bcrypt('owner123');
        $owner->nohp = '085150658692';
        $owner->alamat = 'Jl.Puri Widya Kencana';
        $owner->peran = 1;  // Default role (could be 1 for admin, etc.)
        $owner->statusAkun = 1;  // Active by default
        $owner->save();

        // Let's say we want to create 10 accounts for example
        for ($i = 0; $i < 2; $i++) {
            $akun = new Akun();
            $akun->idAkun = Akun::generateNewId();  // Generate new ID like A001, A002, etc.
            $akun->nama = 'Staff ' . ($i + 1);
            $akun->email = 'Staff' . ($i + 1) . '@example.com';
            $akun->password = bcrypt('staff123');
            $akun->nohp = '0812345678';
            $akun->alamat = 'Alamat Default Staff ' . ($i + 1);
            $akun->peran = 2;  // Default role (could be 1 for admin, etc.)
            $akun->statusAkun = 1;  // Active by default
            $akun->save();
        }
    }
}
