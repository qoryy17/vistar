<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->createMany([
            'id' => rand(1, 999) . rand(1, 99),
            'name' => 'Qori Chairawan',
            'email' => 'qorichairawan17@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
            'remember_token' => Str::random(10),
            'role' => 'Superadmin',
            'tentor_id' => null,
            'customer_id' => null,
            'blokir' => 'T',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'name' => 'Ahmad Yusri',
            'email' => 'ahmadyusri@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
            'remember_token' => Str::random(10),
            'role' => 'Admin',
            'tentor_id' => null,
            'customer_id' => null,
            'blokir' => 'T',
        ]);

        KategoriProduk::factory()->createMany([
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'CPNS',
            'status' => 'Berbayar',
            'aktif' => 'Y',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'CPNS',
            'status' => 'Gratis',
            'aktif' => 'Y',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'PPPK',
            'status' => 'Berbayar',
            'aktif' => 'Y',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'PPPK',
            'status' => 'Gratis',
            'aktif' => 'Y',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'Kedinasan',
            'status' => 'Berbayar',
            'aktif' => 'Y',
        ], [
            'id' => rand(1, 999) . rand(1, 99),
            'judul' => 'Kedinasan',
            'status' => 'Gratis',
            'aktif' => 'Y',
        ]);
    }
}
