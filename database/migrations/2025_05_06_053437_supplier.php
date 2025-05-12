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
        Schema::create('supplier', function (Blueprint $table) {
            $table->string('idSupplier', 11)->primary();
            $table->string('nama', 100);
            $table->string('nohp', 15);
            $table->string('alamat')->nullable();
            $table->integer('status', 1)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
