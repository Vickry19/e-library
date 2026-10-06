@extends('member.layout.main')

@section('title', 'Katalog Buku')

@section('content')

{{-- ==================== HERO SECTION ==================== --}}
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="fas fa-book-reader"></i> PERPUSTAKAAN DIGITAL
                </div>
                <h1 class="hero-title">
                    Temukan Buku <span class="text-gradient">Favoritmu</span> di Sini
                </h1>
                <p class="hero-subtitle">
                    Jelajahi ribuan koleksi buku dari berbagai kategori. Booking online,
                    ambil di perpustakaan, dan mulai membaca!
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('member.index') }}" method="GET" class="hero-search">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="search-input"
                               placeholder="Cari judul buku, pengarang, atau penerbit..."
                               value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                {{-- Quick Stats --}}
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">{{ \App\Models\Buku::count() }}</div>
                        <div class="hero-stat-label">Total Buku</div>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">{{ \App\Models\Kategori::count() }}</div>
                        <div class="hero-stat-label">Kategori</div>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">{{ \App\Models\User::where('role_id', 2)->count() }}</div>
                        <div class="hero-stat-label">Member Aktif</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-illustration">
                    <div class="floating-book book-1"><i class="fas fa-book"></i></div>
                    <div class="floating-book book-2"><i class="fas fa-book-open"></i></div>
                    <div class="floating-book book-3"><i class="fas fa-graduation-cap"></i></div>
                    <div class="floating-book book-4"><i class="fas fa-bookmark"></i></div>
                    <div class="hero-circle"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== KATEGORI FILTER ==================== --}}
<div class="container">
    <div class="kategori-filter-section">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
            <div>
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-tags text-primary mr-2"></i>Jelajahi Kategori
                </h5>
                <small class="text-muted">Pilih kategori untuk filter buku</small>
            </div>
            @if(request('kategori') || request('search'))
                <a href="{{ route('member.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="kategori-chips">
    {{-- Semua --}}
    <a href="{{ route('member.index') }}"
       class="kategori-chip {{ !request('kategori') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i> Semua
    </a>

    {{-- Per Kategori --}}
    @foreach(\App\Models\Kategori::withCount('buku')->get() as $kat)
        <a href="{{ route('member.index', ['kategori' => $kat->id]) }}"
           class="kategori-chip {{ request('kategori') == $kat->id ? 'active' : '' }}">
            <i class="fas fa-bookmark"></i>
            {{ $kat->nama_kategori }}
            <span class="chip-count">{{ $kat->buku_count }}</span>
        </a>
    @endforeach
</div>
    </div>
</div>

{{-- ==================== INFO BANNER ==================== --}}
<div class="container">
    <div class="info-banner">
        <div class="info-banner-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-banner-content">
            <strong>Informasi:</strong> Pilih buku yang ingin dipinjam, lalu klik
            <strong>"Booking"</strong> untuk memesan. Ambil buku di perpustakaan dalam 1x24 jam.
        </div>
    </div>
</div>

{{-- ==================== GRID BUKU ==================== --}}
<main class="container py-4">

    {{-- Result Count --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <h5 class="mb-0 font-weight-bold">
                    @if(request('search'))
                        Hasil pencarian: "<span class="text-primary">{{ request('search') }}</span>"
                    @elseif(request('kategori'))
                        Kategori: <span class="text-primary">{{ \App\Models\Kategori::find(request('kategori'))->nama_kategori ?? '-' }}</span>
                    @else
                        Semua Buku
                    @endif
                </h5>
                <span class="badge badge-primary badge-lg">
                    <i class="fas fa-book"></i> {{ $buku->total() }} buku ditemukan
                </span>
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="row">
        @forelse ($buku as $item)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="book-card">

                {{-- Cover --}}
                <div class="book-cover-wrapper">
                    <span class="book-badge-category">
                        {{ $item->kategori->nama_kategori ?? '-' }}
                    </span>
                    <span class="book-badge-stock {{ $item->stok > 0 ? 'available' : 'out' }}">
                        @if($item->stok > 0)
                            <i class="fas fa-check-circle"></i> {{ $item->stok }}
                        @else
                            <i class="fas fa-times-circle"></i> Habis
                        @endif
                    </span>
                    <img src="{{ \App\Helpers\ImageHelper::url($item->image, 'cover-buku') }}"
                         alt="{{ $item->judul_buku }}"
                         onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">

                    {{-- Hover Overlay --}}
                    <div class="book-hover-overlay">
                        <button class="btn-hover-detail" onclick="detailBuku('{{ $item->id }}')">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="book-body">
                    <h5 class="book-title">{{ $item->judul_buku }}</h5>

                    <div class="book-meta">
                        <div class="book-meta-item">
                            <i class="fas fa-user-edit"></i>
                            <span>{{ $item->pengarang }}</span>
                        </div>
                        <div class="book-meta-item">
                            <i class="fas fa-building"></i>
                            <span>{{ $item->penerbit }}</span>
                        </div>
                        <div class="book-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $item->tahun_terbit }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="book-footer">
                    <button class="btn btn-detail-book" onclick="detailBuku('{{ $item->id }}')">
                        <i class="fas fa-eye"></i>
                    </button>
                    @if($item->stok > 0)
                        <form action="{{ route('member.tambahKeranjang') }}" method="POST" class="flex-fill">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-booking-book w-100">
                                <i class="fas fa-cart-plus"></i> Booking
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary flex-fill" disabled>
                            <i class="fas fa-ban"></i> Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="empty-state-title">Buku Tidak Ditemukan</h4>
                <p class="empty-state-text">
                    @if(request('search'))
                        Tidak ada buku dengan kata kunci "<strong>{{ request('search') }}</strong>".
                    @elseif(request('kategori'))
                        Belum ada buku di kategori ini.
                    @else
                        Belum ada buku di katalog.
                    @endif
                </p>
                <a href="{{ route('member.index') }}" class="btn btn-primary btn-modern">
                    <i class="fas fa-redo"></i> Lihat Semua Buku
                </a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
@if($buku->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $buku->appends(request()->query())->links('vendor.pagination.simple-bootstrap-4') }}
    </div>
@endif

</main>

{{-- ==================== MODAL DETAIL BUKU ==================== --}}
<div class="modal fade" id="detailBukuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content detail-modal">

            {{-- Header --}}
            <div class="detail-modal-header">
                <div class="d-flex align-items-center">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">Detail Buku</h5>
                        <small class="opacity-75">Informasi lengkap koleksi</small>
                    </div>
                </div>
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="detail-modal-body">
                <div class="row">

                    {{-- Cover --}}
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="cover-wrapper-detail">
                            <img src="" id="gambar" alt="Cover Buku" class="cover-detail-img">
                            <div class="cover-overlay">
                                <span class="badge-stock-detail" id="stockBadge">
                                    <i class="fas fa-check-circle"></i> Tersedia
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="col-md-8">
                        <h3 class="detail-title" id="judul_buku">Judul Buku</h3>

                        <div class="detail-divider"></div>

                        <div class="detail-grid">

                            {{-- Kategori --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-info-light">
                                    <i class="fas fa-tag text-info"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">Kategori</span>
                                    <span class="detail-value">
                                        <span class="badge badge-info" id="kategori">-</span>
                                    </span>
                                </div>
                            </div>

                            {{-- Pengarang --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-primary-light">
                                    <i class="fas fa-user-edit text-primary"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">Pengarang</span>
                                    <span class="detail-value text-primary font-weight-bold" id="pengarang">-</span>
                                </div>
                            </div>

                            {{-- Penerbit --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-danger-light">
                                    <i class="fas fa-building text-danger"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">Penerbit</span>
                                    <span class="detail-value" id="penerbit">-</span>
                                </div>
                            </div>

                            {{-- Tahun --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-success-light">
                                    <i class="fas fa-calendar-alt text-success"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">Tahun Terbit</span>
                                    <span class="detail-value" id="tahun_terbit">-</span>
                                </div>
                            </div>

                            {{-- ISBN --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-secondary-light">
                                    <i class="fas fa-barcode text-secondary"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">ISBN</span>
                                    <span class="detail-value" id="isbn">-</span>
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="detail-item">
                                <div class="detail-item-icon bg-warning-light">
                                    <i class="fas fa-cubes text-warning"></i>
                                </div>
                                <div class="detail-item-content">
                                    <span class="detail-label">Stok Tersedia</span>
                                    <span class="detail-value">
                                        <span class="stock-pill" id="stok">0</span>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="detail-modal-footer">
                <button type="button" class="btn btn-light btn-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <form action="{{ route('member.tambahKeranjang') }}" method="POST" id="formTambahKeranjang" class="d-inline">
                    @csrf
                    <input type="hidden" name="id" id="id_buku_keranjang">
                    <button type="submit" class="btn btn-success btn-modern" id="tambahKeranjang">
                        <i class="fas fa-shopping-cart"></i> Booking Sekarang
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- ==================== MODAL LOGIN ==================== --}}
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('loginMember') }}" method="POST">
            @csrf
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title"><i class="fas fa-sign-in-alt mr-2"></i>Login Member</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label><i class="fas fa-envelope mr-1"></i> Email</label>
                        <input type="email" class="form-control form-control-lg" name="email"
                               required placeholder="Masukkan email">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock mr-1"></i> Password</label>
                        <input type="password" class="form-control form-control-lg" name="password"
                               required placeholder="Masukkan password">
                    </div>
                </div>
                <div class="modal-footer justify-content-between bg-light">
                    <p class="mb-0 small">Belum punya akun?
                        <a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="daftarModal();">
                            Daftar
                        </a>
                    </p>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL DAFTAR ==================== --}}
<div class="modal fade" id="daftarMemberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form id="daftarMemberForm">
            @csrf
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Daftar Member</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1"></i> Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" id="nama"
                                       placeholder="Nama lengkap">
                                <span class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-envelope mr-1"></i> Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                       placeholder="Email">
                                <span class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt mr-1"></i> Alamat</label>
                                <input type="text" name="alamat" class="form-control" id="alamat"
                                       placeholder="Alamat">
                                <span class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock mr-1"></i> Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                       placeholder="Min. 8 karakter">
                                <span class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock mr-1"></i> Konfirmasi Password</label>
                                <input type="password" name="konfirmasi_password" class="form-control"
                                       id="konfirmasi_password" placeholder="Konfirmasi Password">
                                <span class="invalid-feedback" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between bg-light">
                    <p class="mb-0 small">Sudah punya akun?
                        <a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="loginModal();">
                            Login
                        </a>
                    </p>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==================== DETAIL BUKU ====================
    function detailBuku(id) {
        $.ajax({
            url: '{{ url("/") }}/detail-buku/' + id,
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                $('#gambar').attr('src', APP_URL + '/storage/' + data.image);
                $('#judul_buku').html(data.judul_buku);
                $('#kategori').html(data.kategori.nama_kategori);
                $('#pengarang').html(data.pengarang);
                $('#penerbit').html(data.penerbit);
                $('#tahun_terbit').html(data.tahun_terbit);
                $('#isbn').html(data.isbn);
                $('#stok').html(data.stok + ' buku');
                $('#id_buku_keranjang').val(data.id);

                if (data.stok > 0) {
                    $('#stockBadge')
                        .removeClass('out-of-stock')
                        .html('<i class="fas fa-check-circle"></i> Tersedia');
                    $('#tambahKeranjang').removeClass('d-none');
                } else {
                    $('#stockBadge')
                        .addClass('out-of-stock')
                        .html('<i class="fas fa-times-circle"></i> Habis');
                    $('#tambahKeranjang').addClass('d-none');
                }

                $('#detailBukuModal').modal('show');
            },
            error: function() {
                toastr.error('Gagal memuat detail buku.');
            }
        });
    }

    // ==================== MODAL LOGIN / DAFTAR ====================
    function daftarModal() {
        $('#daftarMemberModal').modal('show');
        $('#loginModal').modal('hide');
    }

    function loginModal() {
        $('#daftarMemberModal').modal('hide');
        $('#loginModal').modal('show');
    }

    // ==================== REGISTER MEMBER ====================
    $('#daftarMemberForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('registerMember') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.success);
                    $('#daftarMemberModal').modal('hide');
                    $('#daftarMemberForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').empty();
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).next('.invalid-feedback').html(value[0]);
                    });
                }
            }
        });
    });
</script>
@endpush