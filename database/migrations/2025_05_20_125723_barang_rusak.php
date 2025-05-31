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
        Schema::create('barang_rusak', function (Blueprint $table) {
            $table->string('idBarangRusak', 11)->primary();
            $table->string('tglRusak');
            $table->string('penanggungJawab', 11);
            $table->integer('statusRusak')->default(2);
            
            $table->timestamps();

            $table->foreign('penanggungJawab')->references('idAkun')->on('akun');
        });

        Schema::create('detail_barang_rusak', function (Blueprint $table) {
            $table->string('idDetailBR', 11)->primary();
            $table->string('idBarangRusak', 11);
            $table->string('idBarang', 11);
            $table->string('barcode');
            $table->float('jumlah');
            $table->integer('kategoriAlasan');
            $table->string('keterangan', 100)->nullable();
            $table->integer('statusRusakDetail')->default(2);
            
            $table->timestamps();

            $table->foreign('idBarangRusak')->references('idBarangRusak')->on('barang_rusak');
            $table->foreign('idBarang')->references('idBarang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_rusak');
        Schema::dropIfExists('detail_barang_rusak');
    }
};
