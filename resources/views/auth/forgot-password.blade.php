@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold">Lupa Password?</h4>
            <p class="text-muted small">Masukkan email Anda untuk mendapatkan link reset password</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if(session('reset_link'))
                    <hr>
                    <small class="d-block mt-2">
                        <strong>Development Mode:</strong><br>
                        <a href="{{ session('reset_link') }}" class="text-break">{{ session('reset_link') }}</a>
                    </small>
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
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

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Kirim Link Reset Password
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none small">
                    ← Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
