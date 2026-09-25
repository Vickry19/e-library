@extends('admin.layout.main')

@section('title', 'Master Buku')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-buku">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Master Buku</h3>
                            <small class="opacity-75">Kelola koleksi buku perpustakaan</small>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-light badge-lg">
                            <i class="fas fa-book"></i>
                            {{ $buku->count() }} Buku
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
                    <i class="fas fa-book"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Buku</span>
                    <span class="mini-stat-value">{{ $buku->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Stok Tersedia</span>
                    <span class="mini-stat-value">{{ $buku->sum('stok') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-warning">
                <div class="mini-stat-icon">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Sedang Dipinjam</span>
                    <span class="mini-stat-value">{{ $buku->sum('dipinjam') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-info">
                <div class="mini-stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Dibooking</span>
                    <span class="mini-stat-value">{{ $buku->sum('dibooking') }}</span>
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
                            <h5 class="mb-0 font-weight-bold">Daftar Buku</h5>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.master.buku.create') }}" class="btn btn-modern btn-primary-modern">
                                <i class="fas fa-plus"></i> Tambah Buku
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Tabel --}}
                    <div class="table-responsive table-modern-wrapper">
                        <table id="data-buku" class="table table-modern">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th width="6%">Tahun</th>
                                    <th width="6%">Stok</th>
                                    <th width="10%">Gambar</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buku as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <i class="fas fa-book text-primary mr-1"></i>
                                        <strong>{{ $item->judul_buku }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info badge-lg">
                                            {{ $item->kategori->nama_kategori }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-edit text-muted mr-1"></i>
                                        {{ $item->pengarang }}
                                    </td>
                                    <td>
                                        <i class="fas fa-building text-muted mr-1"></i>
                                        {{ $item->penerbit }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar text-muted mr-1"></i>
                                        {{ $item->tahun_terbit }}
                                    </td>
                                    <td>
                                        @if($item->stok > 0)
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check"></i> {{ $item->stok }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-times"></i> 0
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ \App\Helpers\ImageHelper::url($item->image, 'cover-buku') }}"
                                             alt="Cover"
                                             style="width: 55px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                             onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.master.buku.show', $item->id) }}"
                                               class="btn btn-sm btn-info btn-modern-sm"
                                               data-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.master.buku.edit', $item->id) }}"
                                               class="btn btn-sm btn-warning btn-modern-sm"
                                               data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-modern-sm hapus-data"
                                                    data-toggle="tooltip" title="Hapus"
                                                    data-id="{{ $item->id }}"
                                                    data-nama="{{ $item->judul_buku }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form action="{{ route('admin.master.buku.destroy', $item->id) }}"
                                                  method="POST" id="form-hapus-{{ $item->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
        $("#data-buku").DataTable({
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

        $('[data-toggle="tooltip"]').tooltip();

        // Hapus buku dengan SweetAlert
        $(document).on('click', '.hapus-data', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            SwalConfirm(
                'Hapus Buku?',
                `Buku "${nama}" akan dihapus permanen. Lanjutkan?`,
                function() {
                    $(`#form-hapus-${id}`).submit();
                }
            );
        });
    });
</script>
@endpush