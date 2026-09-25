@extends('admin.layout.main')

@section('title', 'Transaksi Peminjaman')

@section('content')
<div class="container-fluid">

    {{-- ==================== PAGE HEADER ==================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header-pinjam">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-0 font-weight-bold">Transaksi Peminjaman</h3>
                            <small class="opacity-75">Kelola buku yang sedang dipinjam anggota</small>
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
                    <i class="fas fa-book"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Sedang Dipinjam</span>
                    <span class="mini-stat-value" id="stat-aktif">-</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-warning">
                <div class="mini-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Terlambat</span>
                    <span class="mini-stat-value" id="stat-terlambat">-</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-success">
                <div class="mini-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Anggota</span>
                    <span class="mini-stat-value" id="stat-anggota">-</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="mini-stat-card mini-stat-info">
                <div class="mini-stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Hari Ini</span>
                    <span class="mini-stat-value" id="stat-hari-ini">-</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CARD ==================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">

                {{-- Filter Header --}}
                <div class="card-header card-header-modern">
                    <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-ul text-primary mr-2"></i>
                            <h5 class="mb-0 font-weight-bold">Daftar Peminjaman Aktif</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Filter Bar --}}
                    <div class="filter-bar">
                        <div class="row align-items-end">
                            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
                                <label class="filter-label">
                                    <i class="fas fa-calendar-alt text-primary"></i> Dari Tanggal
                                </label>
                                <input type="date" id="start_date" class="form-control form-control-modern">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
                                <label class="filter-label">
                                    <i class="fas fa-calendar-alt text-primary"></i> Sampai Tanggal
                                </label>
                                <input type="date" id="end_date" class="form-control form-control-modern">
                            </div>
                            <div class="col-lg-6">
                                <label class="filter-label d-block">&nbsp;</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" id="filter" class="btn btn-modern btn-primary-modern">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <button type="button" id="reset" class="btn btn-modern btn-light-modern">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-modern btn-danger-modern" id="export-pdf">
                                        <i class="fas fa-file-pdf"></i> Print PDF
                                    </button>
                                    <button type="button" class="btn btn-modern btn-success-modern" id="export-excel">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive table-modern-wrapper">
                        <table class="table table-modern" id="transaksi-table">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th>No. Pinjam</th>
                                    <th>Tgl. Pinjam</th>
                                    <th>Tgl. Kembali</th>
                                    <th>Judul Buku</th>
                                    <th>Status</th>
                                    <th>Gambar</th>
                                    <th>Anggota</th>
                                    <th>Petugas</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
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

        // ==================== DATATABLE ====================
        var table = $('#transaksi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.transaksi.peminjaman.data') }}",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
                dataSrc: function(json) {
                    // Update statistik
                    $('#stat-aktif').text(json.recordsTotal || 0);
                    $('#stat-terlambat').text(0);
                    $('#stat-anggota').text(new Set(json.data.map(r => r.anggota)).size);
                    $('#stat-hari-ini').text(json.data.length);
                    return json.data;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '4%' },
                { data: 'no_pinjam', name: 'no_pinjam' },
                { data: 'tgl_pinjam', name: 'tgl_pinjam' },
                { data: 'tgl_kembali', name: 'tgl_kembali' },
                { data: 'judul_buku', name: 'judul_buku' },
                { data: 'status', name: 'status', orderable: false },
                { data: 'gambar', name: 'gambar', orderable: false, searchable: false },
                { data: 'anggota', name: 'anggota' },
                { data: 'petugas', name: 'petugas' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ],
            responsive: true,
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "🔍 Cari data...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Tidak ada data peminjaman</h6>
                        <small class="text-muted">Coba ubah filter tanggal atau reset</small>
                    </div>
                `,
                processing: '<div class="spinner-modern"></div>',
                paginate: {
                    first: "«",
                    last: "»",
                    next: "›",
                    previous: "‹"
                }
            }
        });

        // ==================== FILTER ====================
        $('#filter').click(function() {
            table.ajax.reload();
        });

        $('#reset').click(function() {
            $('#start_date').val('');
            $('#end_date').val('');
            table.ajax.reload();
        });

        // ==================== KEMBALIKAN BUKU ====================
        $(document).on('click', '.kembalikan-buku', function(event) {
            event.preventDefault();
            var form = $(this).closest("form");
            var url = form.attr('action');
            var token = form.find('input[name="_token"]').val();
            var method = form.find('input[name="_method"]').val();

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Yakin ingin mengembalikan buku ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Kembalikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: token, _method: method },
                        success: function(response) {
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Buku berhasil dikembalikan.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan.'
                            });
                        }
                    });
                }
            });
        });

        // ==================== EXPORT PDF ====================
        $('#export-pdf').on('click', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (startDate === '' || endDate === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Belum Dipilih',
                    text: 'Silakan pilih tanggal awal dan akhir.',
                });
                return false;
            }

            var url = '{{ route("admin.transaksi.pinjam.printPinjam") }}?start_date=' + startDate + '&end_date=' + endDate;
            window.open(url, '_blank');
        });

        // ==================== EXPORT CSV ====================
        $('#export-excel').on('click', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (startDate === '' || endDate === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Belum Dipilih',
                    text: 'Silakan pilih tanggal awal dan akhir.',
                });
                return false;
            }

            var url = '{{ route("admin.transaksi.pinjam.exportCsvPinjam") }}?start_date=' + startDate + '&end_date=' + endDate;
            window.location.href = url;
        });

    });
</script>
@endpush