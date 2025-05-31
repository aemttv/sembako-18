<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $supplier1 = new Supplier();
        $supplier1->idsupplier = Supplier::generateNewIdSupplier();  // Generate new ID like A001, A002, etc.
        $supplier1->nama = 'Aira';
        $supplier1->nohp = '082331940061';
        $supplier1->alamat = 'Jl. Raya Lontar Blk. AB No.61, Surabaya';
        $supplier1->save();

        $supplier2 = new Supplier();
        $supplier2->idsupplier = Supplier::generateNewIdSupplier();  // Generate new ID like A001, A002, etc.
        $supplier2->nama = 'Tatik';
        $supplier2->nohp = '085100626195';
        $supplier2->alamat = 'Jl. Pasar Raya Darmo Permai Timur III, Surabaya';
        $supplier2->save();
        
        $supplier3 = new Supplier();
        $supplier3->idsupplier = Supplier::generateNewIdSupplier();  // Generate new ID like A001, A002, etc.
        $supplier3->nama = 'Noh Kurniawan';
        $supplier3->nohp = '081217760979';
        $supplier3->alamat = 'Jl. Balongsari, Tandes, Surabaya';
        $supplier3->save();

        // Let's say we want to create 10 accounts for example
        // for ($i = 0; $i < 2; $i++) {
        //     $supplier = new Supplier();
        //     $supplier->idsupplier = Supplier::generateNewIdSupplier();  // Generate new ID like A001, A002, etc.
        //     $supplier->nama = $faker->name();
        //     $supplier->nohp = $faker->phoneNumber();
        //     $supplier->alamat = $faker->address();
        //     $supplier->status = 1;  // Active by default
        //     $supplier->save();
        // }
    }
}
