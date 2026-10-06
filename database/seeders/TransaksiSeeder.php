<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Buku;
use App\Models\Booking;
use App\Models\Pinjam;
use Illuminate\Database\Seeder;
use App\Models\BookingDetail;
use App\Models\PinjamDetail;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::where('role_id', 2)->get();
        $bukus = Buku::all();

        // ==================== 30 BOOKING ====================
        for ($i = 1; $i <= 30; $i++) {
            $member = $members->random();
            $buku = $bukus->random();
            
            $idBooking = 'B' . now()->format('ymd') . str_pad($i, 3, '0', STR_PAD_LEFT);
            $tglBooking = Carbon::now()->subDays(rand(0, 30));
            
            Booking::create([
                'id_booking' => $idBooking,
                'tgl_booking' => $tglBooking,
                'batas_ambil' => $tglBooking->copy()->addDay(),
                'id_user' => $member->id,
            ]);

            BookingDetail::create([
                'id_booking' => $idBooking,
                'id_buku' => $buku->id,
            ]);
        }

        // ==================== 50 PEMINJAMAN ====================
        for ($i = 1; $i <= 50; $i++) {
            $member = $members->random();
            $buku = $bukus->random();
            
            $noPinjam = 'P' . now()->format('ymd') . str_pad($i, 3, '0', STR_PAD_LEFT);
            $tglPinjam = Carbon::now()->subDays(rand(1, 60));
            $lama = rand(3, 14);
            $tglKembali = $tglPinjam->copy()->addDays($lama);

            // 70% sudah dikembalikan
            $status = rand(1, 10) <= 7 ? 'Kembali' : 'Pinjam';
            
            Pinjam::create([
                'no_pinjam' => $noPinjam,
                'tgl_pinjam' => $tglPinjam,
                'id_booking' => 'B' . $tglPinjam->format('ymd') . str_pad($i, 3, '0', STR_PAD_LEFT),
                'id_user' => $member->id,
                'total_denda' => 0,
                'id_petugas_pinjam' => 1,
            ]);

            $tglPengembalian = null;
            if ($status === 'Kembali') {
                $tglPengembalian = $tglKembali->copy()->subDays(rand(-3, 3));
            }

            PinjamDetail::create([
                'no_pinjam' => $noPinjam,
                'id_buku' => $buku->id,
                'tgl_kembali' => $tglKembali,
                'tgl_pengembalian' => $tglPengembalian,
                'status' => $status,
                'denda' => rand(1, 10) * 2000,
                'lama_pinjam' => $lama,
                'id_petugas_kembali' => $status === 'Kembali' ? 1 : null,
            ]);
        }

        $this->command->info('✅ Transaksi created: 30 booking + 50 peminjaman');
    }
}