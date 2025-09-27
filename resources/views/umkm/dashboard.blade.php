@extends('layouts.app')

@section('title', 'UMKM Dashboard - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="card mb-8 transition duration-200">
        <div class="card-body">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Kelola bisnis UMKM Anda dan buat lelang untuk mendapatkan modal investasi.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="total-businesses">0</p>
                    <p class="text-gray-500">Total Bisnis</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="active-auctions">0</p>
                    <p class="text-gray-500">Lelang Aktif</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="total-bids">0</p>
                    <p class="text-gray-500">Total Tawaran</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700">Rp <span id="total-raised">0</span></p>
                    <p class="text-gray-500">Modal Terkumpul</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-8 transition duration-200">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-800">Aksi Cepat</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('umkm.businesses.create') }}" class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Daftarkan Bisnis Baru</h3>
                </a>
                <a href="{{ route('umkm.businesses.index') }}" class="action-card transition duration-200">
                    <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Kelola Bisnis</h3>
                </a>
                <a href="{{ route('auctions.index') }}" class="action-card transition duration-200">
                    <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Lihat Semua Lelang</h3>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card transition duration-200">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h2>
        </div>
        <div class="card-body">
            <div id="recent-activity">
                <p class="text-gray-500 text-center py-8">Belum ada aktivitas terbaru</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard statistics
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-businesses').textContent = data.total_businesses || 0;
            document.getElementById('active-auctions').textContent = data.active_auctions || 0;
            document.getElementById('total-bids').textContent = data.total_bids || 0;
            
            // Format currency for total raised
            const totalRaised = data.total_raised || 0;
            document.getElementById('total-raised').textContent = new Intl.NumberFormat('id-ID').format(totalRaised);
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
        });
});
</script>
@endsection