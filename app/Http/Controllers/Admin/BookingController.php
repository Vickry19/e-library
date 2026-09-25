<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::with('anggota')->get();
        return view('admin.booking.index', compact('booking'));
    }

    public function show(string $id)
    {
        // Gunakan findOrFail agar otomatis 404 jika tidak ditemukan
        $booking = Booking::with('booking_detail', 'booking_detail.buku', 'anggota')
            ->findOrFail($id);

        return view('admin.booking.show', compact('booking'));
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'Data booking berhasil dihapus!');
    }
}