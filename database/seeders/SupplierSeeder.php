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

        // Let's say we want to create 10 accounts for example
        for ($i = 0; $i < 5; $i++) {
            $supplier = new Supplier();
            $supplier->idsupplier = Supplier::generateNewIdSupplier();  // Generate new ID like A001, A002, etc.
            $supplier->nama = $faker->name();
            $supplier->nohp = $faker->phoneNumber();
            $supplier->alamat = $faker->address();
            $supplier->status = 1;  // Active by default
            $supplier->save();
        }
    }
}
