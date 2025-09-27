@extends('layouts.app')

@section('title', 'Edit Bisnis - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Bisnis UMKM</h1>
            <p class="text-gray-600">Perbarui informasi profil bisnis Anda</p>
        </div>

        <!-- Form -->
        <div class="card transition duration-200">
            <div class="card-body">
                <form id="businessForm" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Business Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Bisnis *</label>
                        <input type="text" id="name" name="name" required 
                               value="{{ $business->name }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Contoh: Warung Kopi Nusantara">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori Bisnis *</label>
                        <select id="category" name="category" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                            <option value="">Pilih kategori bisnis</option>
                            <option value="tech" {{ $business->category == 'tech' ? 'selected' : '' }}>Teknologi</option>
                            <option value="fb" {{ $business->category == 'fb' ? 'selected' : '' }}>Food & Beverage</option>
                            <option value="retail" {{ $business->category == 'retail' ? 'selected' : '' }}>Retail</option>
                            <option value="service" {{ $business->category == 'service' ? 'selected' : '' }}>Jasa</option>
                            <option value="manufacturing" {{ $business->category == 'manufacturing' ? 'selected' : '' }}>Manufaktur</option>
                            <option value="ecommerce" {{ $business->category == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="education" {{ $business->category == 'education' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="health" {{ $business->category == 'health' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="agriculture" {{ $business->category == 'agriculture' ? 'selected' : '' }}>Pertanian</option>
                            <option value="creative" {{ $business->category == 'creative' ? 'selected' : '' }}>Industri Kreatif</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Bisnis *</label>
                        <textarea id="description" name="description" rows="4" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                  placeholder="Jelaskan bisnis Anda, target pasar, keunggulan kompetitif, dan rencana pengembangan...">{{ $business->description }}</textarea>
                    </div>

                    <!-- Annual Revenue -->
                    <div>
                        <label for="annual_revenue" class="block text-sm font-medium text-gray-700 mb-2">Pendapatan Tahunan (Rp)</label>
                        <input type="text" id="annual_revenue" name="annual_revenue"
                               value="{{ $business->annual_revenue ? 'Rp ' . number_format($business->annual_revenue, 0, ',', '.') : '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Rp 0">
                        <p class="text-sm text-gray-500 mt-1">Masukkan pendapatan kotor tahunan dalam Rupiah</p>
                    </div>

                    <!-- Employee Count -->
                    <div>
                        <label for="employee_count" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Karyawan</label>
                        <input type="number" id="employee_count" name="employee_count" min="1"
                               value="{{ $business->employee_count }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="15">
                    </div>

                    <!-- Established Date -->
                    <div>
                        <label for="established_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berdiri</label>
                        <input type="date" id="established_date" name="established_date" 
                               value="{{ $business->established_date ? $business->established_date->format('Y-m-d') : '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    </div>

                    <!-- Business License -->
                    <div>
                        <label for="business_license" class="block text-sm font-medium text-gray-700 mb-2">Nomor Izin Usaha</label>
                        <input type="text" id="business_license" name="business_license" 
                               value="{{ $business->business_license }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Nomor SIUP/NIB/Izin Usaha lainnya">
                    </div>

                    <!-- Business Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Bisnis</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                  placeholder="Alamat lengkap bisnis Anda...">{{ $business->address }}</textarea>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website (Opsional)</label>
                        <input type="url" id="website" name="website" 
                               value="{{ $business->website }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="https://example.com">
                    </div>

                    <!-- Social Media -->
                    <div>
                        <label for="social_media" class="block text-sm font-medium text-gray-700 mb-2">Social Media (Opsional)</label>
                        <input type="text" id="social_media" name="social_media" 
                               value="{{ $business->social_media }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="@username_instagram, @facebook_page">
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>

                    <!-- Success Message -->
                    <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"></div>

                    <!-- Action Buttons -->
                    <div class="pt-6 flex flex-wrap gap-4">
                        <button type="submit" 
                                class="flex-1 btn-primary py-3 px-6 rounded-lg flex items-center justify-center transition duration-200">
                            <span id="submit-text">Perbarui Bisnis</span>
                            <span id="loading-spinner" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                        
                        <a href="{{ route('umkm.businesses.show', $business) }}" 
                           class="flex-1 btn-secondary py-3 px-6 rounded-lg text-center transition duration-200">
                            Batal
                        </a>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        <p>* Wajib diisi</p>
                        <p class="mt-2">Perubahan akan diverifikasi oleh admin jika diperlukan</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function formatCurrency(input) {
    // Remove non-numeric characters
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Format as Rupiah currency
    if (value.length > 0) {
        // Add thousands separator
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        // Add Rp prefix
        value = 'Rp ' + value;
    } else {
        value = '';
    }
    
    input.value = value;
}

// Handle input event for currency formatting
document.getElementById('annual_revenue').addEventListener('input', function(e) {
    formatCurrency(e.target);
});

// Handle blur event to ensure formatting
document.getElementById('annual_revenue').addEventListener('blur', function(e) {
    if (e.target.value && !e.target.value.startsWith('Rp ')) {
        formatCurrency(e.target);
    }
});

// Prevent non-numeric input except for allowed keys
document.getElementById('annual_revenue').addEventListener('keypress', function(e) {
    // Allow: backspace, delete, tab, escape, enter
    if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.keyCode === 65 && e.ctrlKey === true) ||
        (e.keyCode === 67 && e.ctrlKey === true) ||
        (e.keyCode === 86 && e.ctrlKey === true) ||
        (e.keyCode === 88 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;  // let it happen, don't do anything
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

// Handle paste event
document.getElementById('annual_revenue').addEventListener('paste', function(e) {
    setTimeout(() => {
        formatCurrency(e.target);
    }, 10);
});

document.getElementById('businessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clean the annual_revenue value before submission
    const revenueInput = document.getElementById('annual_revenue');
    const cleanValue = revenueInput.value.replace(/[^0-9]/g, '');
    revenueInput.value = cleanValue;
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');
    
    // Show loading state
    submitButton.disabled = true;
    submitText.textContent = 'Memproses...';
    loadingSpinner.classList.remove('hidden');
    errorMessage.classList.add('hidden');
    successMessage.classList.add('hidden');
    
    const formData = new FormData(e.target);
    
    fetch('/umkm/businesses/{{ $business->id }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            successMessage.textContent = data.message || 'Bisnis berhasil diperbarui!';
            successMessage.classList.remove('hidden');
            
            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = '/umkm/businesses/{{ $business->id }}';
            }, 2000);
        } else {
            // Show error message
            let errorText = data.message || 'Gagal memperbarui bisnis. Silakan coba lagi.';
            if (data.errors) {
                errorText = Object.values(data.errors).flat().join(', ');
            }
            errorMessage.textContent = errorText;
            errorMessage.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        errorMessage.classList.remove('hidden');
    })
    .finally(() => {
        // Reset loading state
        submitButton.disabled = false;
        submitText.textContent = 'Perbarui Bisnis';
        loadingSpinner.classList.add('hidden');
        
        // Restore formatted value
        formatCurrency(revenueInput);
    });
});

</script>
@endsection
