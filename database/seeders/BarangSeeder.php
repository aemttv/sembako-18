<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['namaMerek' => 'Indomie'],
            ['namaMerek' => 'Mie Sedaap'],
            ['namaMerek' => 'Mie Eko'],
            ['namaMerek' => 'Masako'],
            ['namaMerek' => 'Shinzui'],
            ['namaMerek' => 'Rinso'],
            ['namaMerek' => 'Royco'],
            ['namaMerek' => 'Racik'],
            ['namaMerek' => 'Sasa'],
            ['namaMerek' => 'ABC'],
        ];

        DB::table('merek_barang')->insert($data);

        $faker = Faker::create('id_ID');

        // Create 10 barang records
        for ($i = 0; $i < 3; $i++) {
            $barang = new Barang();
            $barang->idBarang = Barang::generateNewIdBarang();  // e.g., B001
            $barang->namaBarang = 'Produk ' . ($i + 1);
            $barang->kategoriBarang = $faker->numberBetween(1, 4);
            $barang->merekBarang = $faker->numberBetween(1, 10);
            $barang->stokBarang = 0;
            $barang->hargaJual = $faker->randomElement([5000, 8000, 12000]); // set a fallback if no hargaBeli yet
            $barang->gambarProduk = null;
            $barang->save();

            // Create 2–5 detail records for each barang
            $detailCount = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $detailCount; $j++) {
                $detail = new BarangDetail();
                $detail->idDetailBarang = BarangDetail::generateNewIdBarangDetail();  // e.g., DB001
                $detail->idBarang = $barang->idBarang;
                $detail->kondisiBarang = $faker->randomElement(['Layak', 'Mendekati Kadaluarsa']);
                $detail->quantity = $faker->numberBetween(1, 10);
                $detail->satuanBarang = 'PCS';
                $detail->hargaBeli = $faker->randomElement([1000, 3000, 5000, 7000, 10000]);
                $detail->tglMasuk = $faker->dateTimeBetween('-1 month', 'now');
                $detail->tglKadaluarsa = $faker->dateTimeBetween($detail->tglMasuk, '+6 months');
                $detail->barcode = $faker->ean13();
                $detail->statusBarang = 1;
                $detail->save();

                // Optionally update stok in Barang if needed:
                $barang->stokBarang += $detail->quantity;
            }

            // Save updated stock total
            $barang->save();
        }


    }
}
