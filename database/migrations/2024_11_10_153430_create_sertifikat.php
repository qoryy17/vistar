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
        Schema::create('sertifikat_sertikom', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_indeks')->nullable()->unique();
            $table->string('nomor_sertifikat')->nullable();
            $table->unsignedBigInteger('produk_pelatihan_seminar_id')->nullable();
            $table->unsignedBigInteger('peserta_sertikom_id')->nullable();
            $table->timestamps();

            $table->foreign('produk_pelatihan_seminar_id')->references('id')->on('produk_pelatihan_seminar')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('peserta_sertikom_id')->references('id')->on('peserta_sertikom')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_sertikom');
    }
};
