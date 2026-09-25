@extends('member.layout.main')

@section('title', 'Sedang Pinjam')
@section('hide-page-header') @endsection

@section('content')

{{-- ==================== HERO ==================== --}}
<div class="hero-section-compact hero-section-info">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="hero-icon-compact">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="ml-3">
                    <h2 class="mb-0 font-weight-bold text-white">Sedang Dipinjam</h2>
                    <small class="text-white opacity-75">
                        Buku yang sedang kamu pinjam
                    </small>
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge badge-light badge-lg">
                    <i class="fas fa-book"></i>
                    {{ $sedang_pinjam->sum(fn($p) => $p->pinjam_detail->count()) }} buku
                </span>
            </div>
        </div>
    </div>
</div>

<main class="container py-4">

    @if($sedang_pinjam->isEmpty())

        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h4 class="empty-state-title">Tidak Ada Buku Dipinjam</h4>
            <p class="empty-state-text">Kamu belum meminjam buku apapun saat ini.</p>
            <a href="{{ route('member.index') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-search"></i> Cari Buku
            </a>
        </div>

    @else

        @php
            $totalDipinjam = $sedang_pinjam->sum(fn($p) => $p->pinjam_detail->count());

            $totalTerlambat = $sedang_pinjam->sum(function($p) {
                return $p->pinjam_detail->filter(function($d) {
                    return \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($d->tgl_kembali));
                })->count();
            });

            $totalDenda = $sedang_pinjam->sum(function($p) {
                $total = 0;
                foreach ($p->pinjam_detail as $d) {
                    $tglKembali = \Carbon\Carbon::parse($d->tgl_kembali);
                    $today = \Carbon\Carbon::now();

                    if ($today->gt($tglKembali)) {
                        // Sudah lewat: hitung hari terlambat
                        $hariTerlambat = (int) $tglKembali->diffInDays($today);
                        $total += $hariTerlambat * $d->denda;
                    }
                }
                return $total;
            });
        @endphp

        {{-- Statistik --}}
        <div class="row mb-3">
            <div class="col-lg-4 col-md-6 col-6 mb-2">
                <div class="mini-stat-card mini-stat-info">
                    <div class="mini-stat-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="mini-stat-content">
                        <span class="mini-stat-label">Total Dipinjam</span>
                        <span class="mini-stat-value">{{ $totalDipinjam }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-6 mb-2">
                <div class="mini-stat-card mini-stat-danger">
                    <div class="mini-stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="mini-stat-content">
                        <span class="mini-stat-label">Terlambat</span>
                        <span class="mini-stat-value">{{ $totalTerlambat }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 mb-2">
                <div class="mini-stat-card mini-stat-warning">
                    <div class="mini-stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="mini-stat-content">
                        <span class="mini-stat-label">Estimasi Denda</span>
                        <span class="mini-stat-value" style="font-size: 1.1rem;">
                            Rp {{ number_format($totalDenda, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Banner jika ada yang terlambat --}}
        @if($totalTerlambat > 0)
        <div class="info-banner info-banner-warning">
            <div class="info-banner-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="info-banner-content">
                <strong>Perhatian!</strong> Kamu memiliki
                <strong>{{ $totalTerlambat }} buku yang terlambat dikembalikan</strong>.
                Segera kembalikan ke perpustakaan untuk menghindari denda yang lebih besar.
            </div>
        </div>
        @endif

        {{-- Tabel --}}
        <div class="card card-modern">
            <div class="card-header card-header-modern">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list-ul text-info mr-2"></i>
                    <h5 class="mb-0 font-weight-bold">Daftar Buku Dipinjam</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table-modern-wrapper">
                    <table id="sedang-pinjam" class="table table-modern">
                        <thead>
                            <tr>
                                <th width="4%">#</th>
                                <th>No. Pinjam</th>
                                <th>Tgl. Pinjam</th>
                                <th>Tgl. Kembali</th>
                                <th>Lama</th>
                                <th>Judul Buku</th>
                                <th>Status</th>
                                <th>Denda/Hari</th>
                                <th width="10%">Gambar</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sedang_pinjam as $pinjam)
                                @foreach ($pinjam->pinjam_detail as $detail)
                                @php
                                    $tglKembali = \Carbon\Carbon::parse($detail->tgl_kembali);
                                    $today = \Carbon\Carbon::now();

                                    // Cek apakah sudah terlambat
                                    $isLate = $today->gt($tglKembali);

                                    if ($isLate) {
                                        // Sudah lewat: hitung hari terlambat (positif)
                                        $selisihHari = (int) $tglKembali->diffInDays($today);
                                    } else {
                                        // Belum lewat: hitung sisa hari (positif)
                                        $selisihHari = (int) $today->diffInDays($tglKembali);
                                    }
                                @endphp
                                <tr class="{{ $isLate ? 'tr-danger' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="fas fa-hashtag"></i> {{ $pinjam->no_pinjam }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-plus text-muted mr-1"></i>
                                        {{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        <span class="{{ $isLate ? 'text-danger font-weight-bold' : 'text-success' }}">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $tglKembali->format('d-m-Y') }}
                                        </span>
                                        <br>
                                        @if($isLate)
                                            <small class="badge badge-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Terlambat {{ $selisihHari }} hari
                                            </small>
                                        @else
                                            <small class="badge badge-success">
                                                <i class="fas fa-clock"></i>
                                                Sisa {{ $selisihHari }} hari
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info badge-lg">
                                            <i class="fas fa-clock"></i> {{ $detail->lama_pinjam }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-book text-primary mr-1"></i>
                                        <strong>{{ $detail->buku->judul_buku }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i>
                                            {{ $detail->buku->kategori->nama_kategori ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($detail->status == 'Pinjam')
                                            <span class="badge badge-info badge-lg">
                                                <i class="fas fa-book-reader"></i> Dipinjam
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check-circle"></i> Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success font-weight-bold">
                                            Rp {{ number_format($detail->denda, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ \App\Helpers\ImageHelper::url($detail->buku->image, 'cover-buku') }}"
                                             style="width: 55px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                             onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-user text-muted"></i>
                                            <b>Pinjam:</b> {{ $pinjam->petugas_pinjam->nama ?? '-' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#sedang-pinjam tbody tr').length > 0) {
            $('#sedang-pinjam').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Cari buku...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: { first: "«", last: "»", next: "›", previous: "‹" }
                }
            });
        }
    });
</script>
@endpush