@extends('layouts.app')

@section('title', 'UMKMBid - Platform Lelang Equity UMKM')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                Platform <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-600">Lelang</span><br>
                Equity <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-700">UMKM</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Platform pertama di Indonesia yang menghubungkan UMKM dengan investor melalui sistem lelang equity yang transparan. UMKM menawarkan saham, investor berlomba memberikan tawaran terbaik.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    @if(auth()->user()->role === 'umkm_owner')
                        <a href="{{ route('umkm.dashboard') }}" class="btn-primary transition duration-200">
                            Dashboard UMKM
                        </a>
                    @elseif(auth()->user()->role === 'investor')
                        <a href="{{ route('investor.dashboard') }}" class="btn-primary transition duration-200">
                            Dashboard Investor
                        </a>
                    @endif
                    <a href="{{ route('auctions.index') }}" class="btn-secondary transition duration-200">
                        Lihat Semua Lelang
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary transition duration-200">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('auctions.index') }}" class="btn-secondary transition duration-200">
                        Jelajahi Lelang
                    </a>
                @endauth
            </div>
        </div>

        <!-- How It Works -->
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-12">Cara Kerja Platform</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-3">1. Daftarkan Bisnis</h3>
                    <p class="text-gray-600 text-sm">UMKM mendaftarkan profil bisnis dan data keuangan untuk verifikasi admin.</p>
                </div>
                <div class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-3">2. Buat Kontrak</h3>
                    <p class="text-gray-600 text-sm">Tentukan modal yang dibutuhkan, persentase equity, dan syarat investasi.</p>
                </div>
                <div class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-3">3. Lelang Berlangsung</h3>
                    <p class="text-gray-600 text-sm">Investor berlomba memberikan tawaran modal terbaik dalam periode lelang.</p>
                </div>
                <div class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-3">4. Deal Terbentuk</h3>
                    <p class="text-gray-600 text-sm">Pemenang lelang menjadi investor dan proses legal agreement dimulai.</p>
                </div>
            </div>
        </div>

        <!-- Featured Auctions -->
        <div class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Lelang Unggulan</h2>
                <a href="{{ route('auctions.index') }}" class="text-primary hover:text-secondary font-semibold transition duration-200">
                    Lihat Semua →
                </a>
            </div>
            
            <div id="featured-auctions" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Loading placeholder -->
                <div class="col-span-full text-center py-12">
                    <svg class="animate-spin h-8 w-8 text-primary mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500">Memuat lelang unggulan...</p>
                </div>
            </div>
        </div>

        <!-- Platform Stats -->
        <div class="card mb-16 transition duration-200">
            <div class="card-body">
                <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-8">Statistik Platform</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                    <div class="text-center p-4 bg-blue-50 rounded-lg transition duration-200">
                        <div class="text-2xl md:text-3xl font-bold text-blue-600 mb-2" id="total-businesses">0</div>
                        <div class="text-gray-600">UMKM Terdaftar</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg transition duration-200">
                        <div class="text-2xl md:text-3xl font-bold text-green-600 mb-2" id="active-auctions">0</div>
                        <div class="text-gray-600">Lelang Aktif</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg transition duration-200">
                        <div class="text-2xl md:text-3xl font-bold text-purple-600 mb-2">Rp <span id="total-funding">0</span></div>
                        <div class="text-gray-600">Total Modal</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg transition duration-200">
                        <div class="text-2xl md:text-3xl font-bold text-orange-600 mb-2" id="total-investors">0</div>
                        <div class="text-gray-600">Investor Aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Memilih UMKMBid?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="action-card transition duration-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Transparan & Aman</h3>
                    <p class="text-gray-600">Sistem lelang terbuka dengan verifikasi ketat untuk semua pihak. Data keuangan dan legal diaudit profesional.</p>
                </div>
                <div class="action-card transition duration-200">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Proses Cepat</h3>
                    <p class="text-gray-600">Dari registrasi hingga closing deal hanya membutuhkan waktu maksimal 30 hari. Tidak ada birokrasi berbelit.</p>
                </div>
                <div class="action-card transition duration-200">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 00-2 2v6a2 2 0 002 2zm10 0v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Komunitas Kuat</h3>
                    <p class="text-gray-600">Bergabung dengan komunitas UMKM dan investor terpercaya. Networking dan mentoring berkelanjutan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load featured auctions
    loadFeaturedAuctions();
    // Load platform stats
    loadPlatformStats();
});

function loadFeaturedAuctions() {
    fetch('/api/auctions?limit=6')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('featured-auctions');
            
            if (data.data && data.data.length > 0) {
                container.innerHTML = data.data.map(auction => createAuctionCard(auction)).join('');
            } else {
                container.innerHTML = `
                    <div class="col-span-full text-center py-16 card">
                        <div class="card-body">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Lelang</h3>
                            <p class="text-gray-500 mb-6">Jadilah yang pertama untuk memulai lelang investasi</p>
                            @auth
                                @if(auth()->user()->role === 'umkm_owner')
                                    <a href="{{ route('umkm.businesses.create') }}" class="btn-primary">
                                        Daftarkan Bisnis
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="btn-primary">
                                    Bergabung Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading featured auctions:', error);
            document.getElementById('featured-auctions').innerHTML = `
                <div class="col-span-full text-center py-12 card">
                    <div class="card-body">
                        <p class="text-red-500">Gagal memuat lelang. Silakan refresh halaman.</p>
                    </div>
                </div>
            `;
        });
}

function loadPlatformStats() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-businesses').textContent = data.total_businesses || 0;
            document.getElementById('active-auctions').textContent = data.active_auctions || 0;
            document.getElementById('total-funding').textContent = new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(data.total_funding || 0);
            document.getElementById('total-investors').textContent = data.total_investors || 0;
        })
        .catch(error => {
            console.error('Error loading platform stats:', error);
        });
}

function createAuctionCard(auction) {
    const timeRemaining = getTimeRemaining(auction.end_date);
    const progress = (auction.current_highest_bid || 0) / auction.funding_goal * 100;
    
    return `
        <div class="card hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-1">${auction.title}</h3>
                        <p class="text-gray-600 text-sm">${auction.business.name} • ${getCategoryText(auction.business.category)}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(auction.status)}">
                        ${getStatusText(auction.status)}
                    </span>
                </div>
                
                <p class="text-gray-600 mb-4 text-sm line-clamp-3">${auction.description.substring(0, 100)}...</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-center">
                    <div>
                        <div class="text-lg font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(auction.funding_goal)}</div>
                        <div class="text-xs text-gray-500">Target Modal</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-green-600">${auction.equity_percentage}%</div>
                        <div class="text-xs text-gray-500">Equity</div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">Progress</span>
                        <span class="text-xs font-medium">${Math.round(progress)}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: ${Math.min(progress, 100)}%"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        <div class="text-gray-500">Berakhir dalam:</div>
                        <div class="font-medium ${timeRemaining.urgent ? 'text-red-600' : 'text-gray-800'}">${timeRemaining.text}</div>
                    </div>
                    <a href="/auctions/${auction.id}" class="btn-primary text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    `;
}

function getTimeRemaining(endDate) {
    const now = new Date().getTime();
    const end = new Date(endDate).getTime();
    const distance = end - now;
    
    if (distance < 0) {
        return { text: 'Berakhir', urgent: false };
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    
    if (days > 0) {
        return { text: `${days} hari`, urgent: days <= 2 };
    } else if (hours > 0) {
        return { text: `${hours} jam`, urgent: true };
    } else {
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        return { text: `${minutes} menit`, urgent: true };
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'active': return 'bg-green-100 text-green-800';
        case 'ended': return 'bg-gray-100 text-gray-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-yellow-100 text-yellow-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'active': return 'Aktif';
        case 'ended': return 'Berakhir';
        case 'cancelled': return 'Dibatalkan';
        default: return 'Menunggu';
    }
}

function getCategoryText(category) {
    const categories = {
        'tech': 'Teknologi',
        'fb': 'F&B',
        'retail': 'Retail',
        'service': 'Jasa',
        'manufacturing': 'Manufaktur',
        'ecommerce': 'E-commerce',
        'education': 'Pendidikan',
        'health': 'Kesehatan',
        'agriculture': 'Pertanian',
        'creative': 'Industri Kreatif'
    };
    return categories[category] || category;
}
</script>

@endsection