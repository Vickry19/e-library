@extends('admin.layout.main')

@section('title', 'Transaksi Booking')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-booking">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Transaksi Booking</h3>
                            <small class="opacity-75">Kelola buku yang sedang dibooking anggota</small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-light badge-lg">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== STATISTIK MINI ==================== --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-primary">
                <div class="mini-stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Booking</span>
                    <span class="mini-stat-value">{{ $booking->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Booking Aktif</span>
                    <span class="mini-stat-value">
                        {{ $booking->filter(fn($b) => \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($b->batas_ambil)))->count() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-danger">
                <div class="mini-stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Expired</span>
                    <span class="mini-stat-value">
                        {{ $booking->filter(fn($b) => \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($b->batas_ambil)))->count() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-info">
                <div class="mini-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Anggota Aktif</span>
                    <span class="mini-stat-value">
                        {{ $booking->pluck('anggota.id')->unique()->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CARD ==================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">

                {{-- Header --}}
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-ul text-primary mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Daftar Booking Aktif</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Info Alert --}}
                    <div class="alert alert-info border-0 shadow-sm" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi:</strong> Booking harus diambil dalam <strong>1x24 jam</strong>.
                        Jika tidak diambil, booking akan dibatalkan otomatis oleh sistem.
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive table-modern-wrapper">
                        <table id="booking-table" class="table table-modern">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID Booking</th>
                                    <th>Tgl. Booking</th>
                                    <th>Batas Ambil</th>
                                    <th>Anggota</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($booking as $item)
                                @php
                                    $batas = \Carbon\Carbon::parse($item->batas_ambil);
                                    $now = \Carbon\Carbon::now();

                                    // Cek apakah sudah lewat batas
                                    $isExpired = $now->gt($batas);

                                    if ($isExpired) {
                                        // Sudah lewat: hitung selisih dari batas ke now (positif)
                                        $selisihDetik = $batas->diffInSeconds($now);
                                    } else {
                                        // Belum lewat: hitung selisih dari now ke batas (positif)
                                        $selisihDetik = $now->diffInSeconds($batas);
                                    }

                                    // Konversi ke hari/jam/menit
                                    $selisihHari  = (int) floor($selisihDetik / 86400);
                                    $selisihJam   = (int) floor(($selisihDetik % 86400) / 3600);
                                    $selisihMenit = (int) floor(($selisihDetik % 3600) / 60);
                                @endphp
                                <tr class="{{ $isExpired ? 'tr-danger' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="fas fa-hashtag"></i> {{ $item->id_booking }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->tgl_booking)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        @if($isExpired)
                                            <span class="text-danger font-weight-bold">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $batas->format('d-m-Y H:i') }}
                                            </span>
                                            <br>
                                            <small class="badge badge-danger">
                                                @if($selisihHari > 0)
                                                    Expired {{ $selisihHari }} hari {{ $selisihJam }} jam lalu
                                                @elseif($selisihJam > 0)
                                                    Expired {{ $selisihJam }} jam {{ $selisihMenit }} menit lalu
                                                @else
                                                    Expired {{ $selisihMenit }} menit lalu
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-success font-weight-bold">
                                                <i class="fas fa-clock"></i>
                                                {{ $batas->format('d-m-Y H:i') }}
                                            </span>
                                            <br>
                                            <small class="badge badge-warning">
                                                @if($selisihHari > 0)
                                                    Sisa {{ $selisihHari }} hari {{ $selisihJam }} jam
                                                @elseif($selisihJam > 0)
                                                    Sisa {{ $selisihJam }} jam {{ $selisihMenit }} menit
                                                @else
                                                    Sisa {{ $selisihMenit }} menit
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $avatar = \App\Helpers\AvatarHelper::generate($item->anggota->nama ?? 'User');
                                            @endphp
                                            <div class="avatar-initial mr-2"
                                                 style="width: 35px; height: 35px; background-color: {{ $avatar['color'] }}; font-size: 13px;">
                                                {{ $avatar['initial'] }}
                                            </div>
                                            <span>{{ $item->anggota->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($isExpired)
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-times-circle"></i> Expired
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check-circle"></i> Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.transaksi.booking.show', $item->id) }}"
                                               class="btn btn-sm btn-primary btn-modern-sm"
                                               data-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-modern-sm hapus-data"
                                                    data-toggle="tooltip" title="Hapus"
                                                    data-id="{{ $item->id }}"
                                                    data-booking="{{ $item->id_booking }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form action="{{ route('admin.transaksi.booking.destroy', $item->id) }}"
                                                  method="POST" id="form-hapus-{{ $item->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center py-5">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                            <h6 class="text-muted">Tidak ada booking saat ini</h6>
                                            <small class="text-muted">Booking akan muncul di sini ketika member melakukan booking buku.</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // Inisialisasi DataTable (jika ada data)
        if ($('#booking-table tbody tr').length > 0 && !$('#booking-table tbody tr td[colspan]').length) {
            $('#booking-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        first: "«",
                        last: "»",
                        next: "›",
                        previous: "‹"
                    }
                }
            });
        }

        $('[data-toggle="tooltip"]').tooltip();

        // Hapus booking dengan SweetAlert
        $(document).on('click', '.hapus-data', function() {
            const id = $(this).data('id');
            const idBooking = $(this).data('booking');

            SwalConfirm(
                'Hapus Booking?',
                `Booking ${idBooking} akan dihapus permanen. Lanjutkan?`,
                function() {
                    $(`#form-hapus-${id}`).submit();
                }
            );
        });

    });
</script>
@endpush