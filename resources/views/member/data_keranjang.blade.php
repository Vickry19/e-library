@extends('member.layout.main')

@section('title', 'Data Keranjang')

@section('content')

{{-- ==================== HERO SECTION ==================== --}}
<div class="hero-section-compact">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="hero-icon-compact">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="ml-3">
                    <h2 class="mb-0 font-weight-bold text-white">Keranjang Buku</h2>
                    <small class="text-white opacity-75">
                        {{ $data_keranjang->count() }} buku siap untuk dibooking
                    </small>
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('member.index') }}" class="btn btn-light btn-modern-sm">
                    <i class="fas fa-plus"></i> Tambah Buku
                </a>
            </div>
        </div>
    </div>
</div>

<main class="container py-4">

    {{-- ==================== STATISTIK ==================== --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-primary">
                <div class="mini-stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Buku</span>
                    <span class="mini-stat-value">{{ $data_keranjang->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Tersedia</span>
                    <span class="mini-stat-value">
                        {{ $data_keranjang->filter(fn($item) => $item->buku->stok > 0)->count() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-warning">
                <div class="mini-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Stok Habis</span>
                    <span class="mini-stat-value">
                        {{ $data_keranjang->filter(fn($item) => $item->buku->stok == 0)->count() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-info">
                <div class="mini-stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Limit</span>
                    <span class="mini-stat-value">3</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== INFO ALERT ==================== --}}
    <div class="info-banner">
        <div class="info-banner-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-banner-content">
            <strong>Informasi:</strong> Pastikan semua buku tersedia sebelum booking.
            Setelah klik <strong>"Selesai Booking"</strong>, kamu punya waktu <strong>1x24 jam</strong>
            untuk mengambil buku di perpustakaan.
        </div>
    </div>

    {{-- ==================== MAIN CARD ==================== --}}
    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list-ul text-primary mr-2"></i>
                    <h5 class="mb-0 font-weight-bold">Buku di Keranjang</h5>
                </div>
                <div class="mt-2 mt-md-0 d-flex gap-2">
                    <a href="{{ route('member.index') }}" class="btn btn-modern btn-light-modern">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                    <form action="{{ route('member.simpanBooking') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                        <button type="submit" class="btn btn-modern btn-success-modern"
                                onclick="return confirmBooking(event)">
                            <i class="fas fa-check"></i> Selesai Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($data_keranjang->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h4 class="empty-state-title">Keranjang Kosong</h4>
                    <p class="empty-state-text">Belum ada buku di keranjangmu. Yuk, cari buku favoritmu!</p>
                    <a href="{{ route('member.index') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-search"></i> Cari Buku
                    </a>
                </div>
            @else
                <div class="table-responsive table-modern-wrapper">
                    <table id="data-keranjang" class="table table-modern">
                        <thead>
                            <tr>
                                <th width="4%">#</th>
                                <th width="20%">Judul Buku</th>
                                <th>Kategori</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th width="6%">Tahun</th>
                                <th width="6%">Stok</th>
                                <th width="10%">Gambar</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_keranjang as $item)
                            <tr class="{{ $item->buku->stok == 0 ? 'tr-danger' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <i class="fas fa-book text-primary mr-1"></i>
                                    <strong>{{ $item->buku->judul_buku }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info badge-lg">
                                        {{ $item->buku->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-user-edit text-muted mr-1"></i>
                                    {{ $item->buku->pengarang }}
                                </td>
                                <td>
                                    <i class="fas fa-building text-muted mr-1"></i>
                                    {{ $item->buku->penerbit }}
                                </td>
                                <td>{{ $item->buku->tahun_terbit }}</td>
                                <td>
                                    @if($item->buku->stok > 0)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check"></i> {{ $item->buku->stok }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger badge-lg">
                                            <i class="fas fa-times"></i> Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <img src="{{ \App\Helpers\ImageHelper::url($item->buku->image, 'cover-buku') }}"
                                         style="width: 55px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                         onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                </td>
                                <td>
                                    <form action="{{ route('member.hapusKeranjang', ['buku' => $item->buku->id, 'user' => Auth::user()->id]) }}"
                                          method="POST" id="form-hapus-{{ $item->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-modern-sm hapus-data"
                                                data-nama="{{ $item->buku->judul_buku }}"
                                                data-id="{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#data-keranjang tbody tr').length > 0) {
            $('#data-keranjang').DataTable({
                responsive: true,
                autoWidth: false,
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

    function confirmBooking(e) {
        e.preventDefault();
        const form = e.target.closest('form');
        Swal.fire({
            title: 'Konfirmasi Booking?',
            text: 'Setelah booking, kamu punya 1x24 jam untuk mengambil buku di perpustakaan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya, Booking',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
        return false;
    }

    $(document).on('click', '.hapus-data', function() {
        const nama = $(this).data('nama');
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Buku?',
            text: `Buku "${nama}" akan dihapus dari keranjang.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#form-hapus-${id}`).submit();
            }
        });
    });
</script>
@endpush