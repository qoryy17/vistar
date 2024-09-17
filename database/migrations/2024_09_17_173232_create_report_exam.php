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
        Schema::create('report_ujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_tryout_id')->nullable();
            $table->unsignedBigInteger('soal_id')->nullable();
            $table->text('deskripsi');
            $table->text('screenshot');
            $table->string('status', 10);
            $table->timestamps();

            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal_ujian')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_ujian');
    }
};
