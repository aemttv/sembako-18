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
        Schema::create('retur_barang', function (Blueprint $table) {
            $table->string('idBarangRetur', 11)->primary();
            $table->string('tglRetur');
            $table->string('idSupplier', 11);
            $table->string('penanggungJawab', 11);
            $table->integer('statusRetur')->default(2); //(e.g., pending, approved, rejected, completed)
            
            $table->timestamps();

            $table->foreign('idSupplier')->references('idSupplier')->on('supplier');
            $table->foreign('penanggungJawab')->references('idAkun')->on('akun');
        });

        Schema::create('detail_retur_barang', function (Blueprint $table) {
            $table->string('idDetailRetur', 11)->primary();
            $table->string('idBarangRetur', 11);
            $table->string('barcode');
            $table->integer('jumlah');
            $table->integer('kategoriAlasan');
            $table->string('keterangan', 100)->nullable();
            $table->integer('statusReturDetail')->default(2); //(e.g., some items approved, some rejected),
            
            $table->timestamps();

            $table->foreign('idBarangRetur')->references('idBarangRetur')->on('retur_barang');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_barang');
        Schema::dropIfExists('detail_retur_barang');
    }
};
