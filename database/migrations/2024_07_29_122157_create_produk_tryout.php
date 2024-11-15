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

        Schema::create('klasifikasi_soal', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('alias');
            $table->string('aktif', 1);
            $table->timestamps();
        });

        Schema::create('soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_soal', 12);
            $table->text('soal');
            $table->string('gambar')->nullable();
            $table->text('jawaban_a');
            $table->text('jawaban_b');
            $table->text('jawaban_c');
            $table->text('jawaban_d');
            $table->text('jawaban_d');
            $table->string('kunci_jawaban', 1);
            $table->unsignedBigInteger('klasifikasi_soal_id')->nullable();
            $table->text('review_pembahasan');
            $table->timestamps();

            $table->foreign('klasifikasi_soal_id')->references('id')->on('klasifikasi_soal')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('pengaturan_tryout', function (Blueprint $table) {
            $table->id();
            $table->integer('harga');
            $table->integer('harga_promo')->nullable();
            $table->integer('durasi');
            $table->string('lihat_peserta_lain', 1);
            $table->string('nilai_keluar', 1);
            $table->string('grafik_evaluasi', 1);
            $table->string('review_pembahasan', 1);
            $table->string('ulang_ujian', 1);
            $table->integer('masa_aktif');
            $table->timestamps();
        });

        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('status');
            $table->string('aktif', 1);
            $table->timestamps();
        });

        Schema::create('produk_tryout', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tryout');
            $table->string('keterangan');
            $table->string('kode_soal', 12);
            $table->unsignedBigInteger('pengaturan_tryout_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kategori_produk_id');
            $table->string('status', 100);
            $table->string('thumbnail', 500);
            $table->timestamps();

            $table->foreign('pengaturan_tryout_id')->references('id')->on('pengaturan_tryout')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('set null');
            $table->foreign('kategori_produk_id')->references('id')->on('kategori_produk')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('order_tryout', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('faktur_id', 12)->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->string('nama');
            $table->unsignedBigInteger('produk_tryout_id');
            $table->uuid('payment_id')->nullable();
            $table->string('status_order')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('payment_id')->references('id')->on('payment')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('progres_ujian', function (Blueprint $table) {
            $table->id();
            $table->uuid('ujian_id');
            $table->unsignedBigInteger('soal_ujian_id');
            $table->string('kode_soal', 12);
            $table->string('jawaban', 1);
            $table->timestamps();
            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('soal_ujian_id')->references('id')->on('soal_ujian')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('limit_tryout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('produk_tryout_id');
            $table->string('bukti_share');
            $table->string('bukti_follow');
            $table->string('informasi', 100);
            $table->text('alasan');
            $table->string('status_validasi');
            $table->unsignedBigInteger('validasi_oleh');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produk_tryout_id')->references('id')->on('produk_tryout')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('validasi_oleh')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_tryout_id')->nullable();
            $table->unsignedBigInteger('limit_tryout_id')->nullable();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_berakhir')->nullable();
            $table->integer('durasi_ujian');
            $table->integer('sisa_waktu')->nullable();
            $table->integer('soal_terjawab')->nullable();
            $table->integer('soal_belum_terjawab')->nullable();
            $table->string('status_ujian');
            $table->timestamps();

            $table->foreign('order_tryout_id')->references('id')->on('order_tryout')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('limit_tryout_id')->references('id')->on('limit_tryout')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('hasil_ujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->timestamp('durasi_selesai');
            $table->integer('benar');
            $table->integer('salah');
            $table->integer('terjawab');
            $table->integer('tidak_terjawab');
            $table->float('total_nilai');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('hasil_pg_klasifikasi_soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hasil_ujian_id');
            $table->string('judul');
            $table->string('alias');
            $table->integer('passing_grade');
            $table->double('total_nilai');
            $table->timestamps();

            $table->foreign('hasil_ujian_id')->references('id')->on('ujian')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_soal');
        Schema::dropIfExists('soal_ujian');
        Schema::dropIfExists('pengaturan_tryout');
        Schema::dropIfExists('kategori_produk');
        Schema::dropIfExists('produk_tryout');
        Schema::dropIfExists('order_tryout');
        Schema::dropIfExists('progres_ujian');
        Schema::dropIfExists('ujian');
        Schema::dropIfExists('hasil_ujian');
        Schema::dropIfExists('limit_tryout');
        Schema::dropIfExists('hasil_pg_klasifikasi_soal');
    }
};
