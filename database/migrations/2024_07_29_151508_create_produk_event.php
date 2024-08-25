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
        Schema::create('produk_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_tryout_id')->nullable();
            $table->date('tanggal_berlangsung');
            $table->date('tanggal_berakhir');
            $table->string('publish', 1);
            $table->timestamps();

            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_event');
    }
};
