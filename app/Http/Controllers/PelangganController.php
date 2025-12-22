<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        // Mengambil data pelanggan beserta review-nya
        $pelanggans = Pelanggan::with('reviews')->paginate(20);

        return view('pelanggan.index', compact('notifikasis', 'pelanggans'));
    }

    public function show($id)
    { //fungsi menampilkan data secara detail
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.show', compact('notifikasis', 'pelanggan'));
    }

    public function register()
    { //MEMANGGIL VIEW REGISTER
        return view('auth/register');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'nomor_hp' => 'required'
        ])->validate();

        $level = $request->filled('level') ? $request->input('level') : 'guest';

        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_hp' => $request->nomor_hp,
            'level' => $level
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth.login'); // Memanggil view login
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $request->email)) {
            return redirect()->route('emailFormatInvalid');
        }

        // Periksa autentikasi pengguna menggunakan metode Auth::attempt()
        if (!Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Jika autentikasi gagal, redirect ke halaman dari routes
            return redirect()->route('wrongpas');
        }


        // Jika autentikasi berhasil, atur ulang sesi dan arahkan ke halaman dashboard
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate(); //mengahiri sesi login pengguna saat ini
        return redirect('/login'); //setelah logout akan diarahkan ke halaman login
    }

    //ini adalah fungsi crud data pelanggan apabila anda admin
    public function create()
    { //MEMANGGIL VIEW create pelanggan
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        return view('pelanggan.create', compact('notifikasis'));
    }

    public function storePelanggan(Request $request)
    {
        //cek dupliasi email
        $cekDuplikasiEmail = Pelanggan::where('email', $request->email)->first();
        if ($cekDuplikasiEmail) {
            return redirect()->route('existEmail');
        }

        //cek dupliasi no hp
        $cekDuplikasiNoHp = Pelanggan::where('nomor_hp', $request->nomor_hp)->first();
        if ($cekDuplikasiNoHp) {
            return redirect()->route('duplicateNoHp');
        }

        //cek format email
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $request->email)) {
            return redirect()->route('invalidEmailFormat');
        }

        //cek konfirmasi password
        if ($request->password !== $request->password_confirmation) {
            return redirect()->route('passConfirmInvalid');
        }

        $validatedData = $request->validate([
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'email' => 'required|email|unique:user,email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required|confirmed',
            'nomor_hp' => 'required',
            'photo_profile' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);



        $level = $request->filled('level') ? $request->input('level') : 'guest';

        $photo_profile = $request->file('photo_profile');
        $photo_profile_path = null;

        if ($photo_profile) {
            $photo_profile_path = $photo_profile->store('profile_photos', 'public');
        }

        Pelanggan::create([
            'nama_pelanggan' => $validatedData['nama_pelanggan'],
            'alamat_pelanggan' => $validatedData['alamat_pelanggan'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'nomor_hp' => $validatedData['nomor_hp'],
            'level' => $level,
            'photo_profile' => $photo_profile_path,
        ]);

        toastr()->success('Data pelanggan berhasil ditambahkan');
        return redirect()->route('pelanggan.index');
        // ->with('success', 'Data pelanggan berhasil ditambahkan');
    }


    public function edit($id)
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('notifikasis', 'pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        Validator::make($request->all(), [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'email' => 'required|email',
            'password' => 'sometimes|nullable|confirmed',
            'nomor_hp' => 'required',
            'level' => 'required'
        ])->validate();

        $data = [
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'level' => $request->level,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $photo_profile = $request->file('photo_profile');
        if ($photo_profile) {
            $photo_profile_path = $photo_profile->store('profile_photos', 'public');
            $data['photo_profile'] = $photo_profile_path;
        }

        $pelanggan->update($data);

        toastr()->success('Pelanggan berhasil diperbarui');
        return redirect()->route('pelanggan.index');
    }

    public function delete($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        toastr()->success('Pelanggan berhasil dihapus');
        return redirect()->route('pelanggan.index');
        // ->with('success', 'Pelanggan berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $pelanggans = Pelanggan::where('nama_pelanggan', 'like', '%' . $search . '%')
            ->paginate(20);

        return view('pelanggan.index', compact('pelanggans', 'notifikasis'));
    }
}
