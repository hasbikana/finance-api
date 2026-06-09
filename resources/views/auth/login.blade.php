@extends('layouts.auth')
@section('title', 'FinanceApp - Login')
@section('content')
<div class="text-center mb-4">
    <i class="bi bi-wallet2 display-4" style="color: #0F766E;"></i>
    <h4 class="fw-bold mt-2" style="color: #0F766E;">FinanceApp</h4>
    <p class="text-muted small">Login untuk mengelola keuangan Anda</p>
</div>
<form method="POST" action="{{ route('web.login.submit') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-medium">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
        </div>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-medium">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
        </div>
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Masuk</button>
</form>
<p class="text-center mt-3 mb-0 small">Belum punya akun? <a href="{{ route('web.register') }}" style="color: #0F766E;">Daftar</a></p>
@endsection
