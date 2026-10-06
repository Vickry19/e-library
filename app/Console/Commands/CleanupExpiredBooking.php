<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Buku;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredBooking extends Command
{
    protected $signature = 'booking:cleanup';
    protected $description = 'Hapus booking yang expired > 1 hari';

    public function handle()
    {
        $expired = Booking::where('batas_ambil', '<', Carbon::now()->subDay())->get();
        $count = 0;

        DB::transaction(function () use ($expired, &$count) {
            foreach ($expired as $booking) {
                foreach ($booking->booking_detail as $detail) {
                    Buku::where('id', $detail->id_buku)->update([
                        'stok' => DB::raw('stok + 1'),
                        'dibooking' => DB::raw('dibooking - 1'),
                    ]);
                }
                $booking->delete();
                $count++;
            }
        });

        $this->info("✅ {$count} booking expired berhasil dihapus.");
    }
}