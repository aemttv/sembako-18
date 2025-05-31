<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bMasuk;
use App\Models\bMasukDetail;
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
        // Insert merek_barang data
        $data = [
            ['namaMerek' => 'Ajinomoto'],
            ['namaMerek' => 'Desaku'],
            ['namaMerek' => 'Good Day'],
            ['namaMerek' => 'Indomie'],
            ['namaMerek' => 'Indomie Sedaap'],
            ['namaMerek' => 'Roma'],
            ['namaMerek' => 'Mie Eko'],
            ['namaMerek' => 'Pinpin'],
            ['namaMerek' => 'Racik'],
            ['namaMerek' => 'Rinso'],
            ['namaMerek' => 'SASA'],
            ['namaMerek' => 'Shinzui'],
            ['namaMerek' => 'TOP Coffee'],
            ['namaMerek' => 'Tidak tersedia'],
        ];
        DB::table('merek_barang')->insert($data);

        $faker = Faker::create('id_ID');

        // Array of barang data with satuan (1 = PCS, 2 = KG)
        $barangsData = [
            [
                'namaBarang' => 'TOP COFFEE Cappucino',
                'kategoriBarang' => 1,
                'hargaJual' => 3500,
                'merekBarang' => 13,
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
                'merekBarang' => 10,
                'satuan' => 1,
            ],
        ];

        foreach ($barangsData as $barangData) {
            // Insert Barang
            $barang = new Barang();
            $barang->idBarang = Barang::generateNewIdBarang();
            $barang->namaBarang = $barangData['namaBarang'];
            $barang->kategoriBarang = $barangData['kategoriBarang'];
            $barang->hargaJual = $barangData['hargaJual'];
            $barang->merekBarang = $barangData['merekBarang'];
            $barang->satuan = $barangData['satuan'];
            $barang->save();

            // Generate a BarangMasuk (simulate a stock entry)
            $barangMasuk = new bMasuk();
            $barangMasuk->idBarangMasuk = bMasuk::generateNewIdBarangMasuk();
            $barangMasuk->idSupplier = $faker->randomElement(['S001', 'S002', 'S003']);
            $barangMasuk->idAkun = $faker->randomElement(['A001', 'A002', 'A003']);
            $barangMasuk->tglMasuk = $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
            $barangMasuk->nota = $faker->optional()->text(50);
            $barangMasuk->save();

            // Generate 2–5 detail_barang_masuk records for each barang_masuk
            $detailCount = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $detailCount; $j++) {
                $detail = new bMasukDetail();
                $detail->idDetailBM = bMasukDetail::generateNewIdDetailBM();
                $detail->idBarangMasuk = $barangMasuk->idBarangMasuk;
                $detail->idBarang = $barang->idBarang;
                $detail->jumlahMasuk = $faker->numberBetween(1, 10);
                $detail->hargaBeli = $faker->randomElement([1000, 3000, 5000, 7000, 10000]);
                $detail->subtotal = $detail->jumlahMasuk * $detail->hargaBeli;
                $detail->tglKadaluarsa = $faker->dateTimeBetween($barangMasuk->tglMasuk, '+6 months')->format('Y-m-d');
                $detail->save();

                // Insert into BarangDetail as well
                $barangDetail = new BarangDetail();
                $barangDetail->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                $barangDetail->idBarang = $barang->idBarang;
                $barangDetail->idSupplier = $barangMasuk->idSupplier;
                $barangDetail->kondisiBarang = $faker->randomElement(['Baik', 'Mendekati Kadaluarsa']);
                $barangDetail->quantity = $detail->jumlahMasuk;
                $barangDetail->hargaBeli = $detail->hargaBeli;
                $barangDetail->tglMasuk = $barangMasuk->tglMasuk;
                $barangDetail->tglKadaluarsa = $detail->tglKadaluarsa;
                $barangDetail->barcode = BarangDetail::generateBarcode();
                $barangDetail->statusDetailBarang = $faker->randomElement([1, 2]);
                $barangDetail->save();
            }
        }
    }
}
