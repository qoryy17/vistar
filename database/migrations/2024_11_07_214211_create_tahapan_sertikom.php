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
        Schema::create('tahapan_sertikom', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->unsignedBigInteger('produk_pelatihan_seminar_id')->nullable();
            $table->enum('tahapan', ['Proses', 'Tugas', 'Review', 'Selesai'])->nullable();
            $table->timestamps();

            $table->foreign('produk_pelatihan_seminar_id')->references('id')->on('produk_pelatihan_seminar')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapan_sertikom');
    }
};
