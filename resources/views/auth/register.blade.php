@extends('layouts.auth')
@section('title', 'FinanceApp - Register')
@section('content')
<div class="text-center mb-4">
    <i class="bi bi-person-plus display-4" style="color: #0F766E;"></i>
    <h4 class="fw-bold mt-2" style="color: #0F766E;">Daftar Akun</h4>
    <p class="text-muted small">Buat akun untuk mulai mengelola keuangan</p>
</div>
<form method="POST" action="{{ route('web.register.submit') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-medium">Nama</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama lengkap" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-medium">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-medium">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-medium">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Daftar</button>
</form>
<p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="{{ route('web.login') }}" style="color: #0F766E;">Login</a></p>
@endsection
