<?php

namespace Database\Seeders;

use App\Models\Akun;
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
        // Let's say we want to create 10 accounts for example
        for ($i = 0; $i < 2; $i++) {
            $akun = new Akun();
            $akun->idAkun = Akun::generateNewId();  // Generate new ID like A001, A002, etc.
            $akun->nama = 'User ' . ($i + 1);
            $akun->email = 'user' . ($i + 1) . '@example.com';
            $akun->password = bcrypt('password123');
            $akun->nohp = '0812345678';
            $akun->alamat = 'Address ' . ($i + 1);
            $akun->peran = 2;  // Default role (could be 1 for admin, etc.)
            $akun->statusAkun = 1;  // Active by default
            $akun->save();
        }
    }
}
