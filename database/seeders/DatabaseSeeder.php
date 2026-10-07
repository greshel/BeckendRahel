<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Kelas::create([
            'judul'    => 'Laravel REST API Masterclass',
            'pengajar' => 'Rahel',
            'harga'    => 150000,
            'kuota'    => 20,
        ]);

        Kelas::create([
            'judul'    => 'Vue.js 3 Frontend Development',
            'pengajar' => 'Budi',
            'harga'    => 120000,
            'kuota'    => 15,
        ]);

        Kelas::create([
            'judul'    => 'Flutter Mobile App',
            'pengajar' => 'Siti',
            'harga'    => 200000,
            'kuota'    => 10,
        ]);
    }
}