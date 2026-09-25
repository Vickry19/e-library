@extends('member.layout.main')

@section('title', 'Riwayat Pinjam')
@section('hide-page-header') @endsection

@section('content')

{{-- ==================== HERO ==================== --}}
<div class="hero-section-compact hero-section-success">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="hero-icon-compact">
                    <i class="fas fa-history"></i>
                </div>
                <div class="ml-3">
                    <h2 class="mb-0 font-weight-bold text-white">Riwayat Peminjaman</h2>
                    <small class="text-white opacity-75">
                        Semua buku yang pernah kamu pinjam
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="container py-4">

    @php
        $totalPinjam = $riwayat_pinjam->sum(fn($p) => $p->pinjam_detail->count());
        $totalTerlambat = $riwayat_pinjam->sum(function($p) {
            return $p->pinjam_detail->filter(function($d) {
                if (is_null($d->tgl_pengembalian)) return false;
                return \Carbon\Carbon::parse($d->tgl_pengembalian)->gt(\Carbon\Carbon::parse($d->tgl_kembali));
            })->count();
        });
        $totalDenda = $riwayat_pinjam->sum(function($p) {
            $total = 0;
            foreach ($p->pinjam_detail as $d) {
                if (!is_null($d->tgl_pengembalian)) {
                    $tglKembali = \Carbon\Carbon::parse($d->tgl_kembali);
                    $tglPengembalian = \Carbon\Carbon::parse($d->tgl_pengembalian);
                    if ($tglPengembalian->gt($tglKembali)) {
                        $total += (int) $tglPengembalian->diffInDays($tglKembali) * $d->denda;
                    }
                }
            }
            return $total;
        });
    @endphp

    {{-- ==================== STATISTIK ==================== --}}
    <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-6 mb-2">
            <div class="mini-stat-card mini-stat-primary">
                <div class="mini-stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="mini-stat-content">
                    <span class="mini-stat-label">Total Pinjam</span>
                    <span class="mini-stat-value">{{ $totalPinjam }}</span>
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
                    <span class="mini-stat-label">Total Denda</span>
                    <span class="mini-stat-value" style="font-size: 1.1rem;">
                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== TABEL ==================== --}}
    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center">
                <i class="fas fa-list-ul text-success mr-2"></i>
                <h5 class="mb-0 font-weight-bold">Daftar Riwayat</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-modern-wrapper">
                <table id="riwayat-pinjam" class="table table-modern">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th>No. Pinjam</th>
                            <th>Tgl. Pinjam</th>
                            <th>Tgl. Kembali</th>
                            <th>Tgl. Pengembalian</th>
                            <th>Judul Buku</th>
                            <th>Status</th>
                            <th>Denda/Hari</th>
                            <th>Keterangan</th>
                            <th width="10%">Gambar</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat_pinjam as $pinjam)
                            @foreach ($pinjam->pinjam_detail as $detail)
                            @php
                                $terlambat = 0;
                                $totalDendaItem = 0;
                                if (!is_null($detail->tgl_pengembalian)) {
                                    $tglKembali = \Carbon\Carbon::parse($detail->tgl_kembali);
                                    $tglPengembalian = \Carbon\Carbon::parse($detail->tgl_pengembalian);
                                    if ($tglPengembalian->gt($tglKembali)) {
                                        $terlambat = (int) $tglPengembalian->diffInDays($tglKembali);
                                        $totalDendaItem = $terlambat * $detail->denda;
                                    }
                                }
                            @endphp
                            <tr class="{{ $terlambat > 0 ? 'tr-warning' : '' }}">
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
                                    <i class="fas fa-calendar-check text-muted mr-1"></i>
                                    {{ \Carbon\Carbon::parse($detail->tgl_kembali)->format('d-m-Y') }}
                                </td>
                                <td>
                                    <i class="fas fa-calendar-day text-success mr-1"></i>
                                    {{ $detail->tgl_pengembalian ? \Carbon\Carbon::parse($detail->tgl_pengembalian)->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    <i class="fas fa-book text-primary mr-1"></i>
                                    <strong>{{ $detail->buku->judul_buku }}</strong>
                                </td>
                                <td>
                                    @if($detail->status == 'Kembali')
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check-circle"></i> Kembali
                                        </span>
                                    @else
                                        <span class="badge badge-info badge-lg">
                                            <i class="fas fa-book-reader"></i> Dipinjam
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        Rp {{ number_format($detail->denda, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($terlambat > 0)
                                        <div class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <b>Terlambat {{ $terlambat }} hari</b>
                                        </div>
                                        <small class="text-danger font-weight-bold">
                                            Total: Rp {{ number_format($totalDendaItem, 0, ',', '.') }}
                                        </small>
                                    @else
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Tepat waktu
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <img src="{{ \App\Helpers\ImageHelper::url($detail->buku->image, 'cover-buku') }}"
                                         style="width: 55px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;"
                                         onerror="this.src='{{ asset('storage/cover-buku/book-default-cover.jpg') }}'">
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-info btn-modern-sm"
                                            onclick="detailRiwayat({{ $detail->id }})"
                                            data-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
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

{{-- ==================== MODAL DETAIL RIWAYAT ==================== --}}
<div class="modal fade" id="detailRiwayatModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content detail-modal">

            {{-- Header --}}
            <div class="detail-modal-header">
                <div class="d-flex align-items-center">
                    <div class="detail-icon-wrapper">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">Detail Riwayat</h5>
                        <small class="opacity-75">Informasi lengkap peminjaman</small>
                    </div>
                </div>
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="detail-modal-body">
                <div class="text-center mb-3">
                    <img id="detailCover" src="" alt="Cover"
                         style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </div>

                <h5 class="text-center mb-3" id="detailJudul">-</h5>

                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-item-icon bg-primary-light">
                            <i class="fas fa-hashtag text-primary"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">No. Pinjam</span>
                            <span class="detail-value" id="detailNoPinjam">-</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-icon bg-info-light">
                            <i class="fas fa-calendar-plus text-info"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">Tgl. Pinjam</span>
                            <span class="detail-value" id="detailTglPinjam">-</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-icon bg-warning-light">
                            <i class="fas fa-calendar-check text-warning"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">Tgl. Kembali (Batas)</span>
                            <span class="detail-value" id="detailTglKembali">-</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-icon bg-success-light">
                            <i class="fas fa-calendar-day text-success"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">Tgl. Pengembalian</span>
                            <span class="detail-value" id="detailTglPengembalian">-</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-icon bg-secondary-light">
                            <i class="fas fa-clock text-secondary"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">Lama Pinjam</span>
                            <span class="detail-value" id="detailLama">-</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-icon bg-danger-light">
                            <i class="fas fa-money-bill-wave text-danger"></i>
                        </div>
                        <div class="detail-item-content">
                            <span class="detail-label">Denda</span>
                            <span class="detail-value" id="detailDenda">-</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="detail-modal-footer">
                <button type="button" class="btn btn-light btn-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#riwayat-pinjam tbody tr').length > 0) {
            $('#riwayat-pinjam').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: { first: "«", last: "»", next: "›", previous: "‹" }
                }
            });
        }
    });

    // ==================== DETAIL RIWAYAT ====================
    function detailRiwayat(id) {
        // Cari data dari HTML (bisa juga fetch ke server)
        const row = $('button[onclick="detailRiwayat(' + id + ')"]').closest('tr');

        const noPinjam = row.find('td').eq(1).text().trim();
        const tglPinjam = row.find('td').eq(2).text().trim();
        const tglKembali = row.find('td').eq(3).text().trim();
        const tglPengembalian = row.find('td').eq(4).text().trim();
        const judul = row.find('td').eq(5).text().trim();
        const denda = row.find('td').eq(7).text().trim();
        const keterangan = row.find('td').eq(8).text().trim();
        const gambar = row.find('td').eq(9).find('img').attr('src');

        $('#detailNoPinjam').text(noPinjam);
        $('#detailTglPinjam').text(tglPinjam);
        $('#detailTglKembali').text(tglKembali);
        $('#detailTglPengembalian').text(tglPengembalian);
        $('#detailJudul').text(judul);
        $('#detailDenda').text(denda);
        $('#detailCover').attr('src', gambar);

        // Lama pinjam = selisih tgl kembali & tgl pinjam
        const tgl1 = new Date(tglPinjam.split('-').reverse().join('-'));
        const tgl2 = new Date(tglKembali.split('-').reverse().join('-'));
        const lama = Math.round((tgl2 - tgl1) / (1000 * 60 * 60 * 24));
        $('#detailLama').text(lama + ' hari');

        $('#detailRiwayatModal').modal('show');
    }
</script>
@endpush