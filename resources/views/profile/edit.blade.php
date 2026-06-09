@extends('layouts.app')
@section('title', 'Profil - FinanceApp')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h4 class="fw-bold mb-4">Pengaturan Profil</h4>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Informasi Profil</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('web.profile.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Ubah Password</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('web.password.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
