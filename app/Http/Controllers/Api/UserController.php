<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // Get limit (default 10, max 100)
        // ==========================================
        $limit = $request->get('limit', 10);

        // Prevent too large requests
        if ($limit > 100) $limit = 100;
        if ($limit < 1)   $limit = 1;

        // ==========================================
        // Fetch users (exclude admins)
        // ==========================================
        $users = User::where('role', 'user')
                     ->select('id', 'name', 'email', 'created_at')
                     ->latest()
                     ->paginate($limit);

        // ==========================================
        // Return structured JSON response
        // ==========================================
        return response()->json([
            'success' => true,
            'data'    => $users->items(),
            'pagination' => [
                'total'        => $users->total(),
                'per_page'     => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
                'has_more'     => $users->hasMorePages(),
                'next_page'    => $users->currentPage() < $users->lastPage()
                                    ? $users->currentPage() + 1
                                    : null,
                'prev_page'    => $users->currentPage() > 1
                                    ? $users->currentPage() - 1
                                    : null,
            ],
        ]);
    }
}