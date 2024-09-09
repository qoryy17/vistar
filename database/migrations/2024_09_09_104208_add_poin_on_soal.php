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
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->integer('poin_a')->after('jawaban_e')->nullable();
            $table->integer('poin_b')->after('poin_a')->nullable();
            $table->integer('poin_c')->after('poin_b')->nullable();
            $table->integer('poin_d')->after('poin_c')->nullable();
            $table->integer('poin_e')->after('poin_d')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->dropColumn('poin');
        });
    }
};
