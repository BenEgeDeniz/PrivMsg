<?php

namespace App\Http\Controllers;

use App\Models\KeyPair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeyPairController extends Controller
{
    /**
     * Store a newly created public key in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'public_key' => 'required|string'
        ]);

        // Get the current authenticated user
        $user = Auth::user();
        
        // Check if user already has a key pair
        $keyPair = $user->keyPair;
        
        if ($keyPair) {
            // Update the existing key pair
            $keyPair->public_key = $request->public_key;
            $keyPair->save();
        } else {
            // Create a new key pair
            $keyPair = new KeyPair();
            $keyPair->user_id = $user->id;
            $keyPair->public_key = $request->public_key;
            $keyPair->save();
        }

        return response()->json([
            'message' => 'Public key stored successfully',
            'public_key' => $keyPair->public_key
        ]);
    }

    /**
     * Display the public key of the authenticated user.
     */
    public function showOwn()
    {
        $user = Auth::user();
        $keyPair = $user->keyPair;
        
        return response()->json([
            'has_key_pair' => $keyPair !== null,
            'public_key' => $keyPair ? $keyPair->public_key : null
        ]);
    }

    /**
     * Display the public key of a specific user.
     */
    public function show(int $userId)
    {
        $user = User::findOrFail($userId);
        $keyPair = $user->keyPair;
        
        if (!$keyPair) {
            return response()->json([
                'message' => 'User has not registered a public key'
            ], 404);
        }
        
        return response()->json([
            'user_id' => $user->id,
            'username' => $user->username,
            'public_key' => $keyPair->public_key
        ]);
    }
}
