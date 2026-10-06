@extends('admin.layout.main')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- ==================== WELCOME BANNER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="welcome-banner">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        @php
                            $avatar = \App\Helpers\AvatarHelper::generate(Auth::user()->nama);
                        @endphp
                        <div class="avatar-initial welcome-avatar"
                             style="background-color: {{ $avatar['color'] }};">
                            {{ $avatar['initial'] }}
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold text-white">
                                Selamat datang, {{ Auth::user()->nama }}! 👋
                            </h3>
                            <small class="text-white opacity-75">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                                &nbsp;|&nbsp;
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::now()->format('H:i') }} WIB
                            </small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-light badge-lg">
                            <i class="fas fa-user-shield"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== STATISTIK UTAMA ==================== --}}
    <div class="row mb-3">
        {{-- Total Buku --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-bg"></div>
                <div class="stat-card-content">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-info">
                        <span class="stat-card-label">Total Buku</span>
                        <span class="stat-card-value">{{ $totalBuku ?? \App\Models\Buku::count() }}</span>
                        <span class="stat-card-desc">
                            <i class="fas fa-cubes"></i>
                            {{ $totalStok ?? \App\Models\Buku::sum('stok') }} stok tersedia
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.master.buku.index') }}" class="stat-card-footer">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Total Kategori --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="stat-card stat-card-success">
                <div class="stat-card-bg"></div>
                <div class="stat-card-content">
                    <div class="stat-card-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-card-info">
                        <span class="stat-card-label">Total Kategori</span>
                        <span class="stat-card-value">{{ $totalKategori ?? \App\Models\Kategori::count() }}</span>
                        <span class="stat-card-desc">
                            <i class="fas fa-bookmark"></i>
                            Klasifikasi buku
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.master.kategori.index') }}" class="stat-card-footer">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Total Member --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-bg"></div>
                <div class="stat-card-content">
                    <div class="stat-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-card-info">
                        <span class="stat-card-label">Total Member</span>
                        <span class="stat-card-value">{{ $totalMember ?? \App\Models\User::where('role_id', 2)->count() }}</span>
                        <span class="stat-card-desc">
                            <i class="fas fa-user-check"></i>
                            {{ $totalMemberAktif ?? \App\Models\User::where('role_id', 2)->where('is_active', 1)->count() }} aktif
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.master.user.index') }}" class="stat-card-footer">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Booking Aktif --}}
        <div class="col-lg-3 col-md-6 col-12">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-bg"></div>
                <div class="stat-card-content">
                    <div class="stat-card-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-card-info">
                        <span class="stat-card-label">Booking Aktif</span>
                        <span class="stat-card-value">{{ $bookingAktif ?? \App\Models\Booking::where('batas_ambil', '>', now())->count() }}</span>
                        <span class="stat-card-desc">
                            <i class="fas fa-clock"></i>
                            Menunggu diambil
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.transaksi.booking.index') }}" class="stat-card-footer">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ==================== STATISTIK TRANSAKSI ==================== --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-warning">
                <div class="mini-stat-icon">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Sedang Dipinjam</span>
                    <span class="mini-stat-value">{{ $totalDipinjam ?? \App\Models\PinjamDetail::where('status', 'Pinjam')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Dikembalikan</span>
                    <span class="mini-stat-value">{{ $totalDikembalikan ?? \App\Models\PinjamDetail::where('status', 'Kembali')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-info">
                <div class="mini-stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Transaksi Hari Ini</span>
                    <span class="mini-stat-value">{{ $transaksiHariIni ?? \App\Models\Pinjam::whereDate('tgl_pinjam', today())->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-danger">
                <div class="mini-stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Denda</span>
                    <span class="mini-stat-value" style="font-size: 1rem;">
                        Rp {{ number_format($totalDenda ?? \App\Models\Pinjam::sum('total_denda'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== CHART & TOP BUKU ==================== --}}
    <div class="row">
        {{-- Chart --}}
        <div class="col-lg-8 mb-3">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line text-primary mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Statistik Peminjaman</h5>
                        </div>
                        <span class="badge badge-primary badge-lg">
                            <i class="fas fa-calendar"></i> 7 Hari Terakhir
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartPeminjaman" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Buku --}}
        <div class="col-lg-4 mb-3">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-fire text-danger mr-2"></i>
                        <h5 class="mb-0 font-weight-bold">Top 5 Buku</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="top-book-list">
                        @php
                            $topBuku = \App\Models\Buku::orderBy('dipinjam', 'DESC')->take(5)->get();
                        @endphp
                        @forelse($topBuku as $index => $buku)
                        <li class="top-book-item">
                            <div class="top-book-rank rank-{{ $index + 1 }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="top-book-info">
                                <span class="top-book-title">{{ \Illuminate\Support\Str::limit($buku->judul_buku, 25) }}</span>
                                <span class="top-book-meta">
                                    <i class="fas fa-book-reader text-warning"></i>
                                    {{ $buku->dipinjam }} kali dipinjam
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-muted py-4">
                            Belum ada data
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== TABEL AKTIVITAS ==================== --}}
    <div class="row">
        {{-- Buku Terbaru --}}
        <div class="col-lg-6 mb-3">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book-open text-info mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Buku Terbaru</h5>
                        </div>
                        <a href="{{ route('admin.master.buku.index') }}" class="btn btn-sm btn-modern-sm btn-light-modern">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60">Cover</th>
                                    <th>Judul</th>
                                    <th width="100">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Buku::with('kategori')->latest()->take(5)->get() as $buku)
                                <tr>
                                    <td>
                                        <img src="{{ \App\Helpers\ImageHelper::url($buku->image, 'cover-buku') }}"
                                             style="width: 40px; height: 55px; object-fit: cover; border-radius: 4px;"
                                             onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                    </td>
                                    <td>
                                        <strong>{{ \Illuminate\Support\Str::limit($buku->judul_buku, 30) }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> {{ $buku->kategori->nama_kategori ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($buku->stok > 0)
                                            <span class="badge badge-success">{{ $buku->stok }} tersedia</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada buku</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Terbaru --}}
        <div class="col-lg-6 mb-3">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-receipt text-warning mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Booking Terbaru</h5>
                        </div>
                        <a href="{{ route('admin.transaksi.booking.index') }}" class="btn btn-sm btn-modern-sm btn-light-modern">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Booking</th>
                                    <th>Anggota</th>
                                    <th width="120">Batas Ambil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Booking::with('anggota')->latest()->take(5)->get() as $booking)
                                @php
                                    $batas = \Carbon\Carbon::parse($booking->batas_ambil);
                                    $isExpired = \Carbon\Carbon::now()->gt($batas);
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary badge-lg">
                                            {{ $booking->id_booking }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $av = \App\Helpers\AvatarHelper::generate($booking->anggota->nama ?? 'User');
                                            @endphp
                                            <div class="avatar-initial mr-2"
                                                 style="width: 30px; height: 30px; background-color: {{ $av['color'] }}; font-size: 11px;">
                                                {{ $av['initial'] }}
                                            </div>
                                            <span>{{ $booking->anggota->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($isExpired)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Expired
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-clock"></i>
                                                {{ $batas->diffForHumans() }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada booking</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== QUICK ACTIONS ==================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bolt text-warning mr-2"></i>
                        <h5 class="mb-0 font-weight-bold">Aksi Cepat</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12 mb-2">
                            <a href="{{ route('admin.master.buku.create') }}" class="quick-action quick-action-primary">
                                <i class="fas fa-plus-circle"></i>
                                <span>Tambah Buku</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-2">
                            <a href="{{ route('admin.master.kategori.index') }}" class="quick-action quick-action-success">
                                <i class="fas fa-tags"></i>
                                <span>Kelola Kategori</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-2">
                            <a href="{{ route('admin.master.user.index') }}" class="quick-action quick-action-warning">
                                <i class="fas fa-users"></i>
                                <span>Kelola User</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-2">
                            <a href="{{ route('admin.transaksi.peminjaman.index') }}" class="quick-action quick-action-info">
                                <i class="fas fa-book-reader"></i>
                                <span>Lihat Peminjaman</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    $(document).ready(function() {
        // ==================== CHART PEMINJAMAN ====================
        const ctx = document.getElementById('chartPeminjaman').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.5)');
        gradient.addColorStop(1, 'rgba(0, 123, 255, 0.05)');

        @php
            $labels = [];
            $dataPeminjaman = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $labels[] = $date->translatedFormat('D, d M');
                $dataPeminjaman[] = \App\Models\Pinjam::whereDate('tgl_pinjam', $date)->count();
            }
        @endphp

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Peminjaman',
                    data: {!! json_encode($dataPeminjaman) !!},
                    borderColor: '#007bff',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#007bff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#212529',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '📚 ' + context.parsed.y + ' peminjaman';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, color: '#6c757d' },
                        grid: { color: '#f1f3f5', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#6c757d' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush