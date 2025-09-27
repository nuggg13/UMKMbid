<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::where('user_id', Auth::id())
                             ->with('auctions')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('umkm.businesses.index', compact('businesses'));
    }

    public function apiIndex()
    {
        $businesses = Business::where('user_id', Auth::id())
                             ->with(['auctions' => function($query) {
                                 $query->latest();
                             }])
                             ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json($businesses);
    }

    public function create()
    {
        return view('umkm.businesses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:fb,ecommerce,tech,retail,manufaktur,service,manufacturing,education,health,agriculture,creative',
            'description' => 'required|string',
            'business_license' => 'nullable|string|max:255',
            'annual_revenue' => 'nullable|numeric|min:0',
            'employee_count' => 'nullable|integer|min:1',
            'established_date' => 'nullable|date|before:today',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|string|max:255',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $business = Business::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'business_license' => $request->business_license,
            'annual_revenue' => $request->annual_revenue,
            'employee_count' => $request->employee_count,
            'established_date' => $request->established_date,
            'website' => $request->website,
            'social_media' => $request->social_media,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Business registered successfully. Awaiting admin approval.',
            'business' => $business,
            'redirect' => route('umkm.businesses.show', $business)
        ]);
    }

    public function show(Business $business)
    {
        // Check if user owns this business or is admin
        if ($business->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $business->load(['auctions.bids', 'user']);
        
        return view('umkm.businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        // Check if user owns this business
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        return view('umkm.businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        // Check if user owns this business
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:fb,ecommerce,tech,retail,manufaktur,service,manufacturing,education,health,agriculture,creative',
            'description' => 'required|string',
            'business_license' => 'nullable|string|max:255',
            'annual_revenue' => 'nullable|numeric|min:0',
            'employee_count' => 'nullable|integer|min:1',
            'established_date' => 'nullable|date|before:today',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|string|max:255',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $business->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Business updated successfully',
            'business' => $business
        ]);
    }

    public function destroy(Business $business)
    {
        // Check if user owns this business
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if business has active auctions
        if ($business->auctions()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete business with active auctions'
            ], 422);
        }

        $business->delete();

        return response()->json([
            'success' => true,
            'message' => 'Business deleted successfully'
        ]);
    }

    // Admin methods
    public function adminIndex()
    {
        $businesses = Business::with('user')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function adminShow(Business $business)
    {
        $business->load(['auctions.bids', 'user']);
        
        return view('admin.businesses.show', compact('business'));
    }

    public function approve(Business $business)
    {
        $business->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Business approved successfully'
        ]);
    }

    public function reject(Business $business)
    {
        $business->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Business rejected'
        ]);
    }
}
