<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the currently authenticated user.
     */
    public function getCurrentUser()
    {
        $user = Auth::user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'has_key_pair' => $user->keyPair !== null
            ]
        ]);
    }
    
    /**
     * Get all users except the authenticated user.
     */
    public function index()
    {
        $currentUserId = Auth::id();
        
        $users = User::where('id', '!=', $currentUserId)
            ->where('is_hidden', false) // Only show users who haven't hidden themselves
            ->select('id', 'username')
            ->orderBy('username')
            ->get();
        
        return response()->json([
            'users' => $users
        ]);
    }
    
    /**
     * Get a specific user by ID.
     */
    public function show(int $userId)
    {
        $user = User::findOrFail($userId);

        // Prevent access to hidden profiles unless it's the user themselves
        if ($user->id !== Auth::id() && $user->is_hidden) {
            abort(404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'has_key_pair' => $user->keyPair !== null
            ]
        ]);
    }

    /**
     * Search for users by username.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $currentUserId = Auth::id();
        $query = $request->input('query');

        $users = User::where('id', '!=', $currentUserId)
            ->where('is_hidden', false) // Don't include hidden users in search results
            ->where('username', 'like', "%{$query}%")
            ->select('id', 'username')
            ->orderBy('username')
            ->limit(10)
            ->get();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Search for a user by exact username match.
     * This enhances privacy by only returning users that exactly match the provided username.
     */
    public function searchExact(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $currentUserId = Auth::id();
        $username = $request->input('username');

        $users = User::where('id', '!=', $currentUserId)
            ->where('is_hidden', false)
            ->where('username', $username) // Exact match only
            ->select('id', 'username')
            ->get();

        return response()->json([
            'users' => $users
        ]);
    }
}
