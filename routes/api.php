<?php

use App\Http\Controllers\Api\ApiPelangganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ApiPelangganController::class, 'register']);
//Route::post('login', [ApiPelangganController::class, 'login']);
Route::post('login', [ApiPelangganController::class, 'login'])->middleware('throttle:5,1');
Route::middleware('auth:sanctum')->post('/logout', [ApiPelangganController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/pelanggan/profile', [ApiPelangganController::class, 'getProfile']);

Route::middleware('auth:sanctum')->get('jenis_layanan', [ApiPelangganController::class, 'index']);
Route::middleware('auth:sanctum')->post('/pesanan/store', [ApiPelangganController::class, 'store']);
Route::middleware('auth:sanctum')->get('/pesanan/history-pesanan', [ApiPelangganController::class, 'historyPelanggan']);
Route::middleware('auth:sanctum')->post('/pelanggan/update-profile', [ApiPelangganController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/cuaca', [ApiPelangganController::class, 'getCuaca']);
Route::get('/notifikasi-masuk', [ApiPelangganController::class, 'notifikasiMasuk']);
Route::middleware('auth:sanctum')->get('/notifikasi-keluar', [ApiPelangganController::class, 'notifikasiKeluar']);
Route::middleware('auth:sanctum')->delete('/notifikasi-hapus', [ApiPelangganController::class, 'notifikasiHapus']);

Route::middleware('auth:sanctum')->post('/review', [ApiPelangganController::class, 'addReview']);
Route::middleware('auth:sanctum')->get('/review/layanan', [ApiPelangganController::class, 'getAllReviews']);
