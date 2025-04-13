<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProofOfWorkController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'pow_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : back()->withErrors($validator)->withInput($request->except('password'));
        }

        // Verify the proof of work token
        $powToken = $request->input('pow_token');
        if (!ProofOfWorkController::isTokenValid($powToken)) {
            return $request->expectsJson()
                ? response()->json(['errors' => ['pow_token' => 'Invalid or expired CAPTCHA verification. Please try again.']], 422)
                : back()->withErrors(['pow_token' => 'Invalid or expired CAPTCHA verification. Please try again.'])
                    ->withInput($request->except('password'));
        }
        
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Invalidate the token after successful use
            ProofOfWorkController::invalidateToken($powToken);
            
            return $request->expectsJson()
                ? response()->json(['success' => true])
                : redirect()->intended('messages');
        }

        $error = ['username' => 'The provided credentials do not match our records.'];
        
        return $request->expectsJson()
            ? response()->json(['errors' => $error], 422)
            : back()->withErrors($error)->withInput($request->except('password'));
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'pow_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $request->expectsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }
        
        // Verify the proof of work token
        $powToken = $request->input('pow_token');
        if (!ProofOfWorkController::isTokenValid($powToken)) {
            return $request->expectsJson()
                ? response()->json(['errors' => ['pow_token' => 'Invalid or expired CAPTCHA verification. Please try again.']], 422)
                : back()->withErrors(['pow_token' => 'Invalid or expired CAPTCHA verification. Please try again.'])
                    ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        
        // Invalidate the token after successful use
        ProofOfWorkController::invalidateToken($powToken);

        Auth::login($user);

        return $request->expectsJson()
            ? response()->json(['success' => true])
            : redirect()->route('dashboard');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Delete user account and all associated data.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Confirm password for security
        $request->validate([
            'password' => 'required|string',
        ]);
        
        // Verify the password
        if (!Hash::check($request->password, $user->password)) {
            return $request->expectsJson()
                ? response()->json(['errors' => ['password' => 'Incorrect password.']], 422)
                : back()->withErrors(['password' => 'Incorrect password.']);
        }
        
        // Log the user out first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // The user model should have cascade deletes set up for related models
        // This will delete the user and all associated data
        $user->delete();
        
        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Your account has been permanently deleted.'])
            : redirect()->route('home')->with('status', 'Your account has been permanently deleted.');
    }
}