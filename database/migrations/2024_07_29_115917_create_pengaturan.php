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
        Schema::create('pengaturan_web', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bisnis');
            $table->string('tagline', 500);
            $table->string('perusahaan');
            $table->string('alamat', 300);
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('kontak', 15)->nullable();
            $table->string('logo', 30);
            $table->string('meta_author');
            $table->text('meta_keyword');
            $table->text('meta_description');
            $table->timestamps();
        });

        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan', 300);
            $table->text('jawaban');
            $table->string('publish', 1);
            $table->timestamps();
        });

        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar', 100);
            $table->string('publish', 1);
            $table->timestamps();
        });

        Schema::create('versi', function (Blueprint $table) {
            $table->id();
            $table->string('versi', 100);
            $table->text('pembaharuan')->nullable();
            $table->text('pemeliharaan')->nullable();
            $table->timestamps();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->ipAddress();
            $table->longText('user_agent');
            $table->text('aktivitas');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_web');
        Schema::dropIfExists('faq');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('versi');
        Schema::dropIfExists('logs');
    }
};
