@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

<div>
    <div class="mb-4 text-center">
        <h2 class="h5 mb-1">Lupa Password? 🔐</h2>
        <p class="text-muted small">Masukkan email Anda untuk mendapatkan link reset password</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                placeholder="nama@example.com"
                required
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Kirim Link Reset Password</button>
        </div>
    </form>

    <div class="mt-3 text-center small">
        <a href="{{ route('login') }}" class="text-decoration-none">← Kembali ke Login</a>
    </div>
</div>

@endsection
