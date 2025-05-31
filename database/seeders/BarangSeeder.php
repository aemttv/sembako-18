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
            ['namaMerek' => 'Ajinomoto'],  //1
            ['namaMerek' => 'Desaku'],  //2
            ['namaMerek' => 'Good Day'],  //3
            ['namaMerek' => 'Indomie'], //4
            ['namaMerek' => 'Indomie Sedaap'], //5
            ['namaMerek' => 'Roma'],  //6
            ['namaMerek' => 'Mie Eko'], //7
            ['namaMerek' => 'Pinpin'],  //8
            ['namaMerek' => 'Racik'],   //9
            ['namaMerek' => 'Rinso'],   //10
            ['namaMerek' => 'SASA'],    //11
            ['namaMerek' => 'Shinzui'], //12
            ['namaMerek' => 'TOP Coffee'],  //13
            ['namaMerek' => 'Tidak tersedia'],  //14
        ];

        DB::table('merek_barang')->insert($data);

        $faker = Faker::create('id_ID');

        // Array of barang data with satuan (1 = PCS, 2 = KG)
        $barangsData = [
            [
                'namaBarang' => 'TOP COFFEE Cappucino',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 12,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Good Day Cappucino',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 3,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Racik Bumbu Ikan Goreng',
                'kategoriBarang' => 1,
                'hargaJual' => 3000,
                'merekBarang' => 9,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Racik Bumbu Nasi Goreng',
                'kategoriBarang' => 1,
                'hargaJual' => 3000,
                'merekBarang' => 9,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Marinasi Bumbu Tempe, Ikan & Ayam',
                'kategoriBarang' => 1,
                'hargaJual' => 2000,
                'merekBarang' => 2,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Kunyit Bubuk',
                'kategoriBarang' => 1,
                'hargaJual' => 2000,
                'merekBarang' => 2,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Mi Instan Mi Goreng',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 4,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Mi Instan Kuah Rasa Soto',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 5,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'SELECTION Seaweed Crunch',
                'kategoriBarang' => 1,
                'hargaJual' => 4000,
                'merekBarang' => 5,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'SELECTION Spicy Laksa',
                'kategoriBarang' => 1,
                'hargaJual' => 4000,
                'merekBarang' => 5,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Mi Instan Kuah Rasa Baso Spesial',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 5,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Roma Biskuit Kelapa',
                'kategoriBarang' => 1,
                'hargaJual' => 9500,
                'merekBarang' => 6,
                'satuan' => 1,
            ],
            [
                'namaBarang' => 'Telur Ayam',
                'kategoriBarang' => 1,
                'hargaJual' => 30000,
                'merekBarang' => 14,
                'satuan' => 2, // KG
            ],
            [
                'namaBarang' => 'Beras Pin Pin',
                'kategoriBarang' => 1,
                'hargaJual' => 17500,
                'merekBarang' => 8,
                'satuan' => 2, // KG
            ],
            [
                'namaBarang' => 'Rinso Anti Noda + Molto Rose Fresh',
                'kategoriBarang' => 1,
                'hargaJual' => 17500,
                'merekBarang' => 8,
                'satuan' => 1,
            ],
        ];

        foreach ($barangsData as $barangData) {
            $barang = new Barang();
            $barang->idBarang = Barang::generateNewIdBarang();
            $barang->namaBarang = $barangData['namaBarang'];
            $barang->kategoriBarang = $barangData['kategoriBarang'];
            $barang->hargaJual = $barangData['hargaJual'];
            $barang->merekBarang = $barangData['merekBarang'];
            $barang->satuan = $barangData['satuan'];
            $barang->save();

            // Generate 2–5 detail records for each barang
            $detailCount = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $detailCount; $j++) {
                $detail = new BarangDetail();
                $detail->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                $detail->idBarang = $barang->idBarang;
                $detail->idSupplier = $faker->randomElement(['S001', 'S002', 'S003']);
                $detail->kondisiBarang = $faker->randomElement(['Baik', 'Mendekati Kadaluarsa']);
                $detail->quantity = $faker->numberBetween(1, 10);
                $detail->hargaBeli = $faker->randomElement([1000, 3000, 5000, 7000, 10000]);
                $detail->tglMasuk = $faker->dateTimeBetween('-1 month', 'now');
                $detail->tglKadaluarsa = $faker->dateTimeBetween($detail->tglMasuk, '+6 months');
                $detail->barcode = BarangDetail::generateBarcode();
                $detail->statusDetailBarang = 1;
                $detail->save();
            }
        }


    }
}
