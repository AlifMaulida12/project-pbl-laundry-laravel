<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\JenisLayanan;
use App\Models\status_laundry;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function dashboard(Request $request)
    {
        // Ambil notifikasi
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');

        // Ambil tahun dan bulan dari input
        $year = $request->input('year', date('Y'));
        $selectedMonth = (int) $request->input('month', date('n'));

        // Validasi bulan
        if ($selectedMonth < 0 || $selectedMonth > 12) {
            abort(400, "Bulan tidak valid");
        }

        // Mendapatkan pendapatan perbulan
        $pendapatan = $this->pendapatanPerbulan($year, $selectedMonth > 0 ? $selectedMonth : null);

        // Mendapatkan laporan pendapatan bulanan
        $laporanPendapatanBulanan = $this->getLaporanPendapatanBulanan($year, $selectedMonth > 0 ? $selectedMonth : null);

        // Menghitung total pelanggan dan pesanan untuk status selesai dan dibayar
        $query = Pesanan::query();

        // Jika bulan adalah 0, maka ambil semua data tanpa filter bulan
        if ($selectedMonth > 0) {
            $query->whereMonth('waktu_pesanan_selesai', $selectedMonth);
        } else {
            $query->whereYear('waktu_pesanan_selesai', $year);  // Ambil data sepanjang tahun
        }

        // Total pelanggan dan pesanan selesai
        $totalPelangganSelesai = $query->whereHas('status_laundry', function ($q) {
            $q->where('status', 'selesai');
        })
            ->where('status_pembayaran', 'dibayar')
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        $totalPesananSelesai = $query->whereHas('status_laundry', function ($q) {
            $q->where('status', 'selesai');
        })
            ->where('status_pembayaran', 'dibayar')
            ->count();

        // Reset query untuk menghitung pesanan yang belum selesai
        $query = Pesanan::query();

        // Jika bulan adalah 0, maka ambil semua data tanpa filter bulan
        if ($selectedMonth > 0) {
            $query->whereMonth('waktu_pesanan_selesai', $selectedMonth);
        }

        // Total pelanggan dan pesanan belum selesai
        $totalPelangganBelumSelesai = $query->whereHas('status_laundry', function ($q) {
            $q->where('status', '!=', 'selesai');
        })
            ->where('status_pembayaran', '!=', 'dibayar')
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        $totalPesananBelumSelesai = $query->whereHas('status_laundry', function ($q) {
            $q->where('status', '!=', 'selesai');
        })
            ->where('status_pembayaran', '!=', 'dibayar')
            ->count();

        return view('dashboard', compact(
            'notifikasis',
            'pendapatan',
            'year',
            'selectedMonth',
            'totalPelangganSelesai',
            'totalPesananSelesai',
            'totalPelangganBelumSelesai',
            'totalPesananBelumSelesai',
            'laporanPendapatanBulanan'
        ));
    }

    public function cetaklaporan(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('n')); // Pastikan parameter diteruskan dengan benar

        $cetaklaporanPendapatanBulanan = $this->getLaporanPendapatanBulanan($year, $selectedMonth);

        $monthNames = [
            0 => 'PER TAHUN',
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER',
        ];

        $selectedMonthName = $monthNames[$selectedMonth];

        return view('laporan.cetaklaporan', compact(
            'cetaklaporanPendapatanBulanan',
            'selectedMonthName',
        ));
    }


    public function pendapatanPerbulan($year, $month = null)
    {
        $query = Pesanan::whereYear('waktu_pesanan_selesai', $year);

        if ($month) {
            $query->whereMonth('waktu_pesanan_selesai', $month);
        }

        return $query->where('status_pembayaran', 'dibayar')
            ->sum('total_harga');
    }

    public function getLaporanPendapatanBulanan($year, $month = null)
    {
        $query = Pesanan::select(
            'user.nama_pelanggan as nama',
            'jenis_layanan.nama_layanan',
            DB::raw('COUNT(pesanan.id) as jumlah_pesanan'),
            'jenis_layanan.harga',
            DB::raw('SUM(pesanan.total_harga) as total_harga'),
            DB::raw('SUM(pesanan.berat) as total_berat')
        )
            ->join('user', 'pesanan.id_pelanggan', '=', 'user.id')
            ->join('jenis_layanan', 'pesanan.id_jenis_layanan', '=', 'jenis_layanan.id')
            ->join('status_laundry', 'pesanan.id_status_laundry', '=', 'status_laundry.id')
            ->where('status_laundry.status', 'selesai')
            ->where('pesanan.status_pembayaran', 'dibayar'); // Pastikan hanya menghitung yang sudah dibayar

        if ($month > 0) {
            // Jika bulan ditentukan, filter berdasarkan tahun dan bulan
            $query->whereYear('pesanan.waktu_pesanan_selesai', $year)
                ->whereMonth('pesanan.waktu_pesanan_selesai', $month);
        } else {
            // Jika bulan tidak ditentukan, hanya filter berdasarkan tahun
            $query->whereYear('pesanan.waktu_pesanan_selesai', $year);
        }

        return $query->groupBy(
            'user.nama_pelanggan',
            'jenis_layanan.nama_layanan',
            'jenis_layanan.harga'
        )
            ->orderByDesc('jumlah_pesanan')
            ->get();
    }
}
