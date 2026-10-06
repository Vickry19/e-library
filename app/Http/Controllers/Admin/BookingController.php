<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::with('anggota')->latest()->get();
        return view('admin.booking.index', compact('booking'));
    }

    public function show(string $id)
    {
        $booking = Booking::with('booking_detail', 'booking_detail.buku', 'anggota')
            ->findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::findOrFail($id);
            
            // Kembalikan stok buku
            foreach ($booking->booking_detail as $detail) {
                Buku::where('id', $detail->id_buku)->update([
                    'stok' => DB::raw('stok + 1'),
                    'dibooking' => DB::raw('dibooking - 1'),
                ]);
            }
            
            $booking->delete();
        });

        return redirect()->back()->with('success', 'Booking berhasil dihapus!');
    }

    // ==================== CANCEL BOOKING ====================
    public function cancel($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::with('booking_detail')->findOrFail($id);
            
            foreach ($booking->booking_detail as $detail) {
                Buku::where('id', $detail->id_buku)->update([
                    'stok' => DB::raw('stok + 1'),
                    'dibooking' => DB::raw('dibooking - 1'),
                ]);
            }
            
            $booking->delete();
        });

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan!');
    }

    // ==================== BULK DELETE ====================
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada booking yang dipilih!');
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $booking = Booking::with('booking_detail')->find($id);
                if ($booking) {
                    foreach ($booking->booking_detail as $detail) {
                        Buku::where('id', $detail->id_buku)->update([
                            'stok' => DB::raw('stok + 1'),
                            'dibooking' => DB::raw('dibooking - 1'),
                        ]);
                    }
                    $booking->delete();
                }
            }
        });

        return redirect()->back()->with('success', count($ids) . ' booking berhasil dihapus!');
    }

    // ==================== CLEANUP EXPIRED ====================
    public function cleanupExpired()
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

        return redirect()->back()->with('success', $count . ' booking expired berhasil dibersihkan!');
    }
}