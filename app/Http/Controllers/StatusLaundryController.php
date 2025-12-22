<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\StatusLaundry;

class StatusLaundryController extends Controller
{
    public function index()
    { //fungsi menampilkan semua data di index
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        $statusLaundrys = StatusLaundry::all();
        return view('status.index', compact('statusLaundrys', 'notifikasi'));
    }
}
