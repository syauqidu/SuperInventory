@extends('layouts.auth')@extends('layouts.auth')



@section('title', 'Reset Password')@section('title', 'Reset Password')



@section('content')@section('content')

<div class="card">

    <div class="card-body p-4"><div>

        <div class="text-center mb-4">    <div class="mb-4 text-center">

            <h4 class="fw-bold">Reset Password</h4>        <h2 class="h5 mb-1">Reset Password 🔑</h2>

            <p class="text-muted small">Masukkan password baru untuk akun Anda</p>        <p class="text-muted small">Masukkan password baru Anda</p>

        </div>    </div>



        @if($errors->any())    <form method="POST" action="{{ route('password.update') }}">

            <div class="alert alert-danger">        @csrf

                @foreach($errors->all() as $error)        <input type="hidden" name="token" value="{{ $token }}">

                    <div>{{ $error }}</div>        <input type="hidden" name="email" value="{{ $email }}">

                @endforeach

            </div>        <div class="mb-3">

        @endif            <label for="password" class="form-label">Password Baru</label>

            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required autofocus>

        <form method="POST" action="{{ route('password.update') }}">            @error('password')

            @csrf                <div class="invalid-feedback">{{ $message }}</div>

                        @enderror

            <input type="hidden" name="token" value="{{ $token }}">        </div>

            <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">

            <div class="mb-3">            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>

                <label for="email" class="form-label">Email</label>            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password" required>

                <input         </div>

                    type="email" 

                    class="form-control"         <div class="d-grid">

                    id="email"             <button type="submit" class="btn btn-primary">Reset Password</button>

                    value="{{ $email }}"        </div>

                    disabled    </form>

                >

            </div>    <div class="mt-3 text-center small">

        <a href="{{ route('login') }}" class="text-decoration-none">← Kembali ke Login</a>

            <div class="mb-3">    </div>

                <label for="password" class="form-label">Password Baru</label></div>

                <input 

                    type="password" @endsection

                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password"
                    placeholder="Minimal 6 karakter"
                    required
                    autofocus
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    placeholder="Ketik ulang password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Reset Password
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
