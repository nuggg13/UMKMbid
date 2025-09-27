@extends('layouts.app')

@section('title', 'Kelola Bisnis - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Bisnis</h1>
            <p class="text-gray-600 mt-2">Daftarkan dan kelola profil bisnis UMKM Anda</p>
        </div>
        <a href="{{ route('umkm.businesses.create') }}" class="btn-primary transition duration-200">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Daftarkan Bisnis Baru
        </a>
    </div>

    <!-- Business Cards -->
    <div id="businesses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loading placeholder -->
        <div class="col-span-full text-center py-12">
            <svg class="animate-spin h-8 w-8 text-primary mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-500">Memuat bisnis Anda...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadBusinesses();
});

function loadBusinesses() {
    fetch('/api/my-businesses')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('businesses-container');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-md mx-auto">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Bisnis</h3>
                            <p class="text-gray-500 mb-6">Mulai dengan mendaftarkan bisnis UMKM Anda untuk mendapatkan investor.</p>
                            <a href="{{ route('umkm.businesses.create') }}" class="btn-primary transition duration-200">
                                Daftarkan Bisnis Pertama
                            </a>
                        </div>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(business => `
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="card-body">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-800">${business.name}</h3>
                            <span class="px-3 py-1 text-sm rounded-full ${getStatusColor(business.status)}">
                                ${getStatusText(business.status)}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">${business.description}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="text-gray-500">Kategori:</span>
                                <span class="font-medium">${getCategoryText(business.category)}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Karyawan:</span>
                                <span class="font-medium">${business.employee_count} orang</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500">Pendapatan Tahunan:</span>
                                <span class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(business.annual_revenue)}</span>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="/umkm/businesses/${business.id}" class="btn-secondary transition duration-200">
                                Detail
                            </a>
                            ${business.status === 'approved' ? `
                                <a href="/umkm/businesses/${business.id}/auctions/create" class="btn-primary transition duration-200">
                                    Buat Lelang
                                </a>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading businesses:', error);
            document.getElementById('businesses-container').innerHTML = `
                <div class="col-span-full text-center py-12">
                    <p class="text-red-500">Gagal memuat data bisnis. Silakan coba lagi.</p>
                </div>
            `;
        });
}

function getStatusColor(status) {
    switch(status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'approved': return 'Disetujui';
        case 'pending': return 'Menunggu';
        case 'rejected': return 'Ditolak';
        default: return status;
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