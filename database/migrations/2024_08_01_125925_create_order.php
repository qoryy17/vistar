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
        Schema::create('keranjang_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_tryout_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();

            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('rekap_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_tryout_id')->nullable();
            $table->unsignedBigInteger('produk_event_id')->nullable();
            $table->timestamps();

            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('produk_event_id')->references('id')->on('produk_event')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang_order');
        Schema::dropIfExists('rekap_order');
    }
};
