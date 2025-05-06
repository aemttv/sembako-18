<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('idBarang')->primary();
            $table->string('namaBarang');
            $table->integer('kategoriBarang');
            $table->integer('merekBarang');
            $table->integer('stokAwalBarang');
            $table->integer('stokBarangCurrent');
            $table->integer('satuanBarang');
            $table->date('tglMasuk');
            $table->integer('kondisiBarang');
            $table->float('hargaBeli');
            $table->float('hargaJual');
            $table->text('gambarProduk')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('statusBarang')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
