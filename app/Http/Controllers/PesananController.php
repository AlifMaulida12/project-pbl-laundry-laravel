<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\StatusLaundry;
use App\Models\JenisLayanan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        //filter data pesanan berdasarkan status laundry yang dipilih admin
        $status_filter = $request->input('status_filter');
        $pesanans = Pesanan::with('status_laundry');

        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');

        if ($status_filter) {
            $pesanans->whereHas('status_laundry', function ($query) use ($status_filter) {
                $query->where('id', $status_filter);
            });
        }

        // Gunakan paginate untuk membatasi data yang diambil
        $pesanans = $pesanans->paginate(20);

        // Ambil data status laundry
        $statusLaundrys = StatusLaundry::all();

        return view('pesanan.index', compact('pesanans', 'notifikasis', 'statusLaundrys'));
    }

    public function show($id)
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pesanan = Pesanan::with(['jenis_layanan', 'pelanggan', 'status_laundry'])->findOrFail($id);

        $notif = Notifikasi::where('id_pesanan', $pesanan->id);
        if ($notif->count() > 0) {
            $notif = $notif->first();
            if ($notif->status == 0) {
                $notif->status = 1;
                $notif->save();
            }
        }

        return view('pesanan.show', compact('pesanan', 'notifikasis'));
    }

    public function create()
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        // Ambil data yang diperlukan untuk form
        $jenisLayanans = JenisLayanan::all();
        $pelanggans = Pelanggan::all();
        $statusLaundrys = StatusLaundry::all();

        return view('pesanan.create', compact('notifikasis', 'jenisLayanans', 'pelanggans', 'statusLaundrys'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'id_jenis_layanan' => 'required|exists:jenis_layanan,id',
            'id_pelanggan' => 'required|exists:user,id',
            'id_status_laundry' => 'required|exists:status_laundry,id',
            'total_harga' => 'nullable',
            'waktu_pesanan_datang' => 'nullable|date',
            'berat' => 'nullable|numeric|max:20',
            'status_pembayaran' => 'required|in:belum,dibayar',
            'estimasi_selesai' => 'nullable|date',
            'metode_pengambilan' => 'required|in:pickup,dropoff',
            'waktu_pesanan_selesai' => 'nullable|date',
        ]);
        if ($validatedData['status_pembayaran'] === 'dibayar') {
            // redirect ke halaman errCreateDibayar jika status_pembayaran === dibayar        
            return redirect()->route('pesanan.errCreateDibayar');
        }

        //mengambil harga layanan berdasarkan id jenis layanan
        $jenisLayanan = JenisLayanan::findOrFail($validatedData['id_jenis_layanan']);
        $hargaLayanan = $jenisLayanan->harga;

        // antisipasi total harga ditentukan secara manal
        if (!isset($validatedData['total_harga']) || is_null($validatedData['total_harga'])) {
            // Jika total_harga tidak diisi, jalankan fungsi perhitungan otomatis
            $jenisLayanan = JenisLayanan::findOrFail($validatedData['id_jenis_layanan']);
            $hargaLayanan = $jenisLayanan->harga;
            $totalHarga = $validatedData['berat'] * $hargaLayanan;
            $validatedData['total_harga'] = $totalHarga;
        }

        //estimasi cucian selesai berdasarkan status cuaca
        // Mendapatkan data cuaca dari OpenWeather API
        $apiKey = '49dfb6976043c3864594277b36b26b29'; //key
        $cityName = 'Banyuwangi'; // Nama kota
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apiKey";

        // Inisialisasi cURL
        $curl = curl_init();

        // Set URL dan opsi cURL
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Eksekusi cURL dan simpan respons
        $response = curl_exec($curl);

        // Tutup koneksi cURL
        curl_close($curl);

        // Ubah respons JSON menjadi array asosiatif
        $weatherData = json_decode($response, true);

        // Ambil keadaan cuaca dari data yang diperoleh
        $weatherDescription = isset($weatherData['weather'][0]['description']) ? $weatherData['weather'][0]['description'] : '';

        // Menentukan estimasi selesai berdasarkan keadaan cuaca
        $estimasiSelesai = $this->estimasiSelesaiBerdasarkanCuaca($weatherDescription);

        // Tambahkan estimasi_selesai ke dalam data yang akan disimpan
        $validatedData['estimasi_selesai'] = $estimasiSelesai;

        // Simpan data ke dalam database
        $pesanan = Pesanan::create($validatedData);

        $notif = new Notifikasi();
        $notif->id_pesanan = $pesanan->id;
        $notif->save();

        toastr()->success('Pesanan berhasil dibuat');
        return redirect()->route('pesanan.index');
        // ->with('success', 'Pesanan berhasil dibuat');
    }

    public function edit($id)
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pesanan = Pesanan::findOrFail($id);
        // Ambil data yang diperlukan untuk diedit
        $jenisLayanans = JenisLayanan::all();
        $pelanggans = Pelanggan::all();
        $statusLaundrys = StatusLaundry::all();

        return view('pesanan.edit', compact('notifikasis', 'pesanan', 'jenisLayanans', 'pelanggans', 'statusLaundrys'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validatedData = $request->validate([
            'id_jenis_layanan' => 'required|exists:jenis_layanan,id',
            'id_pelanggan' => 'required|exists:user,id',
            'id_status_laundry' => 'required|exists:status_laundry,id',
            'total_harga' => 'nullable|numeric',  // Menambahkan validasi untuk total_harga
            'waktu_pesanan_datang' => 'nullable|date',
            'berat' => 'nullable|numeric|max:20',
            'status_pembayaran' => 'required|in:belum,dibayar',
            'estimasi_selesai' => 'nullable|date',
            'metode_pengambilan' => 'required|in:pickup,dropoff',
            'waktu_pesanan_selesai' => 'nullable|date',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // antisipasi total harga ditentukan secara manal
        if (!isset($validatedData['total_harga']) || is_null($validatedData['total_harga'])) {
            // Jika total_harga tidak diisi, jalankan fungsi perhitungan otomatis
            $jenisLayanan = JenisLayanan::findOrFail($validatedData['id_jenis_layanan']);
            $hargaLayanan = $jenisLayanan->harga;
            $totalHarga = $validatedData['berat'] * $hargaLayanan;
            $validatedData['total_harga'] = $totalHarga;
        }
        $pesanan->update($validatedData);

        toastr()->success('Pesanan berhasil diperbarui');
        return redirect()->route('pesanan.index');
    }


    public function delete($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Cek apakah status pembayaran dan status laundry memenuhi syarat agar tidak dapat dihapus
        if ($pesanan->status_pembayaran === 'dibayar' && $pesanan->id_status_laundry === 3) {
            // Tampilkan pesan error dan cegah penghapusan
            toastr()->error('Pesanan tidak dapat dihapus dikarenakan sudah dibayar dan status laundry sudah selesai.');
            return redirect()->route('pesanan.index');
        }

        // Jika syarat tidak terpenuhi, maka lanjutkan untuk menghapus pesanan
        $pesanan->delete();

        toastr()->success('Pesanan berhasil dihapus');
        return redirect()->route('pesanan.index');
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pesanans = Pesanan::whereHas('pelanggan', function ($query) use ($search) {
            $query->where('nama_pelanggan', 'like', '%' . $search . '%');
        })->paginate(20); // Use paginate instead of get

        // Ambil data status laundry
        $statusLaundrys = StatusLaundry::all();

        return view('pesanan.index', compact('pesanans', 'statusLaundrys', 'notifikasis'));
    }


    //penambahan hari secara otomatis berdasarkan status cuaca
    public function estimasiSelesaiBerdasarkanCuaca($weatherDescription)
    {
        $estimasi = null;

        switch ($weatherDescription) {
            case 'clear sky':
            case 'few clouds':
                $estimasi = Carbon::now()->addDays(2);
                break;
            case 'scattered clouds':
                //case 'overcast clouds':
            case 'broken clouds':
                $estimasi = Carbon::now()->addDays(3);
                break;
            case 'shower rain':
            case 'rain':
            case 'thunderstorm':
                $estimasi = Carbon::now()->addDays(4);
                break;
            case 'snow':
            case 'mist':
                $estimasi = Carbon::now()->addDays(5);
                break;
            default:
                // Jika deskripsi cuaca tidak ada yang cocok, tambahkan 1 hari saat pesanan ini datang
                $estimasi = Carbon::now()->addDays(1);
                break;
        }

        return $estimasi;
    }
    public function pendapatanPerbulan($year, $month)
    {
        $pendapatan = Pesanan::whereYear('waktu_pesanan_selesai', $year)
            ->whereMonth('waktu_pesanan_selesai', $month)
            ->where('status_pembayaran', 'dibayar') //kondisi waktu pesanan selesai harus sudah di isi dan status pembayaran menjadi dibayar
            ->sum('total_harga');

        return $pendapatan;
    }
}
