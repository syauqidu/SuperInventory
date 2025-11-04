@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div>
        <div class="mb-4 text-center">
            <h2 class="h5 mb-1">Selamat Datang Kembali! 👋</h2>
            <p class="text-muted small">Silakan login untuk mengakses sistem manajemen stok</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" required
                    autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()"
                        aria-label="Toggle password">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Masuk ke Dashboard</button>
            </div>
        </form>

        <div class="mt-3 text-center small">
            <p class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Buat akun</a></p>
        </div>

        <div class="mt-3 pt-3 border-top small">
            <p class="text-muted text-center mb-2">🔑 Akun Demo untuk Testing:</p>
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="fw-semibold">Admin</div>
                        <div class="text-muted">admin@superinventory.com</div>
                        <div class="text-muted">password123</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="fw-semibold">Staff</div>
                        <div class="text-muted">staff@superinventory.com</div>
                        <div class="text-muted">password123</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            if (input.type === 'password') input.type = 'text';
            else input.type = 'password';
        }
    </script>

@endsection
