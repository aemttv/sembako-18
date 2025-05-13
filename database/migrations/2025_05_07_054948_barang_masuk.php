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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->string('idBarangMasuk', 11)->primary();
            $table->string('idSupplier', 11);
            $table->string('idAkun', 11);
            $table->date('tglMasuk');
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->foreign('idSupplier')->references('idSupplier')->on('supplier');
            $table->foreign('idAkun')->references('idAkun')->on('akun');
        });

        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->string('idDetailBM', 11)->primary();
            $table->string('idBarangMasuk', 11);
            $table->string('idBarang', 11);
            $table->integer('jumlahMasuk');
            $table->float('hargaBeli');
            $table->float('subtotal');
            $table->date('tglKadaluarsa');
            $table->timestamps();

            $table->foreign('idBarangMasuk')->references('idBarangMasuk')->on('barang_masuk');
            $table->foreign('idBarang')->references('idBarang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
        Schema::dropIfExists('detail_barang_masuk');
    }
};
