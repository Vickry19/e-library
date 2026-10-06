<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Booking;
use App\Models\PinjamDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixStokSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Mulai sinkronisasi stok...');

        // 1. Reset semua counter
        DB::table('buku')->update([
            'dipinjam' => 0,
            'dibooking' => 0,
        ]);

        // 2. Reset stok ke nilai dasar (misal 100 per buku)
        DB::table('buku')->update(['stok' => 100]);
        $this->command->info('✅ Reset stok dasar: 100 per buku');

        // 3. Hitung ulang DIPINJAM dari pinjam_detail
        $dipinjam = PinjamDetail::where('status', 'Pinjam')
            ->select('id_buku', DB::raw('COUNT(*) as total'))
            ->groupBy('id_buku')
            ->get();

        foreach ($dipinjam as $item) {
            DB::table('buku')
                ->where('id', $item->id_buku)
                ->update([
                    'dipinjam' => $item->total,
                    'stok' => DB::raw('100 - ' . $item->total),
                ]);
        }
        $this->command->info('✅ Update dipinjam: ' . $dipinjam->count() . ' buku');

        // 4. Hitung ulang DIBOOKING dari booking_detail
        $dibooking = DB::table('booking_detail')
            ->select('id_buku', DB::raw('COUNT(*) as total'))
            ->groupBy('id_buku')
            ->get();

        foreach ($dibooking as $item) {
            $buku = Buku::find($item->id_buku);
            if ($buku) {
                DB::table('buku')
                    ->where('id', $item->id_buku)
                    ->update([
                        'dibooking' => $item->total,
                        'stok' => $buku->stok - $item->total,
                    ]);
            }
        }
        $this->command->info('✅ Update dibooking: ' . $dibooking->count() . ' buku');

        // 5. Cek hasil
        $totalStok = Buku::sum('stok');
        $totalDipinjam = Buku::sum('dipinjam');
        $totalDibooking = Buku::sum('dibooking');

        $this->command->info('');
        $this->command->info('📊 HASIL SINKRONISASI:');
        $this->command->info('   Total Buku      : ' . Buku::count());
        $this->command->info('   Total Stok      : ' . $totalStok);
        $this->command->info('   Sedang Dipinjam : ' . $totalDipinjam);
        $this->command->info('   Dibooking       : ' . $totalDibooking);
        $this->command->info('');
        $this->command->info('✅ Sinkronisasi selesai!');
    }
}