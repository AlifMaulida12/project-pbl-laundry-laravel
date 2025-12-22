<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\JenisLayanan;
use App\Models\Notifikasi;
use App\Models\Review;
use App\Models\NotifikasiPelanggan;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use File;
use Carbon\Carbon;

class ApiPelangganController extends Controller
{
    //register
    public function register(Request $request) //fungsi register
    {
        $rules = [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|confirmed',
            'nomor_hp' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Buat data baru di tabel user
        $req = $request->all();
        $req['password'] = Hash::make($req['password']);
        //photo
        $initials = strtoupper(substr($req['nama_pelanggan'], 0, 1));
        $req['photo_profile'] = $request->filled('photo_profile') ? $req['photo_profile'] : 'https://ui-avatars.com/api/?background=random&rounded=true&name=' . urlencode($initials);
        $req['level'] = 'guest';

        $pelanggan = Pelanggan::create($req);
        $token = $pelanggan->createToken('Personal Access Token')->plainTextToken;

        $response = [
            'user' => $pelanggan,
            'token' => $token,
        ];

        return response()->json($response, 200);
    }

    //login
    public function login(Request $req)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);
        // cari email user di tabel pelanggan
        $pelanggan = pelanggan::where('email', $req->email)->first();
        // apabila email ditemukan dan password benar
        if ($pelanggan && Hash::check($req->password, $pelanggan->password)) {
            $token = $pelanggan->createToken('Personal Access Token')->plainTextToken;
            $response = ['user' => $pelanggan, 'token' => $token];
            return response()->json($response, 200);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
    }

    //logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Revoke semua token yang terkait dengan pengguna yang sedang login
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    //jenis layanan
    public function index()
    { //menampilkan jenis layanan
        $jenisLayanans = JenisLayanan::orderBy('id', 'asc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data jenis layanan berhasil diambil',
            'data' => $jenisLayanans
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user(); // Mendapatkan data pengguna yang sedang login

        if ($user) {
            // Filter data yang ingin ditampilkan
            $profileData = [
                'photo_profile' => $user->photo_profile,
                'nama_pelanggan' => $user->nama_pelanggan,
                'alamat_pelanggan' => $user->alamat_pelanggan,
                'nomor_hp' => $user->nomor_hp,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Data profil pelanggan berhasil diambil',
                'data' => $profileData,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak dapat menemukan data profil pelanggan',
        ], 404);
    }

    //update profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Mengambil user yang terautentikasi

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'nomor_hp' => 'required',
            // 'photo_profile' => 'nullable|image|mimes:jpg,png,bmp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui profil',
                'errors' => $validator->errors()
            ], 200);
        }

        // Perbarui informasi profil pelanggan
        $user->nama_pelanggan = $request->nama_pelanggan;
        $user->alamat_pelanggan = $request->alamat_pelanggan;
        $user->nomor_hp = $request->nomor_hp;
        // // Perbarui foto profil jika ada
        // if ($request->hasFile('photo_profile')) {
        //     $photoPath = $request->file('photo_profile')->store('profile_photos', 'public');
        //     $user->photo_profile = asset('storage/' . $photoPath);
        // }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil pelanggan berhasil diperbarui',
            'data' => $user // Mengembalikan data pelanggan yang telah diperbarui
        ], 200);
    }

    //membuat pesanan
    public function store(Request $request)
    {
        $rules = [
            'id_jenis_layanan' => 'required|exists:jenis_layanan,id',
            'id_status_laundry' => 'nullable|exists:status_laundry,id',
            'waktu_pesanan_datang' => 'nullable|date',
            'total_harga' => 'nullable',
            'berat' => 'nullable',
            'waktu_pesanan_selesai' => 'nullable|date',
            'metode_pengambilan' => 'required|in:pickup,dropoff',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Membuat Pesanan',
                'errors' => $validator->errors()
            ], 200);
        }

        // Periksa apakah pengguna terautentikasi sebelum mendapatkan ID Pelanggan
        if (Auth::check()) {
            $idPelanggan = Auth::id();
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada pengguna yang terautentikasi',
            ], 401);
        }

        // Panggil fungsi checkOrderLimit untuk memeriksa batas pesanan
        $checkOrder = $this->checkOrderLimit($request);

        // Jika pesanan tidak melebihi batas, lanjutkan dengan membuat pesanan baru
        if ($checkOrder->getStatusCode() == 200) {
            //mengambil harga layanan berdasarkan id jenis layanan
            $jenisLayanan = JenisLayanan::findOrFail($request->id_jenis_layanan);
            $hargaLayanan = $jenisLayanan->harga;

            //hitung total harga
            $totalHarga = $request->berat * $hargaLayanan;

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

            // Membuat pesanan baru
            $pesanan = new Pesanan();
            $pesanan->id_jenis_layanan = $request->id_jenis_layanan;
            $pesanan->id_pelanggan = $idPelanggan; // Mengisi dengan ID Pelanggan yang terautentikasi
            $pesanan->id_status_laundry = $request->filled('id_status_laundry') ? $request->id_status_laundry : 1; // Menggunakan nilai default
            $pesanan->waktu_pesanan_datang = $request->filled('waktu_pesanan_datang') ? $request->waktu_pesanan_datang : now();
            $pesanan->total_harga = $totalHarga;
            $pesanan->berat = $request->berat;
            $pesanan->estimasi_selesai = $estimasiSelesai;
            $pesanan->metode_pengambilan = $request->metode_pengambilan;
            $pesanan->save();

            $notif = new Notifikasi();
            $notif->id_pesanan = $pesanan->id;
            $notif->save();

            // setelah menyimpan pesanan
            return response()->json([
                'status' => true,
                'message' => 'Pesanan Dibuat',
            ], 200);
        } else {
            // Jika pesanan melebihi batas, kembalikan respons dari fungsi checkOrderLimit
            return $checkOrder;
        }
    }

    //menampilkan history pemesanan pelanggan
    public function historyPelanggan(Request $request)
    {
        $user = Auth::user(); // Mengambil user yang terautentikasi

        $pesanan = Pesanan::with('jenis_layanan', 'pelanggan', 'status_laundry')
            ->where('id_pelanggan', $user->id)
            ->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data pesanan tidak ditemukan untuk pelanggan ini',
                'data' => []
            ], 404);
        }

        // Format data untuk respons JSON
        $formattedData = [];
        foreach ($pesanan as $item) {
            $formattedData[] = [
                'id' => $item->id,
                'jenis_layanan' => $item->jenis_layanan->nama_layanan,
                'pelanggan' => $item->pelanggan->nama_pelanggan,
                'status_laundry' => $item->status_laundry->status,
                'waktu_pesanan_datang' => $item->waktu_pesanan_datang,
                'total_harga' => $item->total_harga,
                'estimasi_selesai' => $item->estimasi_selesai,
                'status_pembayaran' => $item->status_pembayaran,
                'berat' => $item->berat,
                'waktu_pesanan_selesai' => $item->waktu_pesanan_selesai,
                'metode_pengambilan' => $item->metode_pengambilan,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Data pesanan berhasil diambil',
            'data' => $formattedData
        ], 200);
    }

    public function getCuaca(Request $request)
    {
        // Ambil kota Banyuwangi
        $city = 'Banyuwangi';

        // Buat URL request API OpenWeather
        $apiKey = '49dfb6976043c3864594277b36b26b29';
        $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey";

        // Kirim permintaan GET ke OpenWeather API
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        // Tangani respons dari API
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $data = json_decode($response->getBody(), true);

            // format data cuaca
            $weatherData = [
                'kota' => $data['name'],
                'suhu' => $data['main']['temp'],
                'cuaca' => $data['weather'][0]['main'],
                'deskripsi_cuaca' => $data['weather'][0]['description'],
                'kelembapan' => $data['main']['humidity'],
                'kecepatan_angin' => $data['wind']['speed'],
            ];

            return response()->json([
                'status' => true,
                'message' => 'Data cuaca berhasil diambil',
                'data' => $weatherData,
            ], 200);
        } else {
            // Tangani kasus ketika permintaan gagal
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data cuaca',
                'statusCode' => $statusCode,
            ], $statusCode);
        }
    }

    public function estimasiSelesaiBerdasarkanCuaca($weatherDescription)
    {
        $estimasi = null;

        switch ($weatherDescription) {
            case 'clear sky':
            case 'few clouds':
                $estimasi = Carbon::now()->addDays(2);
                break;
            case 'scattered clouds':
            case 'overcast clouds':
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
                $estimasi = Carbon::now()->addDays(1);
                break;
        }

        return $estimasi;
    }

    public function notifikasiMasuk()
    {
        $newOrders = Pesanan::where('id_status_laundry', 1)->get();

        return response()->json([
            'status' => true,
            'message' => 'Data pesanan baru berhasil diambil',
            'data' => $newOrders,
        ], 200);
    }

    public function notifikasiKeluar()
    {
        $user = Auth::user();

        // Memeriksa apakah pengguna terotentikasi
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User belum login',
            ], 401); // Unauthorized
        }

        // Mendapatkan pesanan berdasarkan pengguna yang sedang login
        $newOrders = Pesanan::select(
            'pesanan.id as id_pesanan',
            'pesanan.id_pelanggan',
            'jenis_layanan.nama_layanan',
            'status_laundry.status'
        )
            ->leftJoin('status_laundry', 'pesanan.id_status_laundry', '=', 'status_laundry.id')
            ->leftJoin('jenis_layanan', 'pesanan.id_jenis_layanan', '=', 'jenis_layanan.id')
            ->where('pesanan.id_status_laundry', 3) // Status pesanan selesai (sesuaikan dengan kode status yang benar)
            ->where('pesanan.id_pelanggan', $user->id)
            ->get();

        // Jika tidak ada pesanan selesai
        if ($newOrders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada pesanan selesai untuk pengguna ini.',
            ], 404);
        }

        // Menyimpan notifikasi untuk setiap pesanan selesai
        foreach ($newOrders as $order) {
            NotifikasiPelanggan::create([
                'id_pesanan' => $order->id_pesanan, // Menggunakan ID pesanan yang benar
                'id_pelanggan' => $user->id,
                'nama_layanan' => $order->nama_layanan,
                'status' => 'selesai', // Status selesai
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi berhasil disimpan.',
            'data' => $newOrders,
        ], 200);
    }

    public function notifikasiHapus(Request $request)
{

    $user = Auth::user();

    // Memeriksa apakah pengguna terotentikasi
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User belum login',
        ], 401); // Unauthorized
    }

    // Validasi input ID Pesanan
    $validated = $request->validate([
        'id_pesanan' => 'required|integer', // Pastikan ID Pesanan yang dikirim valid
    ]);

    // Mencari notifikasi yang sesuai dengan ID Pesanan dan ID Pelanggan
    $notifikasi = NotifikasiPelanggan::where('id_pesanan', $validated['id_pesanan'])
        ->where('id_pelanggan', $user->id)
        ->first(); // Mengambil satu data notifikasi

    // Memeriksa apakah notifikasi ditemukan
    if ($notifikasi) {
        // Menghapus notifikasi
        $notifikasi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ], 200);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Notifikasi atau pesanan tidak ditemukan',
        ], 404); // Not Found
    }
}

    // Fungsi untuk memeriksa apakah pelanggan telah memesan dalam 3 jam terakhir
    public function checkOrderLimit(Request $request)
    {
        // Ambil ID pelanggan dari pengguna yang terautentikasi
        $idPelanggan = Auth::id();

        // Ambil waktu saat ini
        $now = Carbon::now();

        // batas 3 jam sebelum memesan lagi
        $threeHoursAgo = $now->subHours(0);
        //$threeHoursAgo = $now->subMinutes(0);

        // Cek apakah ada pesanan dalam 3 jam terakhir
        $orderCount = Pesanan::where('id_pelanggan', $idPelanggan)
            ->where('waktu_pesanan_datang', '>=', $threeHoursAgo)
            ->count();

        // Jika ada pesanan dalam 3 jam terakhir, kembalikan respons error
        if ($orderCount > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Anda hanya dapat melakukan pesanan sekali dalam 3 jam',
            ], 400);
        }

        // Jika tidak ada pesanan dalam 3 jam terakhir, kembalikan respons berhasil
        return response()->json([
            'status' => true,
            'message' => 'Anda dapat membuat pesanan',
        ], 200);
    }

    //fungsi review
    public function addReview(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'id_pesanan' => 'required|exists:pesanan,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        // Cek apakah pesanan ini milik pelanggan yang sedang login
        $pesanan = Pesanan::where('id', $request->id_pesanan)
            ->where('id_pelanggan', $user->id)
            ->first();

        if (!$pesanan) {
            return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Simpan review
        $review = Review::create([
            'id_pelanggan' => $user->id,
            'id_pesanan' => $request->id_pesanan,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['status' => true, 'message' => 'Review berhasil ditambahkan'], 200);
    }

    public function getAllReviews()
    {
        // Fungsi Mengambil semua data review
        $reviews = Review::with(['pelanggan', 'pesanan.jenisLayanan'])->get();

        // Tampilkan dalam format JSON
        $reviewsData = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'review' => $review->review,
                'nama_pelanggan' => $review->pelanggan->nama_pelanggan,
                'nama_layanan' => $review->pesanan->jenisLayanan->nama_layanan,
            ];
        });

        // Menampilkan hasil review dalam format JSON
        return response()->json($reviewsData);
    }
}
