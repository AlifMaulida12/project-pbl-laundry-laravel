@extends('layouts.app')
@section('title', 'Data Pelanggan')

@section('contents')

    <!-- Topbar Search-->
    <form action="{{ route('pelanggan.search') }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari Pelanggan..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Data Pelanggan</h1>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Data
            Pelanggan</a><!--MENGARAH ROUTE pelanggan.create-->
    </div>
    <hr />
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Nomor Hp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($pelanggans->count() > 0)
                    @foreach ($pelanggans as $index => $pelanggan)
                        <tr>
                            <!-- Hitung nomor urut berdasarkan halaman saat ini dan posisi item -->
                            <td class="align-middle">
                                {{ ($pelanggans->currentPage() - 1) * $pelanggans->perPage() + $index + 1 }}</td>
                            <td class="align-middle">{{ $pelanggan->nama_pelanggan }}</td>
                            <td class="align-middle">{{ $pelanggan->alamat_pelanggan }}</td>
                            <td class="align-middle">{{ $pelanggan->nomor_hp }}</td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('pelanggan.show', $pelanggan->id) }}" type="button"
                                        class="btn btn-secondary">Detail</a><!--MENGARAH ROUTE pelanggan.show-->
                                    <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" type="button"
                                        class="btn btn-warning">Edit</a><!--MENGARAH ROUTE pelanggan.edit-->
                                    <form action="{{ route('pelanggan.delete', $pelanggan->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin Menghapus?')"><!--MENGARAH ROUTE pelanggan.delete-->
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5">Data Kosong</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Tambahkan link pagination -->
    <div class="d-flex justify-content-center">
        {{ $pelanggans->appends(['search' => request()->get('search')])->links('pagination::bootstrap-4') }}
    </div>
@endsection
