@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div>
        <div class="mb-4 text-center">
            <h2 class="h5 mb-1">Selamat Datang Pengguna Baru! 👋</h2>
            <p class="text-muted small">Silakan Registrasi Untuk Membuat Akun SuperInventory</p>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required autofocus placeholder="Masukkan nama...">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required placeholder="Masukkan email...">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                        required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()"
                        aria-label="Toggle password">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="••••••••" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordConfirm()"
                        aria-label="Toggle password">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
            </div>
            <div>
                <p class="fw-light fst-italic">
                    Setelah berhasil registrasi, maka Anda harus menunggu Admin untuk menerima permintaan buat akun baru.
                </p>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ url('/login') }}" class="text-decoration-none">Sudah punya akun? <span
                    class="fw-semibold">Login</span></a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            if (input.type === 'password') input.type = 'text';
            else input.type = 'password';
        }

        function togglePasswordConfirm() {
            const input = document.getElementById('password_confirmation');
            if (input.type === 'password') input.type = 'text';
            else input.type = 'password';
        }
    </script>

@endsection
