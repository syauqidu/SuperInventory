@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<!-- 
    Halaman Register - SuperInventory
    Frontend Developer: Syauqi (Uqi)
    Deskripsi: Form pendaftaran akun baru dengan validasi
    
    TODO: Uqi, lengkapi halaman ini dengan:
    - Form input: Name, Email, Password, Confirm Password
    - Validasi: Email unique, password min 6 karakter, password confirmation match
    - Submit button yang redirect ke login setelah berhasil
    - Link ke halaman login untuk user yang sudah punya akun
-->

<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Halaman Register</h2>
    <p class="text-gray-600 mb-6">Ini adalah placeholder untuk Syauqi (Uqi)</p>
    
    <div class="bg-indigo-50 rounded-lg p-6 text-left max-w-md mx-auto">
        <p class="text-sm font-semibold text-indigo-900 mb-3">📝 Yang perlu dibuat:</p>
        <ul class="text-sm text-indigo-800 space-y-2 list-disc list-inside">
            <li>Form input: Name, Email, Password, Confirm Password</li>
            <li>Validasi semua field required</li>
            <li>Email harus unique dan format valid</li>
            <li>Password minimal 6 karakter</li>
            <li>Password confirmation harus match</li>
            <li>Submit button dengan styling Tailwind</li>
            <li>Error handling & flash messages</li>
            <li>Link "Sudah punya akun? Login"</li>
        </ul>
    </div>

    <div class="mt-6">
        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
            ← Kembali ke Login
        </a>
    </div>
</div>
@endsection
