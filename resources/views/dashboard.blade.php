<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - SuperInventory</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex items-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">SuperInventory</span>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Halo, <span class="font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full 
                            {{ Auth::user()->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </span>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Dashboard Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-600">Sistem Manajemen Stok Barang UMKM</p>
        </div>

        <!-- Placeholder untuk Kalel (Backend Developer) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Dashboard Content</h2>
                <p class="text-gray-600 mb-4">
                    mangat kalel</span>
                </p>
                <div class="bg-gray-50 rounded-lg p-4 text-left max-w-2xl mx-auto">
                    <p class="text-sm text-gray-700 mb-2 font-semibold">📋 ini kira kira keknya yang perlu ditampilin di desbort:</p>
                    <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                        <li>Ringkasan jumlah total barang</li>
                        <li>Jumlah supplier terdaftar</li>
                        <li>Notifikasi stok menipis</li>
                        <li>Riwayat aktivitas terakhir (dari stock_histories)</li>
                        <li>Card/statistik dengan chart (opsional)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Links Navigation -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manajemen Stok Barang - Dafa -->
            <a href="{{ route('products.index') }}" class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-300 transition group">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Manajemen Stok Barang</h3>
                <p class="text-sm text-gray-600">CRUD Produk - by Dafa</p>
            </a>

            <!-- Manajemen Supplier - Siganteng -->
            <a href="{{ route('suppliers.index') }}" class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-green-300 transition group">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Manajemen Supplier</h3>
                <p class="text-sm text-gray-600">CRUD Supplier - by Siganteng</p>
            </a>

            <!-- Log/History Stok - Darryl -->
            <a href="{{ route('stock-history.index') }}" class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-purple-300 transition group">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Log/History Stok</h3>
                <p class="text-sm text-gray-600">Riwayat Perubahan - by Darryl</p>
            </a>
        </div>

        <!-- Development Info -->
        <div class="mt-8 bg-indigo-50 border border-indigo-200 rounded-lg p-6">
            <h3 class="text-sm font-semibold text-indigo-900 mb-3">👥 Tim Pengembang SuperInventory</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Syauqi (Uqi)</p>
                    <p class="text-gray-600 text-xs">Frontend - Register</p>
                </div>
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Nijar ✅</p>
                    <p class="text-gray-600 text-xs">Frontend - Login</p>
                </div>
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Kalel</p>
                    <p class="text-gray-600 text-xs">Backend - Dashboard</p>
                </div>
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Dafa</p>
                    <p class="text-gray-600 text-xs">Fullstack - Stok Barang</p>
                </div>
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Siganteng</p>
                    <p class="text-gray-600 text-xs">Fullstack - Supplier</p>
                </div>
                <div class="bg-white rounded p-3">
                    <p class="font-semibold text-gray-900">Darryl</p>
                    <p class="text-gray-600 text-xs">Backend - Log/History</p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>
