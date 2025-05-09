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
            $table->float('hargaJual');
            $table->text('gambarProduk')->nullable();
            $table->integer('statusBarang')->default(1);
            $table->timestamps();
        });

        Schema::create('detail_barang', function (Blueprint $table) {
            $table->string('idDetailBarang')->primary();
            $table->string('idBarang');
            $table->string('kondisiBarang');
            $table->integer('quantity');
            $table->string('satuanBarang');
            $table->float('hargaBeli');
            $table->date('tglMasuk');
            $table->date('tglKadaluarsa');
            $table->string('barcode')->nullable();
            $table->timestamps();

            $table->foreign('idBarang')->references('idBarang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
        Schema::dropIfExists('detail_barang');
    }
};
