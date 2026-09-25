@extends('member.layout.main')

@section('title', 'Data Booking')
@section('hide-page-header') @endsection

@section('content')

{{-- ==================== HERO ==================== --}}
<div class="hero-section-compact hero-section-warning">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="hero-icon-compact">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="ml-3">
                    <h2 class="mb-0 font-weight-bold text-white">Data Booking</h2>
                    <small class="text-white opacity-75">
                        Booking aktif kamu
                    </small>
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('member.bookingPdf', auth()->user()->id) }}"
                   class="btn btn-light btn-modern-sm" target="_blank">
                    <i class="fas fa-print"></i> Cetak Bukti
                </a>
            </div>
        </div>
    </div>
</div>

<main class="container py-4">

    {{-- ==================== INFO BANNER ==================== --}}
    <div class="info-banner info-banner-warning">
        <div class="info-banner-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="info-banner-content">
            <strong>Penting!</strong> Waktu pengambilan buku hanya <strong>1x24 jam</strong> dari booking.
            Jika tidak diambil, booking akan <strong>dibatalkan otomatis</strong> oleh sistem.
        </div>
    </div>

    {{-- ==================== INFO CARD ==================== --}}
    <div class="booking-info-card">
        <div class="row">
            {{-- ID Booking --}}
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="booking-info-item">
                    <div class="booking-info-icon bg-primary-light">
                        <i class="fas fa-hashtag text-primary"></i>
                    </div>
                    <div class="booking-info-content">
                        <span class="booking-info-label">ID Booking</span>
                        <span class="booking-info-value">{{ $data_booking[0]->id_booking }}</span>
                    </div>
                </div>
            </div>

            {{-- Tanggal Booking --}}
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="booking-info-item">
                    <div class="booking-info-icon bg-info-light">
                        <i class="fas fa-calendar-plus text-info"></i>
                    </div>
                    <div class="booking-info-content">
                        <span class="booking-info-label">Tanggal Booking</span>
                        <span class="booking-info-value">
                            {{ \Carbon\Carbon::parse($data_booking[0]->tgl_booking)->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Batas Ambil --}}
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="booking-info-item">
                    <div class="booking-info-icon bg-danger-light">
                        <i class="fas fa-hourglass-end text-danger"></i>
                    </div>
                    <div class="booking-info-content">
                        <span class="booking-info-label">Batas Ambil</span>
                        <span class="booking-info-value text-danger">
                            {{ \Carbon\Carbon::parse($data_booking[0]->batas_ambil)->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Sisa Waktu --}}
            <div class="col-lg-3 col-md-6 mb-3">
    @php
        $batas = \Carbon\Carbon::parse($data_booking[0]->batas_ambil);
        $now = \Carbon\Carbon::now();

        // Cek apakah sudah lewat batas
        $isExpired = $now->gt($batas);

        if ($isExpired) {
            // Sudah lewat: hitung selisih, pastikan positif
            $selisihDetik = $batas->diffInSeconds($now);  // positif
            $display = 'Expired';
        } else {
            // Belum lewat: hitung selisih
            $selisihDetik = $now->diffInSeconds($batas);  // positif

            $selisihHari = (int) floor($selisihDetik / 86400);
            $selisihJam  = (int) floor(($selisihDetik % 86400) / 3600);
            $selisihMenit = (int) floor(($selisihDetik % 3600) / 60);

            if ($selisihHari > 0) {
                $display = $selisihHari . ' hari ' . $selisihJam . ' jam lagi';
            } elseif ($selisihJam > 0) {
                $display = $selisihJam . ' jam ' . $selisihMenit . ' menit lagi';
            } else {
                $display = $selisihMenit . ' menit lagi';
            }
        }
    @endphp
    <div class="booking-info-item">
        <div class="booking-info-icon {{ $isExpired ? 'bg-danger-light' : 'bg-success-light' }}">
            <i class="fas fa-clock {{ $isExpired ? 'text-danger' : 'text-success' }}"></i>
        </div>
        <div class="booking-info-content">
            <span class="booking-info-label">Sisa Waktu</span>
            <span class="booking-info-value {{ $isExpired ? 'text-danger' : 'text-success' }}">
                {{ $display }}
            </span>
        </div>
    </div>
</div>
        </div>
    </div>

    {{-- ==================== TABEL BUKU ==================== --}}
    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-book text-warning mr-2"></i>
                    <h5 class="mb-0 font-weight-bold">Buku yang Dibooking</h5>
                </div>
                <span class="badge badge-warning badge-lg">
                    {{ $data_booking->sum(fn($b) => $b->booking_detail->count()) }} buku
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-modern-wrapper">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th>Judul Buku</th>
                            <th>Kategori</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th width="6%">Tahun</th>
                            <th width="10%">Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_booking as $booking)
                            @foreach ($booking->booking_detail as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <i class="fas fa-book text-primary mr-1"></i>
                                    <strong>{{ $detail->buku->judul_buku }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info badge-lg">
                                        {{ $detail->buku->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-user-edit text-muted mr-1"></i>
                                    {{ $detail->buku->pengarang }}
                                </td>
                                <td>
                                    <i class="fas fa-building text-muted mr-1"></i>
                                    {{ $detail->buku->penerbit }}
                                </td>
                                <td>{{ $detail->buku->tahun_terbit }}</td>
                                <td class="text-center">
                                    <img src="{{ \App\Helpers\ImageHelper::url($detail->buku->image, 'cover-buku') }}"
                                         style="width: 55px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                         onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
@endsection