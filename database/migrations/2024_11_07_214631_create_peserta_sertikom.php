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
        Schema::create('peserta_sertikom', function (Blueprint $table) {
            $table->id();
            $table->string('tahapan_sertikom_kode');
            $table->string('kode_peserta');
            $table->uuid('order_pelatihan_seminar_id');
            $table->string('nama');
            $table->string('kontak')->nullable();
            $table->text('link_pretest')->nullable();
            $table->text('link_posttest')->nullable();
            $table->text('path_sertifikat_kehadiran')->nullable();
            $table->text('path_sertifikat_pelatihan')->nullable();
            $table->timestamps();

            $table->foreign('order_pelatihan_seminar_id')->references('id')->on('order_pelatihan_seminar')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_sertikom');
    }
};
