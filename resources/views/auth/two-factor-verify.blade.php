@extends('layouts.auth')

@section('title', 'Verifikasi 2FA')

@section('content')

<div>
    <div class="mb-4 text-center">
        <h2 class="h5 mb-1">Verifikasi 2 Faktor 🔐</h2>
        <p class="text-muted small">Masukkan kode 6 digit yang telah dikirim</p>
    </div>

    <form method="POST" action="{{ route('two-factor.verify') }}" id="verifyForm">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Kode Verifikasi</label>
            <input 
                type="text" 
                name="code" 
                id="code" 
                class="form-control @error('code') is-invalid @enderror text-center fs-4 letter-spacing-3" 
                placeholder="000000" 
                maxlength="6"
                pattern="\d{6}"
                required 
                autofocus
                style="letter-spacing: 0.5rem;"
            >
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @error('rate_limit')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="alert alert-info small mb-3">
            <strong>ℹ️ Info:</strong> Kode verifikasi berlaku selama 5 menit. Jika tidak menerima kode, silakan klik "Kirim Ulang Kode".
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Verifikasi & Login</button>
            <button type="button" class="btn btn-outline-secondary" onclick="kirimUlang()">Kirim Ulang Kode</button>
        </div>
    </form>

    <div class="mt-3 text-center small">
        <a href="{{ route('login') }}" class="text-decoration-none">← Kembali ke Login</a>
    </div>
</div>

<script>
// auto submit kalo udah 6 digit
document.getElementById('code').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, ''); // cuma angka aja
    if (this.value.length === 6) {
        document.getElementById('verifyForm').submit();
    }
});

function kirimUlang() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("two-factor.resend") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection
