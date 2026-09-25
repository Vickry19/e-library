@extends('admin.layout.main')

@section('title', 'Detail Buku')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-buku">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Detail Buku</h3>
                            <small class="opacity-75">Informasi lengkap koleksi</small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="{{ route('admin.master.buku.index') }}" class="btn btn-light btn-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== DETAIL CARD ==================== --}}
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card card-modern">

                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book text-primary mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">{{ $buku->judul_buku }}</h5>
                        </div>
                        <span class="badge badge-info badge-lg">
                            {{ $buku->kategori->nama_kategori }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">

                    <div class="row">

                        {{-- Cover --}}
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <div class="detail-cover-wrapper">
                                <img src="{{ \App\Helpers\ImageHelper::url($buku->image, 'cover-buku') }}"
                                     alt="{{ $buku->judul_buku }}"
                                     class="detail-cover-img"
                                     onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                            </div>

                            {{-- Stock Badge --}}
                            <div class="text-center mt-3">
                                @if($buku->stok > 0)
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check-circle"></i>
                                        {{ $buku->stok }} buku tersedia
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-times-circle"></i>
                                        Stok habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="col-lg-8">

                            <h3 class="detail-title-book">{{ $buku->judul_buku }}</h3>
                            <div class="detail-divider-book"></div>

                            <div class="row">
                                {{-- Kiri --}}
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-icon bg-primary-light">
                                            <i class="fas fa-user-edit text-primary"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Pengarang</span>
                                            <span class="info-value">{{ $buku->pengarang }}</span>
                                        </div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-icon bg-danger-light">
                                            <i class="fas fa-building text-danger"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Penerbit</span>
                                            <span class="info-value">{{ $buku->penerbit }}</span>
                                        </div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-icon bg-success-light">
                                            <i class="fas fa-calendar-alt text-success"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Tahun Terbit</span>
                                            <span class="info-value">{{ $buku->tahun_terbit }}</span>
                                        </div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-icon bg-secondary-light">
                                            <i class="fas fa-barcode text-secondary"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">ISBN</span>
                                            <span class="info-value">{{ $buku->isbn }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kanan --}}
                                <div class="col-md-6">
                                    <div class="stock-info-card">
                                        <div class="stock-info-item">
                                            <span class="stock-info-label">
                                                <i class="fas fa-cubes text-success"></i> Stok
                                            </span>
                                            <span class="stock-info-value text-success">{{ $buku->stok }}</span>
                                        </div>
                                        <div class="stock-info-item">
                                            <span class="stock-info-label">
                                                <i class="fas fa-book-reader text-warning"></i> Dipinjam
                                            </span>
                                            <span class="stock-info-value text-warning">{{ $buku->dipinjam }}</span>
                                        </div>
                                        <div class="stock-info-item">
                                            <span class="stock-info-label">
                                                <i class="fas fa-receipt text-info"></i> Dibooking
                                            </span>
                                            <span class="stock-info-value text-info">{{ $buku->dibooking }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer-modern">
                    <a href="{{ route('admin.master.buku.index') }}" class="btn btn-modern btn-light-modern">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('admin.master.buku.edit', $buku->id) }}" class="btn btn-modern btn-warning-modern">
                        <i class="fas fa-edit"></i> Edit Buku
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection