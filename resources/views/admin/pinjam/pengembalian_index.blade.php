@extends('admin.layout.main')

@section('title', 'Transaksi Pengembalian')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-pengembalian">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Transaksi Pengembalian</h3>
                            <small class="opacity-75">Riwayat buku yang sudah dikembalikan anggota</small>
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

    {{-- ==================== STATISTIK ==================== --}}
    @php
        $totalKembali = $data_pinjam->sum(fn($p) => $p->pinjam_detail->count());

        $totalTerlambat = 0;
        $totalDenda = 0;

        foreach ($data_pinjam as $p) {
            foreach ($p->pinjam_detail as $d) {
                if (!is_null($d->tgl_pengembalian)) {
                    $tglKembali = \Carbon\Carbon::parse($d->tgl_kembali);
                    $tglPengembalian = \Carbon\Carbon::parse($d->tgl_pengembalian);

                    // Cek apakah terlambat
                    if ($tglPengembalian->gt($tglKembali)) {
                        $hariTerlambat = (int) $tglKembali->diffInDays($tglPengembalian);
                        $totalTerlambat++;
                        $totalDenda += $hariTerlambat * $d->denda;
                    }
                }
            }
        }
    @endphp

    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Kembali</span>
                    <span class="mini-stat-value">{{ $totalKembali }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-primary">
                <div class="mini-stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Buku Kembali</span>
                    <span class="mini-stat-value">{{ $data_pinjam->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-2">
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
        <div class="col-lg-3 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-warning">
                <div class="mini-stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Denda</span>
                    <span class="mini-stat-value" style="font-size: 1rem;">
                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== INFO BANNER ==================== --}}
    @if($totalTerlambat > 0)
    <div class="info-banner info-banner-warning">
        <div class="info-banner-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="info-banner-content">
            <strong>Perhatian!</strong> Ada <strong>{{ $totalTerlambat }} buku</strong> yang dikembalikan terlambat
            dengan total denda <strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>.
        </div>
    </div>
    @endif

    {{-- ==================== MAIN CARD ==================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">

                {{-- Header --}}
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-ul text-success mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Riwayat Pengembalian Buku</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Filter Bar --}}
                    <div class="filter-bar">
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-6 mb-2 mb-lg-0">
                                <label class="filter-label">
                                    <i class="fas fa-search text-primary"></i> Cari Data
                                </label>
                                <input type="text" id="search-pengembalian" class="form-control form-control-modern"
                                       placeholder="Cari no pinjam / judul buku / anggota...">
                            </div>
                            <div class="col-lg-4 col-md-6 mb-2 mb-lg-0">
                                <label class="filter-label">
                                    <i class="fas fa-filter text-primary"></i> Filter Status
                                </label>
                                <select id="filter-status" class="form-control form-control-modern">
                                    <option value="">Semua Status</option>
                                    <option value="tepat">✅ Tepat Waktu</option>
                                    <option value="terlambat">⚠️ Terlambat</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="filter-label d-block">&nbsp;</label>
                                <button type="button" id="reset-filter" class="btn btn-modern btn-light-modern">
                                    <i class="fas fa-redo"></i> Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive table-modern-wrapper">
                        <table id="pengembalian-table" class="table table-modern">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>No. Pinjam</th>
                                    <th>Tgl. Pinjam</th>
                                    <th>Tgl. Kembali</th>
                                    <th>Lama</th>
                                    <th>Tgl. Pengembalian</th>
                                    <th>Judul Buku</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th width="10%">Gambar</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data_pinjam as $pinjam)
                                    @foreach ($pinjam->pinjam_detail as $detail)
                                    @php
                                        $terlambat = 0;
                                        $totalDenda = 0;
                                        $isLate = false;

                                        if (!is_null($detail->tgl_pengembalian)) {
                                            $tglKembali = \Carbon\Carbon::parse($detail->tgl_kembali);
                                            $tglPengembalian = \Carbon\Carbon::parse($detail->tgl_pengembalian);

                                            // ✅ KUNCI PERBAIKAN: cek dulu, baru hitung
                                            if ($tglPengembalian->gt($tglKembali)) {
                                                // Terlambat: hitung dari tglKembali ke tglPengembalian (positif)
                                                $terlambat = (int) $tglKembali->diffInDays($tglPengembalian);
                                                $totalDenda = $terlambat * $detail->denda;
                                                $isLate = true;
                                            }
                                        }

                                        $rowClass = $isLate ? 'tr-danger' : '';
                                        $statusFilter = $isLate ? 'terlambat' : 'tepat';
                                    @endphp
                                    <tr class="{{ $rowClass }}" data-status="{{ $statusFilter }}">
                                        <td>{{ $no++ }}</td>
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
                                            <i class="fas fa-calendar-check text-muted mr-1"></i>
                                            {{ \Carbon\Carbon::parse($detail->tgl_kembali)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info badge-lg">
                                                <i class="fas fa-clock"></i> {{ $detail->lama_pinjam }} hari
                                            </span>
                                        </td>
                                        <td>
                                            @if($detail->tgl_pengembalian)
                                                <span class="{{ $isLate ? 'text-danger font-weight-bold' : 'text-success' }}">
                                                    <i class="fas fa-calendar-day"></i>
                                                    {{ \Carbon\Carbon::parse($detail->tgl_pengembalian)->format('d-m-Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fas fa-book text-primary mr-1"></i>
                                            <strong>{{ $detail->buku->judul_buku ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            @if($isLate)
                                                <span class="badge badge-danger badge-lg">
                                                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                                                </span>
                                            @else
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> Tepat Waktu
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="text-muted">
                                                    <i class="fas fa-money-bill-wave text-success"></i>
                                                    Denda: <b>Rp {{ number_format($detail->denda, 0, ',', '.') }}</b>
                                                </div>
                                                <div class="{{ $isLate ? 'text-danger' : 'text-success' }}">
                                                    <i class="fas fa-clock"></i>
                                                    Terlambat: <b>{{ $terlambat }} hari</b>
                                                </div>
                                                @if($isLate)
                                                    <div class="text-danger font-weight-bold mt-1">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Total: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                                    </div>
                                                @else
                                                    <div class="text-success mt-1">
                                                        <i class="fas fa-check"></i> Tidak ada denda
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ \App\Helpers\ImageHelper::url($detail->buku->image ?? '', 'cover-buku') }}"
                                                 style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                                 onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div>
                                                    <i class="fas fa-sign-out-alt text-primary"></i>
                                                    <b>Pinjam:</b> {{ $pinjam->petugas_pinjam->nama ?? '-' }}
                                                </div>
                                                <div>
                                                    <i class="fas fa-sign-in-alt text-success"></i>
                                                    <b>Kembali:</b> {{ $detail->petugas_kembali->nama ?? '-' }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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

        var table = $('#pengembalian-table').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'asc']],
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

        // Search custom
        $('#search-pengembalian').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter status
        $('#filter-status').on('change', function() {
            var val = $(this).val();

            // Hapus filter lama
            $.fn.dataTable.ext.search.pop();

            if (val !== '') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var row = table.row(dataIndex).node();
                    return $(row).data('status') === val;
                });
            }

            table.draw();
        });

        // Reset filter
        $('#reset-filter').on('click', function() {
            $('#search-pengembalian').val('');
            $('#filter-status').val('');
            $.fn.dataTable.ext.search.pop();
            table.search('').draw();
        });

    });
</script>
@endpush