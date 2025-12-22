<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data notifikasi yang belum dibaca
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');

        // Filter berdasarkan jenis layanan
        $jenis_layanan_filter = $request->input('jenis_layanan_filter');

        // Mengambil data review dengan paginasi
        $reviews = Review::query();

        if ($jenis_layanan_filter) {
            $reviews->whereHas('pesanan', function ($query) use ($jenis_layanan_filter) {
                $query->where('id_jenis_layanan', $jenis_layanan_filter); // Pastikan nama kolom sesuai
            });
        }


        // Mengambil data pelanggan beserta review-nya dengan paginasi
        $reviews = $reviews->with(['pesanan', 'pelanggan'])->paginate(20);

        // Mengambil data pelanggan untuk ditampilkan
        $pelanggans = Pelanggan::with('reviews')->paginate(20);

        // Mengambil data jenis layanan untuk dropdown filter
        $jenisLayanans = JenisLayanan::all();

        // Kirimkan variabel ke view
        return view('reviews.index', compact('notifikasis', 'pelanggans', 'reviews', 'jenisLayanans'));
    }

    public function show($id)
    {
        // Menampilkan detail ulasan berdasarkan ID
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $review = Review::with(['pesanan.jenis_layanan', 'pelanggan'])->findOrFail($id);
        return view('reviews.show', compact('review', 'notifikasis'));
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        toastr()->success('Review berhasil dihapus.');
        return redirect()->route('reviews.index');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');

        // Mengambil data review berdasarkan nama pelanggan
        $reviews = Review::whereHas('pelanggan', function ($query) use ($search) {
            $query->where('nama_pelanggan', 'like', '%' . $search . '%'); // Pastikan nama kolom sesuai
        })->with('pesanan')->paginate(20); // Menggunakan paginate

        // Mengambil data jenis layanan untuk dropdown filter
        $jenisLayanans = JenisLayanan::all();

        return view('reviews.index', compact('reviews', 'notifikasis', 'jenisLayanans'));
    }
}
