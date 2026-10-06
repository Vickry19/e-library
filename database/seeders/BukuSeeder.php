<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $kategoriIds = Kategori::pluck('id')->toArray();

        // Cek apakah ada kategori
        if (empty($kategoriIds)) {
            $this->command->error('❌ Tidak ada kategori! Jalankan KategoriSeeder dulu.');
            return;
        }

        $judulDepan = [
            'Panduan', 'Dasar-dasar', 'Pengantar', 'Buku Lengkap',
            'Rahasia', 'Teknik', 'Metode', 'Strategi', 'Konsep',
            'Teori', 'Praktik', 'Mengenal', 'Memahami', 'Menguasai',
        ];

        $judulBelakang = [
            'Pemrograman', 'Kecerdasan Buatan', 'Data Science', 'Machine Learning',
            'Web Development', 'Mobile Apps', 'Keamanan Siber', 'Basis Data',
            'Jaringan Komputer', 'Sistem Operasi', 'Algoritma', 'Filsafat',
            'Sejarah Nusantara', 'Ekonomi Modern', 'Psikologi Klinis',
        ];

        $berhasil = 0;
        $gagal = 0;

        for ($i = 0; $i < 100; $i++) {
            $stok = rand(5, 50);

            // ==================== DOWNLOAD GAMBAR DULU ====================
            $imagePath = 'cover-buku/dummy-' . $i . '.jpg';
            
            try {
                $imageUrl = 'https://picsum.photos/seed/' . $i . '/300/400';
                $imageContent = @file_get_contents($imageUrl);

                if ($imageContent !== false) {
                    Storage::disk('public')->put($imagePath, $imageContent);
                    $image = $imagePath;
                    $berhasil++;
                } else {
                    $image = 'cover-buku/book-default-cover.jpg';
                    $gagal++;
                }
            } catch (\Exception $e) {
                $image = 'cover-buku/book-default-cover.jpg';
                $gagal++;
            }

            // ==================== SIMPAN BUKU ====================
            Buku::create([
                'judul_buku' => $faker->randomElement($judulDepan) . ' ' .
                                $faker->randomElement($judulBelakang),
                'id_kategori' => $faker->randomElement($kategoriIds),
                'pengarang' => $faker->name,
                'penerbit' => $faker->company,
                'tahun_terbit' => $faker->numberBetween(2015, 2024),
                'isbn' => $faker->isbn13,
                'stok' => $stok,
                'dipinjam' => 0,
                'dibooking' => 0,
                'image' => $image,  // ← INI YANG DIPAKAI
            ]);
        }

        $this->command->info("✅ Buku created: 100");
        $this->command->info("   📷 Gambar berhasil: {$berhasil}");
        $this->command->info("   ⚠️  Gambar default: {$gagal}");
    }
}