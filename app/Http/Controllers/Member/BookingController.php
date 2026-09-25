<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function dataBooking(User $user)
    {
        $data_booking = Booking::with('booking_detail', 'booking_detail.buku')
            ->where('id_user', $user->id)
            ->get();

        if (count($data_booking) == 0) {
            return redirect()->route('member.index')->with('info', 'Tidak ada buku yang dibooking');
        }

        return view('member.data_booking', compact('data_booking'));
    }

    public function bookingPdf(User $user)
{
    $data_booking = Booking::with('booking_detail', 'booking_detail.buku')
        ->where('id_user', $user->id)
        ->get();

    if (count($data_booking) == 0) {
        return redirect()->route('member.index')->with('info', 'Tidak ada buku yang dibooking');
    }

    // Return view yang auto-print
    return view('member.booking_pdf', compact('data_booking'));
}
}