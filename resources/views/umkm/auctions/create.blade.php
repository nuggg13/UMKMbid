@extends('layouts.app')

@section('title', 'Buat Lelang Investasi - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Lelang Investasi</h1>
            <p class="text-gray-600">Tawarkan equity bisnis Anda kepada investor melalui sistem lelang</p>
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>{{ $business->name }}</strong> - {{ $business->category }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="card">
            <div class="card-body">
                <form id="auctionForm" class="space-y-6">
                    @csrf
                    
                    <!-- Auction Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Lelang *</label>
                        <input type="text" id="title" name="title" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Contoh: Ekspansi Warung Kopi Nusantara">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Investasi *</label>
                        <textarea id="description" name="description" rows="6" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                  placeholder="Jelaskan rencana penggunaan dana, proyeksi pertumbuhan, target ekspansi, dan manfaat bagi investor..."></textarea>
                    </div>

                    <!-- Funding Goal -->
                    <div>
                        <label for="funding_goal" class="block text-sm font-medium text-gray-700 mb-2">Target Modal (Rp) *</label>
                        <input type="text" id="funding_goal" name="funding_goal" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Rp 0">
                        <p class="text-sm text-gray-500 mt-1">Minimum Rp 1,000,000 - Maximum Rp 10,000,000,000</p>
                    </div>

                    <!-- Equity Percentage -->
                    <div>
                        <label for="equity_percentage" class="block text-sm font-medium text-gray-700 mb-2">Persentase Equity yang Ditawarkan (%) *</label>
                        <input type="number" id="equity_percentage" name="equity_percentage" required min="1" max="49" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="25.00">
                        <p class="text-sm text-gray-500 mt-1">Maksimal 49% untuk mempertahankan kontrol mayoritas</p>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Durasi Lelang (Hari) *</label>
                        <input type="number" id="duration_days" name="duration_days" required min="1" max="30"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                               placeholder="Masukkan durasi lelang (1-30 hari)">
                        <p class="text-sm text-gray-500 mt-1">Durasi lelang dalam hari (1-30 hari)</p>
                    </div>

                    <!-- Terms and Conditions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Syarat dan Ketentuan Khusus</label>
                        <div class="space-y-3">
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="board_seat" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Investor mendapat kursi di dewan direksi</span>
                            </label>
                            
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="veto_rights" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Hak veto untuk keputusan strategis</span>
                            </label>
                            
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="anti_dilution" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Perlindungan anti-dilusi</span>
                            </label>
                            
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="tag_along" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Hak tag-along untuk penjualan saham</span>
                            </label>
                            
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="drag_along" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Hak drag-along untuk exit strategy</span>
                            </label>
                            
                            <label class="flex items-start">
                                <input type="checkbox" name="terms_conditions[]" value="financial_reporting" 
                                       class="mt-1 mr-3 h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary transition duration-200">
                                <span class="text-sm text-gray-700">Laporan keuangan bulanan wajib</span>
                            </label>
                        </div>
                        
                        <!-- Custom Terms Section -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-sm font-medium text-gray-700">Syarat dan Ketentuan Kustom</label>
                                <button type="button" id="add-custom-term" 
                                        class="bg-primary text-white px-3 py-1 rounded text-sm hover:opacity-90 transition duration-200">
                                    + Tambah Syarat
                                </button>
                            </div>
                            <div id="custom-terms-container" class="space-y-3">
                                <!-- Custom terms will be added here dynamically -->
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Anda dapat menambahkan syarat dan ketentuan khusus sesuai kebutuhan bisnis Anda</p>
                        </div>
                    </div>

                    <!-- Valuation Info -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Estimasi Valuasi</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Target Modal:</span>
                                <span id="display-funding" class="font-medium ml-2">Rp 0</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Equity Ditawarkan:</span>
                                <span id="display-equity" class="font-medium ml-2">0%</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-600">Estimasi Valuasi Bisnis:</span>
                                <span id="display-valuation" class="font-medium ml-2 text-lg text-primary">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>
                    
                    <!-- Success Message -->
                    <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"></div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full btn-primary py-3 px-6 rounded-lg flex items-center justify-center transition duration-200">
                            <span id="submit-text">Buat Lelang</span>
                            <span id="loading-spinner" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        <p>* Wajib diisi</p>
                        <p class="mt-2">Lelang akan dimulai setelah disetujui oleh admin</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Format currency input
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
document.getElementById('funding_goal').addEventListener('input', function(e) {
    formatCurrency(e.target);
});

// Handle blur event to ensure formatting
document.getElementById('funding_goal').addEventListener('blur', function(e) {
    if (e.target.value && !e.target.value.startsWith('Rp ')) {
        formatCurrency(e.target);
    }
    // Update valuation when user leaves the field
    updateValuation();
});

// Prevent non-numeric input except for allowed keys
document.getElementById('funding_goal').addEventListener('keypress', function(e) {
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
document.getElementById('funding_goal').addEventListener('paste', function(e) {
    setTimeout(() => {
        formatCurrency(e.target);
        updateValuation();
    }, 10);
});

// Calculate valuation
function updateValuation() {
    // Clean the funding goal value before calculation
    const fundingInput = document.getElementById('funding_goal');
    const cleanValue = fundingInput.value.replace(/[^0-9]/g, '');
    const funding = parseFloat(cleanValue) || 0;
    const equity = parseFloat(document.getElementById('equity_percentage').value) || 0;
    
    // Format numbers using JavaScript native formatting instead of Laravel's Number helper
    document.getElementById('display-funding').textContent = 'Rp ' + funding.toLocaleString('id-ID');
    document.getElementById('display-equity').textContent = equity + '%';
    
    if (funding > 0 && equity > 0) {
        const valuation = funding / (equity / 100);
        document.getElementById('display-valuation').textContent = 'Rp ' + valuation.toLocaleString('id-ID');
    } else {
        document.getElementById('display-valuation').textContent = 'Rp 0';
    }
}

// Custom terms functionality
let customTermCounter = 0;

function addCustomTerm(value = '') {
    customTermCounter++;
    const container = document.getElementById('custom-terms-container');
    const termDiv = document.createElement('div');
    termDiv.className = 'flex items-center space-x-2';
    termDiv.innerHTML = `
        <input type="text" 
               name="custom_terms[]" 
               value="${value}"
               placeholder="Contoh: Investor memiliki hak pertama untuk putaran pendanaan selanjutnya"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition duration-200"
               maxlength="255">
        <button type="button" 
                onclick="removeCustomTerm(this)"
                class="bg-red-500 text-white px-2 py-2 rounded hover:bg-red-600 transition-colors duration-200 text-sm">
            ×
        </button>
    `;
    container.appendChild(termDiv);
}

function removeCustomTerm(button) {
    button.parentElement.remove();
}

// Add event listeners
document.getElementById('funding_goal').addEventListener('input', updateValuation);
document.getElementById('equity_percentage').addEventListener('input', updateValuation);
document.getElementById('add-custom-term').addEventListener('click', () => addCustomTerm());

// Submission flag to prevent double submission
let isSubmitting = false;

// Form submission
document.getElementById('auctionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clean the funding_goal value before submission
    const fundingInput = document.getElementById('funding_goal');
    const cleanValue = fundingInput.value.replace(/[^0-9]/g, '');
    fundingInput.value = cleanValue;
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');
    
    // Prevent double submission
    if (submitButton.disabled || isSubmitting) {
        return;
    }
    
    // Set submission flag
    isSubmitting = true;
    
    // Show loading state
    submitButton.disabled = true;
    submitText.textContent = 'Memproses...';
    loadingSpinner.classList.remove('hidden');
    errorMessage.classList.add('hidden');
    successMessage.classList.add('hidden');
    
    // Manually build form data to ensure proper array handling
    const formData = new FormData();

    // Add basic fields
    formData.append('title', document.getElementById('title').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('funding_goal', cleanValue); // Use the cleaned value
    formData.append('equity_percentage', document.getElementById('equity_percentage').value);
    formData.append('duration_days', document.getElementById('duration_days').value);

    // Handle terms_conditions array
    const termsCheckboxes = document.querySelectorAll('input[name="terms_conditions[]"]');
    const selectedTerms = [];
    termsCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedTerms.push(checkbox.value);
        }
    });

    // Handle custom terms
    const customTermInputs = document.querySelectorAll('input[name="custom_terms[]"]');
    const customTerms = [];
    customTermInputs.forEach(input => {
        if (input.value.trim()) {
            customTerms.push(input.value.trim());
        }
    });

    // Combine predefined and custom terms
    const allTerms = [...selectedTerms, ...customTerms];

    // Add each term as a separate field to ensure Laravel receives it as an array
    allTerms.forEach((term, index) => {
        formData.append(`terms_conditions[${index}]`, term);
    });

    // Add each custom term
    customTerms.forEach((term, index) => {
        formData.append(`custom_terms[${index}]`, term);
    });

    fetch('{{ route("umkm.auctions.store", $business->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => ({ status: response.status, data }));
        } else {
            // If not JSON, get text content for debugging
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                return {
                    status: response.status,
                    data: {
                        success: false,
                        message: 'Server returned invalid response format. Check browser console for details.'
                    }
                };
            });
        }
    })
    .then(({ status, data }) => {
        if (status === 200 && data.success) {
            // Show success message
            successMessage.textContent = data.message || 'Lelang berhasil dibuat!';
            successMessage.classList.remove('hidden');
            
            // Keep button disabled and show success state
            submitText.textContent = 'Berhasil!';
            loadingSpinner.classList.add('hidden');
            
            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            // Show error message
            console.error('Error response:', { status, data });
            let errorText = data.message || 'Gagal membuat lelang. Silakan coba lagi.';
            if (data.errors) {
                errorText = Object.values(data.errors).flat().join(', ');
            }
            
            // Add status code to error message for debugging
            if (status !== 422) {
                errorText += ` (Status: ${status})`;
            }
            
            errorMessage.textContent = errorText;
            errorMessage.classList.remove('hidden');
            
            // Re-enable button only on error
            isSubmitting = false;
            submitButton.disabled = false;
            submitText.textContent = 'Buat Lelang';
            loadingSpinner.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Network/Fetch error:', error);
        errorMessage.textContent = 'Terjadi kesalahan jaringan. Silakan periksa koneksi internet dan coba lagi.';
        errorMessage.classList.remove('hidden');
        
        // Re-enable button only on error
        isSubmitting = false;
        submitButton.disabled = false;
        submitText.textContent = 'Buat Lelang';
        loadingSpinner.classList.add('hidden');
    });
});
</script>
@endsection