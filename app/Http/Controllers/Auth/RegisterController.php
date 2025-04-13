<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
            'message' => 'Registration successful. Please generate your encryption keys.',
        ], 201);
    }
}
