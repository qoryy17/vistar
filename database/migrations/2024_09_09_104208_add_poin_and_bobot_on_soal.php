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
            $table->integer('poin_a')->after('jawaban_e')->default(0);
            $table->integer('poin_b')->after('poin_a')->default(0);
            $table->integer('poin_c')->after('poin_b')->default(0);
            $table->integer('poin_d')->after('poin_c')->default(0);
            $table->integer('poin_e')->after('poin_d')->default(0);

            $table->boolean('berbobot')->after('poin_e')->default(false);
            $table->dropColumn('poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->dropColumn('poin_a');
            $table->dropColumn('poin_b');
            $table->dropColumn('poin_c');
            $table->dropColumn('poin_d');
            $table->dropColumn('poin_e');
            $table->dropColumn('berbobot');

            $table->double('poin')->after('jawaban_e');
        });
    }
};
