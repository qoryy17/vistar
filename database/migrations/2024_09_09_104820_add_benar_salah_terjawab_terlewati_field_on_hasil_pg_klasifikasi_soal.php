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
        Schema::table('hasil_pg_klasifikasi_soal', function (Blueprint $table) {
            $table->integer('terjawab')->after('passing_grade')->nullable();
            $table->integer('terlewati')->after('terjawab')->nullable();
            $table->integer('benar')->after('terlewati')->nullable();
            $table->integer('salah')->after('benar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_pg_klasifikasi_soal', function (Blueprint $table) {
            $table->dropColumn('terjawab');
            $table->dropColumn('terlewati');
            $table->dropColumn('benar');
            $table->dropColumn('salah');
        });
    }
};
