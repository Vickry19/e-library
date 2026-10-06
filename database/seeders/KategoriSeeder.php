<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $kategoriList = [
            'Sains', 'Teknologi', 'Sejarah', 'Agama', 'Filsafat',
            'Ekonomi', 'Politik', 'Sastra', 'Seni', 'Kesehatan',
            'Psikologi', 'Hukum', 'Pendidikan', 'Olahraga', 'Komputer',
        ];

        foreach ($kategoriList as $nama) {
            Kategori::create([
                'nama_kategori' => $nama,
            ]);
        }

        $this->command->info('✅ Kategori created: ' . count($kategoriList));
    }
}