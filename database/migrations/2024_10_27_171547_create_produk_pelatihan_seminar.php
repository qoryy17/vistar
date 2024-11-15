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
        Schema::create('produk_pelatihan_seminar', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('produk', 300);
            $table->integer('harga');
            $table->text('deskripsi');
            $table->unsignedBigInteger('instruktur_id')->nullable();
            $table->unsignedBigInteger('kategori_produk_id');
            $table->unsignedBigInteger('topik_keahlian_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->text('thumbnail');
            $table->string('publish', 1);
            $table->text('link_zoom')->nullable();
            $table->text('link_wa')->nullable();
            $table->text('link_rekaman')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('instruktur_id')->references('id')->on('instruktur')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kategori_produk_id')->references('id')->on('kategori_produk')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('topik_keahlian_id')->references('id')->on('topik_keahlian')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_pelatihan_seminar');
    }
};
