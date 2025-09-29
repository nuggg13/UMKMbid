@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
                <p class="text-gray-600 mt-1">Kelola informasi profil Anda</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profil
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 auto-rows-fr">
            <!-- Main Profile Card - Large -->
            <div class="md:col-span-2 lg:col-span-3 md:row-span-2 bg-gradient-to-br from-primary to-secondary rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full translate-y-12 -translate-x-12"></div>
                
                <div class="relative z-10">
                    <div class="w-32 h-32 rounded-full mb-6 border-4 border-white border-opacity-40 overflow-hidden shadow-lg">
                        <img src="{{ $user->getProfilePhotoUrl() }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-2xl font-bold mb-2">{{ $user->name }}</h2>
                    <p class="text-white text-opacity-90 mb-4">{{ $user->email }}</p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-white bg-opacity-20 text-sm font-medium">
                        @switch($user->role)
                            @case('umkm_owner') 
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Pemilik UMKM 
                                @break
                            @case('investor') 
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                Investor 
                                @break
                            @case('admin') 
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                </svg>
                                Admin 
                                @break
                            @default {{ $user->role }}
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="md:col-span-2 lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Kontak</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <p class="font-medium text-gray-900">{{ $user->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900 break-all">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Identity Card -->
            <div class="md:col-span-2 lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Identitas</h3>
                </div>
                <div>
                    <p class="text-sm text-gray-500">No. Identitas</p>
                    <p class="font-medium text-gray-900">{{ $user->identity_number ?? 'Belum diisi' }}</p>
                </div>
            </div>

            <!-- Address Card -->
            <div class="md:col-span-4 lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Alamat</h3>
                </div>
                <div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $user->address ?? 'Alamat belum diisi. Silakan update profil Anda untuk menambahkan alamat.' }}
                    </p>
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="md:col-span-2 lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Status Akun</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Verifikasi</span>
                        @if($user->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Belum Verifikasi
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Bergabung</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="md:col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengaturan</h3>
                    <p class="text-sm text-gray-600 mb-4">Kelola akun dan preferensi Anda</p>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                        Edit Profil
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
