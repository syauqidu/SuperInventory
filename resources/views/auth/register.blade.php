@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px; border-radius: 1.2rem;">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus" style="font-size: 3rem; color: #0d6efd;"></i>
                <h2 class="fw-bold mt-2">Register</h2>
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
                        required autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ url('/login') }}" class="text-decoration-none">Sudah punya akun? <span
                        class="fw-semibold">Login</span></a>
            </div>
        </div>
    </div>
@endsection
