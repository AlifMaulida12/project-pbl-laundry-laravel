<?php
namespace App\Http\Controllers;
use App\Models\JenisLayanan;
class JenisLayananController extends Controller
{
    public function index(){//fungsi menampilkan semua data di index
        $jenisLayanans = JenisLayanan::all();
        return view('jenis.index', compact('jenisLayanans'));
        }
}
