<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create('id_ID');

        // Create 10 barang records
        for ($i = 0; $i < 10; $i++) {
            $barang = new Barang();
            $barang->idBarang = Barang::generateNewIdBarang();  // e.g., B001, B002, etc.
            $barang->namaBarang = $faker->word . ' ' . $faker->word; 
            $barang->kategoriBarang = $faker->numberBetween(1, 4);; // Assuming category ID 1 exists
            $barang->merekBarang = $faker->numberBetween(1, 10);;    // Assuming brand ID 1 exists
            $barang->stokAwalBarang = $faker->numberBetween(5, 100);
            $barang->stokBarangCurrent = $barang->stokAwalBarang;
            $barang->satuanBarang = $faker->numberBetween(1, 10);; // Assuming unit ID 1 exists
            $barang->tglMasuk = $faker->dateTimeBetween('-1 month', 'now');
            $barang->kondisiBarang = $faker->randomElement([1, 2, 3 ,4, 5]); // Example: 1 = Baik, 2 = Rusak
            $barang->hargaBeli = $faker->numberBetween(5000, 20000);
            $barang->hargaJual = $barang->hargaBeli + $faker->numberBetween(1000, 10000);
            $barang->save();
        }

    }
}
