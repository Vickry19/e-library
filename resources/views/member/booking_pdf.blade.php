<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Booking - E-Library UNM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #212529;
            background: #fff;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px double #007bff;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 800;
        }
        .header p {
            color: #6c757d;
            font-size: 12px;
        }
        .header .title {
            margin-top: 15px;
            font-size: 16px;
            font-weight: 700;
            color: #212529;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-box {
            background: linear-gradient(135deg, #e7f1ff 0%, #f0f8ff 100%);
            border-left: 4px solid #007bff;
            border-radius: 8px;
            padding: 15px;
        }

        .info-box-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-box-value {
            font-size: 14px;
            font-weight: 700;
            color: #212529;
        }

        .info-box-value.danger { color: #dc3545; }
        .info-box-value.success { color: #28a745; }

        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 12px;
            color: #856404;
        }
        .alert strong { color: #856404; }
        .alert i { margin-right: 5px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        table thead th {
            background: #007bff;
            color: #fff;
            padding: 12px 10px;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        table tbody td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
        }
        .badge-info { background: #17a2b8; }
        .badge-primary { background: #007bff; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
        }

        .footer-note {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 11px;
        }

        .signature {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .signature-box {
            text-align: center;
            font-size: 11px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #212529;
            padding-top: 5px;
            font-weight: 700;
        }

        @media print {
            body { padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <h1>📚 E-LIBRARY UNM</h1>
    <p>Universitas Nusa Mandiri - Fakultas Teknologi Informasi</p>
    <div class="title">Bukti Booking Buku</div>
</div>

{{-- Info Grid --}}
<div class="info-grid">
    <div class="info-box">
        <div class="info-box-label">ID Booking</div>
        <div class="info-box-value">{{ $data_booking[0]->id_booking ?? '-' }}</div>
    </div>
    <div class="info-box">
        <div class="info-box-label">Tanggal Booking</div>
        <div class="info-box-value">
            {{ isset($data_booking[0]) ? \Carbon\Carbon::parse($data_booking[0]->tgl_booking)->format('d M Y, H:i') : '-' }}
        </div>
    </div>
    <div class="info-box">
        <div class="info-box-label">Batas Ambil</div>
        <div class="info-box-value danger">
            {{ isset($data_booking[0]) ? \Carbon\Carbon::parse($data_booking[0]->batas_ambil)->format('d M Y, H:i') : '-' }}
        </div>
    </div>
</div>

{{-- Alert --}}
<div class="alert">
    <strong><i>⚠</i> Penting!</strong> Bukti booking ini wajib dibawa saat mengambil buku di perpustakaan.
    Batas pengambilan hanya <strong>1x24 jam</strong> dari waktu booking. Jika tidak diambil,
    booking akan dibatalkan otomatis oleh sistem.
</div>

{{-- Tabel Buku --}}
<table>
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="30%">Judul Buku</th>
            <th width="15%">Kategori</th>
            <th width="15%">Pengarang</th>
            <th width="15%">Penerbit</th>
            <th width="10%">Tahun</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_booking as $booking)
            @foreach ($booking->booking_detail as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $detail->buku->judul_buku ?? '-' }}</strong></td>
                <td>
                    <span class="badge badge-info">
                        {{ $detail->buku->kategori->nama_kategori ?? '-' }}
                    </span>
                </td>
                <td>{{ $detail->buku->pengarang ?? '-' }}</td>
                <td>{{ $detail->buku->penerbit ?? '-' }}</td>
                <td>{{ $detail->buku->tahun_terbit ?? '-' }}</td>
                <td>
                    <span class="badge badge-primary">Booked</span>
                </td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

{{-- Signature --}}
<div class="signature">
    <div class="signature-box">
        <div>Peminjam</div>
        <div class="signature-line">
            (............................................)
        </div>
    </div>
    <div class="signature-box">
        <div>Petugas Perpustakaan</div>
        <div class="signature-line">
            (............................................)
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="footer">
    <div class="footer-note">
        <strong>Catatan:</strong>
        <ul style="margin-left: 15px; margin-top: 5px;">
            <li>Bukti booking ini sebagai tanda pemesanan buku.</li>
            <li>Buku harus diambil dalam waktu 1x24 jam dari waktu booking.</li>
            <li>Jika melewati batas waktu, booking akan dibatalkan otomatis.</li>
            <li>Bawa bukti ini saat mengambil buku di perpustakaan.</li>
        </ul>
    </div>
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    <p>&copy; {{ date('Y') }} E-Library UNM. All rights reserved.</p>
</div>

</body>
</html>