<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
        // Show single user detail
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Delete user
    public function destroy(User $user)
    {
        // Prevent deleting admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin user!');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }
}
