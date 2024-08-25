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
        Schema::create('bank_referral', function (Blueprint $table) {
            $table->id();
            $table->string('kode_referral');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('produk_tryout_id');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_referral');
    }
};
