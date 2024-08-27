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
        Schema::create('payment', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uucid());
            $table->string('ref_order_id')->nullable();
            $table->string('transaksi_id')->nullable();
            $table->integer('nominal');
            $table->string('metode')->nullable();
            $table->integer('biaya_admin')->nullable();
            $table->string('status_transaksi')->nullable();
            $table->string('status_fraud');
            $table->string('snap_token')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
