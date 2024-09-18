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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('produk_tryout_id')->nullable(false);
            $table->unsignedBigInteger('soal_id')->nullable(false);
            $table->text('deskripsi');
            $table->text('screenshot');
            $table->string('status', 10);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal_ujian')->onDelete('cascade');
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
