@extends('admin.layout.main')

@section('title', 'Data Booking')

@section('content')
<div class="container-fluid">
    {{-- Peringatan --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Waktu Pengambilan Buku 1x24 jam dari Booking!</strong><br>
                Jika tidak diambil setelah batas waktu, maka booking akan dibatalkan otomatis oleh sistem.
            </div>
        </div>
    </div>

    {{-- Info Booking --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>ID Booking</strong>
                            <p class="mb-0">
                                <span class="badge badge-secondary">{{ $booking->id_booking }}</span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <strong>Tanggal Booking</strong>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d-m-Y H:i:s') }}
                            </p>
                        </div>
                        <div class="col-md-3">
    <strong>Batas Ambil</strong>
    <p class="mb-0 text-danger">
        {{ \Carbon\Carbon::parse($booking->batas_ambil)->format('d-m-Y H:i') }}
    </p>
    @php
        $batas = \Carbon\Carbon::parse($booking->batas_ambil);
        $now = \Carbon\Carbon::now();
        $isExpired = $now->gt($batas);
        
        if ($isExpired) {
            $selisihDetik = $batas->diffInSeconds($now);
        } else {
            $selisihDetik = $now->diffInSeconds($batas);
        }
        
        $hari = (int) floor($selisihDetik / 86400);
        $jam = (int) floor(($selisihDetik % 86400) / 3600);
        $menit = (int) floor(($selisihDetik % 3600) / 60);
    @endphp
    <small class="badge {{ $isExpired ? 'badge-danger' : 'badge-success' }}">
        @if($isExpired)
            <i class="fas fa-exclamation-triangle"></i>
            Expired 
            @if($hari > 0) {{ $hari }} hari {{ $jam }} jam lalu
            @elseif($jam > 0) {{ $jam }} jam {{ $menit }} menit lalu
            @else {{ $menit }} menit lalu
            @endif
        @else
            <i class="fas fa-clock"></i>
            Sisa 
            @if($hari > 0) {{ $hari }} hari {{ $jam }} jam
            @elseif($jam > 0) {{ $jam }} jam {{ $menit }} menit
            @else {{ $menit }} menit
            @endif
        @endif
    </small>
</div>
                        <div class="col-md-3">
                            <strong>Anggota</strong>
                            <p class="mb-0">{{ $booking->anggota->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Buku --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-book"></i> Daftar Buku yang Dibooking
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="4%">#</th>
                                <th width="15%">Judul Buku</th>
                                <th width="8%">Kategori</th>
                                <th width="10%">Pengarang</th>
                                <th width="10%">Penerbit</th>
                                <th width="5%">Tahun</th>
                                <th width="12%">Denda/Hari</th>
                                <th width="10%">Lama Pinjam</th>
                                <th width="8%">Gambar</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($booking->booking_detail as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->buku->judul_buku }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $detail->buku->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td>{{ $detail->buku->pengarang }}</td>
                                <td>{{ $detail->buku->penerbit }}</td>
                                <td>{{ $detail->buku->tahun_terbit }}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" 
                                               name="denda" 
                                               form="form-pinjam-{{ $detail->id }}"
                                               placeholder="0" min="0" step="1000" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" 
                                               name="lama" 
                                               form="form-pinjam-{{ $detail->id }}"
                                               placeholder="0" min="1" max="30" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">hari</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <img src="{{ \App\Helpers\ImageHelper::url($detail->buku->image, 'cover-buku') }}" 
                                         alt="Cover" 
                                         style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>
                                    <form action="{{ route('admin.transaksi.peminjaman.storeSingle') }}" 
                                          method="POST" 
                                          id="form-pinjam-{{ $detail->id }}"
                                          onsubmit="return confirm('Proses peminjaman buku ini?')">
                                        @csrf
                                        <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">
                                        <input type="hidden" name="id_buku" value="{{ $detail->buku->id }}">
                                        <input type="hidden" name="id_user" value="{{ $booking->id_user }}">
                                        <button type="submit" class="btn btn-sm btn-success btn-block">
                                            <i class="fas fa-check"></i> Pinjam
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Tidak ada buku dalam booking ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12">
            <a href="{{ route('admin.transaksi.booking.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection