@extends('layouts.app')

@section('title', 'Pengaturan 2FA')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🔐 Pengaturan Autentikasi 2 Faktor</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-bold">Status 2FA</h6>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                @if(Auth::user()->two_factor_enabled)
                                    <span class="badge bg-success fs-6">✅ Aktif</span>
                                    <p class="text-muted small mb-0 mt-2">
                                        Akun Anda dilindungi dengan autentikasi 2 faktor.
                                    </p>
                                @else
                                    <span class="badge bg-secondary fs-6">❌ Tidak Aktif</span>
                                    <p class="text-muted small mb-0 mt-2">
                                        Aktifkan 2FA untuk keamanan tambahan saat login.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Apa itu 2FA?</h6>
                        <p class="small text-muted">
                            Autentikasi 2 Faktor (2FA) menambahkan lapisan keamanan ekstra pada akun Anda. 
                            Setelah memasukkan password, Anda akan diminta memasukkan kode 6 digit yang 
                            dikirimkan setiap kali login.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Cara Kerja:</h6>
                        <ol class="small text-muted">
                            <li>Login dengan email dan password seperti biasa</li>
                            <li>Sistem akan mengirimkan kode 6 digit (dalam implementasi ini, kode akan ditampilkan di layar untuk demo)</li>
                            <li>Masukkan kode tersebut untuk menyelesaikan proses login</li>
                            <li>Kode berlaku selama 5 menit</li>
                        </ol>
                    </div>

                    <div class="d-grid gap-2">
                        @if(Auth::user()->two_factor_enabled)
                            <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan 2FA?');">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    🔓 Nonaktifkan 2FA
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('two-factor.enable') }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    🔒 Aktifkan 2FA
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            ← Kembali ke Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
