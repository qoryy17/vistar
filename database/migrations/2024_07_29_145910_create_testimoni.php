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
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('produk_tryout_id');
            $table->unsignedBigInteger('hasil_ujian_id');
            $table->text('testimoni');
            $table->integer('rating');
            $table->string('publish', 1)->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hasil_ujian_id')->references('id')->on('hasil_ujian')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};
