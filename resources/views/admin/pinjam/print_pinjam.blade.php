<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Peminjaman - E-Library UNM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 40px;
            color: #333;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #007bff;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p { color: #666; font-size: 13px; }

        .info-box {
            background: #f8f9fa;
            padding: 12px 18px;
            border-left: 4px solid #007bff;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .info-box strong { color: #007bff; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table thead th {
            background: #007bff;
            color: #fff;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #0056b3;
        }
        table tbody td {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        table tbody tr:hover {
            background: #e7f1ff;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            background: #17a2b8;
            color: #fff;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-primary:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .header { border-color: #000; }
            .header h1 { color: #000; }
            table thead th { background: #333; color: #fff; }
        }
    </style>
</head>
<body>

{{-- Tombol Aksi (tidak ter-print) --}}
<div class="no-print">
    <a href="{{ route('admin.transaksi.peminjaman.index') }}" class="btn btn-secondary">
        <i>←</i> Kembali
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        <i>🖨</i> Print / Save PDF
    </button>
</div>

{{-- Header --}}
<div class="header">
    <h1>E-LIBRARY UNM</h1>
    <p>Universitas Nusa Mandiri - Fakultas Teknologi Informasi</p>
    <p style="margin-top: 10px; font-weight: 600; color: #333;">
        LAPORAN TRANSAKSI PEMINJAMAN BUKU
    </p>
</div>

{{-- Info Periode --}}
<div class="info-box">
    <strong>Periode:</strong> {{ $startDate }} s/d {{ $endDate }}
    &nbsp;|&nbsp;
    <strong>Total:</strong> {{ $data_pinjam->count() }} transaksi
</div>

{{-- Tabel --}}
<table>
    <thead>
        <tr>
            <th style="width: 4%;">No</th>
            <th style="width: 10%;">No. Pinjam</th>
            <th style="width: 10%;">Tgl. Pinjam</th>
            <th style="width: 10%;">Tgl. Kembali</th>
            <th style="width: 20%;">Judul Buku</th>
            <th style="width: 8%;">Status</th>
            <th style="width: 15%;">Anggota</th>
            <th style="width: 13%;">Petugas</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data_pinjam as $index => $pinjam)
            @foreach ($pinjam->pinjam_detail as $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pinjam->no_pinjam }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->tgl_kembali)->format('d-m-Y') }}</td>
                <td>{{ $detail->buku->judul_buku ?? '-' }}</td>
                <td><span class="badge">{{ $detail->status }}</span></td>
                <td>{{ $pinjam->anggota->nama ?? '-' }}</td>
                <td>{{ $pinjam->petugas_pinjam->nama ?? '-' }}</td>
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                    Tidak ada data peminjaman untuk periode ini.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Footer --}}
<div class="footer">
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    <p>&copy; {{ date('Y') }} E-Library UNM. All rights reserved.</p>
</div>

</body>
</html>