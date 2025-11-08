@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<div>
    <div class="mb-4 text-center">
        <h2 class="h5 mb-1">Reset Password 🔑</h2>
        <p class="text-muted small">Masukkan password baru Anda</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required autofocus>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
    </form>

    <div class="mt-3 text-center small">
        <a href="{{ route('login') }}" class="text-decoration-none">← Kembali ke Login</a>
    </div>
</div>

@endsection
