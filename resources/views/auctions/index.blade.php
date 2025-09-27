@extends('layouts.app')

@section('title', 'Semua Lelang - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Temukan Peluang Investasi</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Investasi pada UMKM potensial melalui sistem lelang equity yang transparan dan aman
        </p>
    </div>

    <!-- Filters -->
    <div class="card mb-8 transition duration-200">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="category-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                        <option value="">Semua Kategori</option>
                        <option value="tech">Teknologi</option>
                        <option value="fb">F&B</option>
                        <option value="retail">Retail</option>
                        <option value="service">Jasa</option>
                        <option value="manufacturing">Manufaktur</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="education">Pendidikan</option>
                        <option value="health">Kesehatan</option>
                        <option value="agriculture">Pertanian</option>
                        <option value="creative">Industri Kreatif</option>
                    </select>
                </div>
                
                <div>
                    <label for="funding-filter" class="block text-sm font-medium text-gray-700 mb-2">Target Modal</label>
                    <select id="funding-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                        <option value="">Semua Range</option>
                        <option value="under_100m">< Rp 100 Juta</option>
                        <option value="100m_500m">Rp 100 Juta - 500 Juta</option>
                        <option value="500m_1b">Rp 500 Juta - 1 Miliar</option>
                        <option value="over_1b">> Rp 1 Miliar</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort-filter" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select id="sort-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                        <option value="ending_soon">Berakhir Segera</option>
                        <option value="newest">Terbaru</option>
                        <option value="highest_funding">Modal Tertinggi</option>
                        <option value="most_bids">Paling Banyak Tawaran</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button id="apply-filters" class="btn-primary w-full transition duration-200">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stat-card transition duration-200">
            <div class="text-3xl font-bold text-blue-600" id="total-auctions">0</div>
            <div class="text-gray-600">Total Lelang</div>
        </div>
        <div class="stat-card transition duration-200">
            <div class="text-3xl font-bold text-green-600" id="active-auctions">0</div>
            <div class="text-gray-600">Lelang Aktif</div>
        </div>
        <div class="stat-card transition duration-200">
            <div class="text-3xl font-bold text-purple-600">Rp <span id="total-funding">0</span></div>
            <div class="text-gray-600">Total Modal</div>
        </div>
        <div class="stat-card transition duration-200">
            <div class="text-3xl font-bold text-orange-600" id="total-bids">0</div>
            <div class="text-gray-600">Total Tawaran</div>
        </div>
    </div>

    <!-- Auction Grid -->
    <div id="auctions-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loading placeholder -->
        <div class="col-span-full text-center py-12">
            <svg class="animate-spin h-8 w-8 text-primary mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-500">Memuat lelang...</p>
        </div>
    </div>

    <!-- Load More Button -->
    <div id="load-more-container" class="text-center mt-8 hidden">
        <button id="load-more-btn" class="btn-secondary transition duration-200">
            Muat Lebih Banyak
        </button>
    </div>
</div>

<script>
let currentPage = 1;
let isLoading = false;

document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadAuctions();
    
    // Filter event listeners
    document.getElementById('apply-filters').addEventListener('click', function() {
        currentPage = 1;
        loadAuctions(true);
    });
    
    document.getElementById('load-more-btn').addEventListener('click', function() {
        currentPage++;
        loadAuctions(false);
    });
});

function loadStats() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-auctions').textContent = data.total_auctions || 0;
            document.getElementById('active-auctions').textContent = data.active_auctions || 0;
            document.getElementById('total-funding').textContent = new Intl.NumberFormat('id-ID').format(data.total_funding || 0);
            document.getElementById('total-bids').textContent = data.total_bids || 0;
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

function loadAuctions(reset = false) {
    if (isLoading) return;
    isLoading = true;
    
    const container = document.getElementById('auctions-container');
    const loadMoreContainer = document.getElementById('load-more-container');
    
    if (reset) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="animate-spin h-8 w-8 text-primary mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-500">Memuat lelang...</p>
            </div>
        `;
        loadMoreContainer.classList.add('hidden');
    }
    
    // Build query parameters
    const params = new URLSearchParams({
        page: currentPage,
        category: document.getElementById('category-filter').value,
        funding_range: document.getElementById('funding-filter').value,
        sort: document.getElementById('sort-filter').value
    });
    
    fetch(`/api/auctions?${params}`)
        .then(response => response.json())
        .then(data => {
            if (reset) {
                container.innerHTML = '';
            }
            
            if (data.data.length === 0 && reset) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-16">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Lelang</h3>
                        <p class="text-gray-500">Tidak ada lelang yang sesuai dengan filter Anda</p>
                    </div>
                `;
                return;
            }
            
            data.data.forEach(auction => {
                const auctionCard = createAuctionCard(auction);
                container.insertAdjacentHTML('beforeend', auctionCard);
            });
            
            // Show/hide load more button
            if (data.next_page_url) {
                loadMoreContainer.classList.remove('hidden');
            } else {
                loadMoreContainer.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading auctions:', error);
            if (reset) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <p class="text-red-500">Gagal memuat lelang. Silakan coba lagi.</p>
                        <button onclick="loadAuctions(true)" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        })
        .finally(() => {
            isLoading = false;
        });
}

function createAuctionCard(auction) {
    const timeRemaining = getTimeRemaining(auction.end_date);
    const progress = (auction.current_highest_bid || 0) / auction.funding_goal * 100;
    
    return `
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">${auction.title}</h3>
                        <p class="text-gray-600 text-sm">${auction.business.name} • ${getCategoryText(auction.business.category)}</p>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full ${getStatusColor(auction.status)}">
                        ${getStatusText(auction.status)}
                    </span>
                </div>
                
                <!-- Description -->
                <p class="text-gray-600 mb-4 line-clamp-3">${auction.description.substring(0, 120)}...</p>
                
                <!-- Metrics -->
                <div class="grid grid-cols-3 gap-4 mb-4 text-center">
                    <div>
                        <div class="text-lg font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(auction.funding_goal)}</div>
                        <div class="text-xs text-gray-500">Target</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-green-600">${auction.equity_percentage}%</div>
                        <div class="text-xs text-gray-500">Equity</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-purple-600">${auction.bids_count || 0}</div>
                        <div class="text-xs text-gray-500">Tawaran</div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Progress</span>
                        <span class="text-sm font-medium">${Math.round(progress)}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: ${Math.min(progress, 100)}%"></div>
                    </div>
                </div>
                
                <!-- Current Bid -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Bid Tertinggi:</span>
                        <span class="font-bold text-lg">Rp ${new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(auction.current_highest_bid || auction.funding_goal)}</span>
                    </div>
                </div>
                
                <!-- Timer & Action -->
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        <div class="text-gray-500">Berakhir dalam:</div>
                        <div class="font-medium ${timeRemaining.urgent ? 'text-red-600' : 'text-gray-800'}">${timeRemaining.text}</div>
                    </div>
                    <a href="/auctions/${auction.id}" class="gradient-btn text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
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