@extends('layouts.app')

@section('title', 'Register - UMKMBid')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-2xl md:text-3xl font-extrabold text-gray-900">
                Daftar Akun Baru
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-secondary transition duration-200">
                    Masuk di sini
                </a>
            </p>
        </div>
        
        <div class="card shadow-lg border border-gray-100">
            <div class="card-body">
                <form id="registerForm" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Masukkan email Anda">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Minimal 8 karakter">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Ulangi password Anda">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Daftar Sebagai</label>
                        <select id="role" name="role" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm transition duration-200">
                            <option value="">Pilih peran Anda</option>
                            <option value="umkm_owner">Pemilik UMKM</option>
                            <option value="investor">Investor</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon (Opsional)</label>
                        <input id="phone" name="phone" type="tel" 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="Contoh: 081234567890">
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat (Opsional)</label>
                        <textarea id="address" name="address" rows="3"
                                  class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                                  placeholder="Masukkan alamat lengkap Anda"></textarea>
                    </div>
                    
                    <div>
                        <label for="identity_number" class="block text-sm font-medium text-gray-700">Nomor Identitas (Opsional)</label>
                        <input id="identity_number" name="identity_number" type="text" 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition duration-200" 
                               placeholder="NIK/Passport">
                    </div>
                    
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>
                    
                    <div>
                        <button type="submit" 
                                class="btn-primary w-full transition duration-200">
                            <span id="register-text">Daftar</span>
                            <span id="loading-spinner" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const registerText = document.getElementById('register-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    
    // Validate password confirmation
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirmation) {
        errorMessage.textContent = 'Konfirmasi password tidak sesuai.';
        errorMessage.classList.remove('hidden');
        return;
    }
    
    // Show loading state
    submitButton.disabled = true;
    registerText.classList.add('hidden');
    loadingSpinner.classList.remove('hidden');
    errorMessage.classList.add('hidden');
    
    const formData = new FormData(e.target);
    
    fetch('/register', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to dashboard
            window.location.href = data.redirect;
        } else {
            // Show error message
            let errorText = data.message || 'Registrasi gagal. Silakan coba lagi.';
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
        registerText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    });
});
</script>
@endsection