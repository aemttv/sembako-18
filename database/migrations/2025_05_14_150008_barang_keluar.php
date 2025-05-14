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
        Schema::create('barang_keluar', function ( Blueprint $table ) {
            $table->string('idBarangKeluar', 11)->primary();
            $table->string('invoice')->after('idBarangKeluar')->unique();
            $table->string('idAkun', 11);
            $table->date('tglKeluar');

            $table->timestamps();
        });

        Schema::create('detail_barang_keluar', function ( Blueprint $table ) {
            $table->string('idDetailBK', 11)->primary();
            $table->string('idBarangKeluar', 11);
            $table->string('idBarang', 11);
            $table->integer('jumlahKeluar');
            $table->float('subtotal');
            $table->integer('kategoriAlasan');
            $table->string('keterangan', 100)->nullable();

            $table->timestamps();

            $table->foreign('idBarangKeluar')->references('idBarangKeluar')->on('barang_keluar');
            $table->foreign('idBarang')->references('idBarang')->on('barang');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
        Schema::dropIfExists('detail_barang_keluar');
    }
};
