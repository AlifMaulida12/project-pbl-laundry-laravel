@extends('layouts.app')
@section('title', 'Profile Anda')
@section('content')
    <hr />
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Anda</h4>
                    </div>
                    <div class="row" id="res"></div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Nama</label>
                            <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama"
                                value="{{ Auth::user()->nama_pelanggan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Alamat</label>
                            <input type="text" name="alamat_pelanggan" class="form-control" placeholder="Alamat"
                                value="{{ Auth::user()->alamat_pelanggan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Password Baru">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Konfirmasi Ulang Password Baru">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Nomor Hp</label>
                            <input type="text" name="nomor_hp" class="form-control" placeholder="Nomor HP"
                                value="{{ Auth::user()->nomor_hp }}">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Email"
                                value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <button id="btn" class="btn btn-primary profile-button" type="submit">Update Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
