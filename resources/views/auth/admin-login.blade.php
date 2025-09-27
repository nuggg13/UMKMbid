@extends('layouts.app')

@section('title', 'Admin Login - UMKMBid')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-red-600 rounded-full flex items-center justify-center">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-2xl md:text-3xl font-extrabold text-gray-900">
                Administrator Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Akses khusus untuk administrator sistem
            </p>
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-primary hover:text-secondary transition duration-200">
                    ← Kembali ke login umum
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg border border-gray-100">
            <div class="card-body">
                <form id="adminLoginForm" class="space-y-6">
                    @csrf
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Area Terbatas
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Hanya administrator yang dapat mengakses area ini. Pastikan Anda memiliki kredensial yang valid.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Administrator</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="admin@umkmbid.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Masukkan password administrator">
                    </div>
                    
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-red-500 group-hover:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span id="login-text">Masuk sebagai Administrator</span>
                            <span id="loading-spinner" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            Semua aktivitas login administrator akan dicatat untuk keperluan keamanan
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const loginText = document.getElementById('login-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    
    // Show loading state
    submitButton.disabled = true;
    loginText.classList.add('hidden');
    loadingSpinner.classList.remove('hidden');
    errorMessage.classList.add('hidden');
    
    const formData = new FormData(e.target);
    
    fetch('/admin/login', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and redirect
            loginText.textContent = 'Login berhasil! Mengalihkan...';
            loginText.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            // Show error message
            errorMessage.textContent = data.message || 'Login gagal. Periksa kredensial administrator Anda.';
            errorMessage.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        errorMessage.classList.remove('hidden');
    })
    .finally(() => {
        // Reset loading state if there was an error
        if (!loginText.classList.contains('hidden') && loginText.textContent === 'Login berhasil! Mengalihkan...') {
            return; // Don't reset if success
        }
        
        submitButton.disabled = false;
        loginText.textContent = 'Masuk sebagai Administrator';
        loginText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    });
});

// Add security warning on page load
document.addEventListener('DOMContentLoaded', function() {
    console.warn('🔐 ADMINISTRATOR LOGIN PAGE - Unauthorized access is strictly prohibited');
});
</script>
@endsection