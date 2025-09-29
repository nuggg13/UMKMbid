<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'identity_number' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new profile photo
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $profilePhotoPath;
        }

        // Prepare data for update
        $data = array_merge([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'identity_number' => $validated['identity_number'] ?? null,
        ], $data ?? []);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh(),
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update(['profile_photo' => null]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile photo deleted successfully',
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }
}
