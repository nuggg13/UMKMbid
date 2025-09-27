@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Edit Profil</h1>
            <a href="{{ route('profile.show') }}" class="text-blue-600 hover:underline">Lihat Profil</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1" for="name">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1" for="phone">No. Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1" for="identity_number">No. Identitas</label>
                    <input type="text" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-1" for="address">Alamat</label>
                    <textarea id="address" name="address" class="w-full border rounded px-3 py-2" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="mt-6 border-t pt-4">
                <h2 class="text-lg font-medium mb-2">Ubah Password (opsional)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-1" for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                <a href="{{ route('profile.show') }}" class="text-gray-700 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
