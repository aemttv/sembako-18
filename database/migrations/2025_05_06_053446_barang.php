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
        Schema::create('merek_barang', function (Blueprint $table) {
            $table->integer('idMerek')->primary()->autoIncrement();
            $table->string('namaMerek', 100);
            $table->timestamps();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->string('idBarang', 11)->primary();
            $table->string('namaBarang', 100);
            $table->integer('kategoriBarang');
            $table->integer('merekBarang');
            $table->integer('stokBarang');
            $table->float('hargaJual');
            $table->text('gambarProduk')->nullable();
            $table->timestamps();

            $table->foreign('merekBarang')->references('idMerek')->on('merek_barang');
        });

        Schema::create('detail_barang', function (Blueprint $table) {
            $table->string('idDetailBarang', 11)->primary();
            $table->string('idBarang', 11);
            $table->string('kondisiBarang', 50);
            $table->integer('quantity');
            $table->string('satuanBarang', 50);
            $table->float('hargaBeli');
            $table->date('tglMasuk');
            $table->date('tglKadaluarsa');
            $table->string('barcode')->nullable();
            $table->integer('statusBarang')->default(1);
            $table->timestamps();

            $table->foreign('idBarang')->references('idBarang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merek_barang');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('detail_barang');
    }
};
