<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\User;
use App\Models\Pinjam;
use App\Models\Booking;
use App\Models\PinjamDetail;
use App\Models\BookingDetail;
use App\Exports\PinjamExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PinjamController extends Controller
{
    // ============================================================
    // INDEX — Data Peminjaman (Admin)
    // ============================================================
    public function index()
    {
        return view('admin.pinjam.index');
    }

    // ============================================================
    // STORE SINGLE — Proses Pinjam Per Buku
    // ============================================================
    public function storeSingle(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|string',
            'id_buku' => 'required|integer',
            'id_user' => 'required|integer',
            'lama' => 'required|integer|min:1|max:30',
            'denda' => 'required|integer|min:0',
        ]);

        $redirectUrl = '';
        $message = '';
        $flashType = 'success';

        DB::transaction(function () use ($request, &$redirectUrl, &$message, &$flashType) {
            // 1. Generate no_pinjam
            $today = Carbon::today()->format('ymd');
            $latestPinjam = DB::table('pinjam')
                ->whereDate('tgl_pinjam', Carbon::today())
                ->orderBy('id', 'desc')
                ->first();
            $latestId = $latestPinjam ? intval(substr($latestPinjam->no_pinjam, -3)) : 0;
            $newNoPinjam = 'P' . $today . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);

            $tgl_pinjam = Carbon::now();
            $tgl_kembali = $tgl_pinjam->copy()->addDays((int) $request->lama);

            // 2. Simpan header pinjam
            DB::table('pinjam')->insert([
                'no_pinjam' => $newNoPinjam,
                'tgl_pinjam' => $tgl_pinjam,
                'id_booking' => $request->id_booking,
                'id_user' => $request->id_user,
                'total_denda' => 0,
                'id_petugas_pinjam' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Simpan detail
            DB::table('pinjam_detail')->insert([
                'no_pinjam' => $newNoPinjam,
                'id_buku' => $request->id_buku,
                'tgl_kembali' => $tgl_kembali,
                'tgl_pengembalian' => null,
                'status' => 'Pinjam',
                'denda' => (int) $request->denda,
                'lama_pinjam' => (int) $request->lama,
                'id_petugas_kembali' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Update stok buku
            DB::table('buku')->where('id', $request->id_buku)->update([
                'dipinjam' => DB::raw('dipinjam + 1'),
                'dibooking' => DB::raw('dibooking - 1'),
            ]);

            // 5. Hapus detail booking yang sudah dipinjam
            DB::table('booking_detail')
                ->where('id_booking', $request->id_booking)
                ->where('id_buku', $request->id_buku)
                ->delete();

            // 6. Cek sisa detail booking
            $sisa = DB::table('booking_detail')
                ->where('id_booking', $request->id_booking)
                ->count();

            if ($sisa == 0) {
                // Semua buku sudah dipinjam → hapus header booking
                DB::table('booking')->where('id_booking', $request->id_booking)->delete();
                $redirectUrl = route('admin.transaksi.booking.index');
                $message = 'Semua buku berhasil dipinjam dan booking telah dihapus!';
            } else {
                // Masih ada sisa → kembali ke detail booking
                $booking = DB::table('booking')->where('id_booking', $request->id_booking)->first();
                $redirectUrl = route('admin.transaksi.booking.show', $booking->id);
                $message = 'Buku berhasil dipinjam! Masih ada ' . $sisa . ' buku yang belum dipinjam.';
            }
        });

        return redirect($redirectUrl)->with($flashType, $message);
    }

    // ============================================================
    // GET DATA — DataTables Peminjaman
    // ============================================================
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            if (empty($startDate) || empty($endDate)) {
                $data_pinjam = Pinjam::with(['pinjam_detail' => function ($query) {
                        $query->where('status', 'Pinjam');
                    }, 'pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam', 'anggota'])
                    ->whereHas('pinjam_detail', function ($query) {
                        $query->where('status', 'Pinjam');
                    })
                    ->orderBy('no_pinjam', 'DESC')
                    ->get();
            } else {
                $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();

                $data_pinjam = Pinjam::with(['pinjam_detail' => function ($query) {
                        $query->where('status', 'Pinjam');
                    }, 'pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam', 'anggota'])
                    ->whereHas('pinjam_detail', function ($query) {
                        $query->where('status', 'Pinjam');
                    })
                    ->whereBetween('tgl_pinjam', [$startDate, $endDate])
                    ->orderBy('no_pinjam', 'DESC')
                    ->get();
            }

            return DataTables::of($data_pinjam)
                ->addIndexColumn()
                ->addColumn('tgl_pinjam', function ($row) {
                    return Carbon::parse($row->tgl_pinjam)->format('d-m-Y');
                })
                ->addColumn('judul_buku', function ($row) {
                    $judul = [];
                    foreach ($row->pinjam_detail as $detail) {
                        $judul[] = $detail->buku->judul_buku ?? '-';
                    }
                    return implode('<br>', $judul);
                })
                ->addColumn('tgl_kembali', function ($row) {
                    $tgl = [];
                    foreach ($row->pinjam_detail as $detail) {
                        $tgl[] = Carbon::parse($detail->tgl_kembali)->format('d-m-Y');
                    }
                    return implode('<br>', $tgl);
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-info">Pinjam</span>';
                })
                ->addColumn('gambar', function ($row) {
                    $html = '';
                    foreach ($row->pinjam_detail as $detail) {
                        $html .= '<img src="' . asset('storage/' . $detail->buku->image) . '" 
                                       style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;"><br>';
                    }
                    return $html;
                })
                ->addColumn('anggota', function ($row) {
                    return $row->anggota->nama ?? '-';
                })
                ->addColumn('petugas', function ($row) {
                    return $row->petugas_pinjam->nama ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    $html = '<div class="d-inline">';
                    foreach ($row->pinjam_detail as $detail) {
                        $html .= '<form action="' . route('admin.transaksi.pinjam.kembalikanBuku', [$row->no_pinjam, $detail->id_buku]) . '" method="POST" class="d-inline">';
                        $html .= csrf_field();
                        $html .= method_field('PUT');
                        $html .= '<button type="button" class="btn btn-xs btn-primary kembalikan-buku">Kembalikan</button>';
                        $html .= '</form><br>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['judul_buku', 'tgl_kembali', 'status', 'gambar', 'aksi'])
                ->make(true);
        }
    }

    // ============================================================
    // EXPORT PDF
    // ============================================================
    public function printPinjam(Request $request)
{
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $data_pinjam = Pinjam::with(['pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam', 'anggota'])
        ->whereHas('pinjam_detail', function ($query) {
            $query->where('status', 'Pinjam');
        })
        ->whereBetween('tgl_pinjam', [$startDate, $endDate])
        ->orderBy('no_pinjam', 'DESC')
        ->get();

    return view('admin.pinjam.print_pinjam', compact('data_pinjam', 'startDate', 'endDate'));
}
    // ============================================================
    // EXPORT EXCEL
    // ============================================================
    public function exportExcelPinjam(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(new PinjamExport($startDate, $endDate), 'transaksi_pinjam.xlsx');
    }

    public function exportCsvPinjam(Request $request)
{
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $data_pinjam = Pinjam::with(['pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam', 'anggota'])
        ->whereHas('pinjam_detail', function ($query) {
            $query->where('status', 'Pinjam');
        })
        ->whereBetween('tgl_pinjam', [$startDate, $endDate])
        ->orderBy('no_pinjam', 'DESC')
        ->get();

    // Nama file
    $filename = 'transaksi_pinjam_' . date('Ymd_His') . '.csv';

    // Header HTTP
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0',
    ];

    // Callback untuk generate CSV
    $callback = function () use ($data_pinjam, $startDate, $endDate) {
        $file = fopen('php://output', 'w');

        // BOM untuk Excel support UTF-8
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Info periode
        fputcsv($file, ['LAPORAN TRANSAKSI PEMINJAMAN BUKU']);
        fputcsv($file, ['E-Library UNM - Universitas Nusa Mandiri']);
        fputcsv($file, ['Periode:', $startDate . ' s/d ' . $endDate]);
        fputcsv($file, ['Total:', $data_pinjam->count() . ' transaksi']);
        fputcsv($file, []); // Baris kosong

        // Header tabel
        fputcsv($file, [
            'No',
            'No. Pinjam',
            'Tgl. Pinjam',
            'Tgl. Kembali',
            'Judul Buku',
            'Status',
            'Anggota',
            'Petugas Pinjam',
        ]);

        // Data
        $no = 1;
        foreach ($data_pinjam as $pinjam) {
            foreach ($pinjam->pinjam_detail as $detail) {
                fputcsv($file, [
                    $no++,
                    $pinjam->no_pinjam,
                    \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d-m-Y'),
                    \Carbon\Carbon::parse($detail->tgl_kembali)->format('d-m-Y'),
                    $detail->buku->judul_buku ?? '-',
                    $detail->status,
                    $pinjam->anggota->nama ?? '-',
                    $pinjam->petugas_pinjam->nama ?? '-',
                ]);
            }
        }

        // Footer
        fputcsv($file, []);
        fputcsv($file, ['Dicetak pada:', \Carbon\Carbon::now()->translatedFormat('d F Y H:i')]);

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    // ============================================================
    // KEMBALIKAN BUKU
    // ============================================================
    public function kembalikanBuku($no_pinjam, $id_buku)
    {
        $pinjamDetail = PinjamDetail::where('no_pinjam', $no_pinjam)
            ->where('id_buku', $id_buku)
            ->first();

        if ($pinjamDetail) {
            $pinjamDetail->tgl_pengembalian = now();
            $pinjamDetail->status = 'Kembali';
            $pinjamDetail->id_petugas_kembali = Auth::user()->id;
            $pinjamDetail->save();

            DB::table('buku')->where('id', $id_buku)->update([
                'stok' => DB::raw('stok + 1'),
                'dipinjam' => DB::raw('dipinjam - 1'),
            ]);

            return response()->json(['success' => 'Buku berhasil dikembalikan.']);
        }

        return response()->json(['error' => 'Detail pinjaman tidak ditemukan.'], 404);
    }

    // ============================================================
    // PENGEMBALIAN INDEX
    // ============================================================
    public function pengembalian_index()
    {
        $data_pinjam = Pinjam::with(['pinjam_detail' => function ($query) {
                $query->where('status', 'Kembali');
            }, 'pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam'])
            ->whereHas('pinjam_detail', function ($query) {
                $query->where('status', 'Kembali');
            })
            ->orderBy('no_pinjam', 'DESC')
            ->get();

        return view('admin.pinjam.pengembalian_index', compact('data_pinjam'));
    }

    // ============================================================
    // MEMBER — Sedang Pinjam
    // ============================================================
    public function sedangPinjam(User $user)
    {
        $sedang_pinjam = Pinjam::with(['pinjam_detail' => function ($query) {
                $query->where('status', 'Pinjam');
            }, 'pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam'])
            ->where('id_user', $user->id)
            ->orderBy('no_pinjam', 'ASC')
            ->get();

        if (count($sedang_pinjam) == 0) {
            return redirect()->route('member.index')->with('info', 'Tidak ada buku yang sedang dipinjam');
        }

        return view('member.sedang_pinjam', compact('sedang_pinjam'));
    }

    // ============================================================
    // MEMBER — Riwayat Pinjam
    // ============================================================
    public function riwayatPinjam(User $user)
    {
        $riwayat_pinjam = Pinjam::with('pinjam_detail', 'pinjam_detail.buku', 'pinjam_detail.petugas_kembali', 'petugas_pinjam')
            ->where('id_user', $user->id)
            ->orderBy('no_pinjam', 'ASC')
            ->get();

        if (count($riwayat_pinjam) == 0) {
            return redirect()->route('member.index')->with('info', 'Tidak ada riwayat peminjaman');
        }

        return view('member.riwayat_pinjam', compact('riwayat_pinjam'));
    }
}