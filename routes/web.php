<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\OpenWheatherController;
use App\Http\Controllers\LaporanController;
use App\Models\Notifikasi;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('auth.login');
});

// Group routes untuk authentication (login, register, session, logout)
Route::group(['middleware' => 'web'], function () {
    //Route::get('daftar', [PelangganController::class, 'register'])->name('register');
    //Route::post('daftar', [PelangganController::class, 'store'])->name('simpan.register');
    Route::get('login', [PelangganController::class, 'login'])->name('login');
    Route::post('login', [PelangganController::class, 'prosesLogin'])->middleware('throttle:5,1')->name('proses.login');
    Route::get('logout', [PelangganController::class, 'logout'])->middleware('auth')->name('logout');
    
    //redirect jika ada kesalahan
    Route::get('invalid/credential', function () {return view('auth.errorPage.wrongpas');})->name('wrongpas');
    Route::get('email/invalid', function () {return view('auth.errorPage.invalidEmailFormat');})->name('emailFormatInvalid');
});



//yang sudah login saja dan memiliki role admin yang bisa mengakses fungsi ini
Route::middleware(['auth','verified','role:admin'])->group(function () {

    //dasboard
    Route::get('dashboard', function (Request $request) {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');

        $year = date('Y');
        $month = $request->input('month', date('n'));
        $selectedMonth = $request->input('month', date('n'));

        // Mendapatkan pendapatan perbulan
        $pendapatan = (new \App\Http\Controllers\PesananController())->pendapatanPerbulan($year, $month);

        // Mendapatkan laporan pendapatan bulanan
        $laporanPendapatanBulanan = (new \App\Http\Controllers\LaporanController())->getLaporanPendapatanBulanan($selectedMonth);

        return view('dashboard', compact('notifikasis', 'pendapatan', 'selectedMonth', 'laporanPendapatanBulanan'));
    })->name('dashboard');

    //grup fungsi pelanggan
    Route::prefix('/pelanggan')->group(function(){
        Route::get('/', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/show/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');
        Route::get('/create', [PelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/store', [PelangganController::class, 'storePelanggan'])->name('pelanggan.store');
        Route::get('/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/update/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/delete/{id}', [PelangganController::class, 'delete'])->name('pelanggan.delete');
        Route::get('/search', [PelangganController::class, 'search'])->name('pelanggan.search');
        
        //redirect jika ada kesalahan
        Route::get('/email/duplicate', function () {return view('pelanggan.errorPage.existEmail');})->name('existEmail');
        Route::get('/email/invalid', function () {return view('pelanggan.errorPage.invalidEmailFormat');})->name('invalidEmailFormat');
        Route::get('/nohp/duplicate', function () {return view('pelanggan.errorPage.duplicateNoHp');})->name('duplicateNoHp');
        Route::get('/password/invalidconfirm', function () {return view('pelanggan.errorPage.passConfirmInvalid');})->name('passConfirmInvalid');
    });

    //grup fungsi pesanan
    Route::prefix('/pesanan')->group(function(){
        Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('show/{id}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::get('/create', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/store', [PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/edit/{id}', [PesananController::class, 'edit'])->name('pesanan.edit');
        Route::put('/update/{id}', [PesananController::class, 'update'])->name('pesanan.update');
        Route::delete('/delete/{id}', [PesananController::class, 'delete'])->name('pesanan.delete');
        Route::get('/search', [PesananController::class, 'search'])->name('pesanan.search');

        //redirect jika ada kesalahan
        Route::get('create/pembayaran/invalid', function () {return view('pesanan.errorPage.errCreateDibayar');})->name('pesanan.errCreateDibayar');                
    });

    //grup fungsi review
    Route::prefix('/reviews')->group(function(){
        Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/show/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::delete('/delete/{id}', [ReviewController::class, 'delete'])->name('reviews.delete');
        Route::get('/search', [ReviewController::class, 'search'])->name('reviews.search');
    });

    //Route::get('/reviews', [PelangganController::class, 'listReview'])->name('reviews.index');//router review
    Route::prefix('/cuaca')->group(function(){
        Route::get('/', [OpenWheatherController::class, 'index'])->name('cuaca.index');
    });

    Route::get('dashboard', [LaporanController::class, 'dashboard'])->name('dashboard');

    Route::get('/dashboard/cetak-laporan', [LaporanController::class, 'cetaklaporan'])->name('dashboard.cetak-laporan');

});
