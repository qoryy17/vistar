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
        Schema::table('visitor_produk', function (Blueprint $table) {
            $table->ipAddress()->after('nama_produk')->nullable();
            $table->date('tanggal')->after('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_produk', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropColumn('tanggal');
        });
    }
};
