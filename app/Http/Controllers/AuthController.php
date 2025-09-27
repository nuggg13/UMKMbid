<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:umkm_owner,investor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $credentials['role'] = $request->role;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => Auth::user(),
                'redirect' => $this->getRedirectPath(Auth::user())
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials or role mismatch'
        ], 401);
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Admin login successful',
                'user' => Auth::user(),
                'redirect' => '/admin/dashboard'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid admin credentials'
        ], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:umkm_owner,investor',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'identity_number' => $request->identity_number,
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'redirect' => $this->getRedirectPath($user)
        ]);
    }

    public function logout(Request $request)
    {
        try {
            Log::info('Logout attempt', ['user_id' => Auth::id(), 'session_id' => $request->session()->getId()]);
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            Log::info('Logout successful');

            // Check if request expects JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully'
                ]);
            }

            // Regular form submission - redirect to home
            return redirect('/')->with('success', 'Logged out successfully');
        } catch (\Exception $e) {
            Log::error('Logout error', ['error' => $e->getMessage()]);
            
            // If there's any error, still try to log out and redirect
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully'
                ]);
            }
            
            return redirect('/')->with('warning', 'Logged out with some issues');
        }
    }

    private function getRedirectPath($user)
    {
        switch ($user->role) {
            case 'admin':
                return '/admin/dashboard';
            case 'umkm_owner':
                return '/umkm/dashboard';
            case 'investor':
                return '/investor/dashboard';
            default:
                return '/';
        }
    }
}
