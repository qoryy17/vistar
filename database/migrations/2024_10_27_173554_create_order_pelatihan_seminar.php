<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_pelatihan_seminar', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('faktur_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->string('nama');
            $table->unsignedBigInteger('produk_pelatihan_seminar_id');
            $table->uuid('payment_id')->nullable();
            $table->string('status_order')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_pelatihan_seminar_id')->references('id')->on('produk_pelatihan_seminar')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('payment_id')->references('id')->on('payment')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pelatihan_seminar');
    }
};
