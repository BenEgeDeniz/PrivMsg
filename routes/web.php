<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeyPairController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessagingViewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Transparency page
Route::get('/transparency', function () {
    return view('static.transparency');
})->name('transparency');

// Static pages
Route::get('/terms', function () {
    return view('static.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('static.privacy');
})->name('privacy');

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    // Authentication routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    // Settings updates
    Route::post('/settings/update-visibility', [SettingsController::class, 'updateVisibility'])
        ->name('settings.update-visibility');
    
    // Export user data
    Route::get('/settings/export-data', [SettingsController::class, 'exportUserData'])
        ->name('settings.export-data');
    
    // Account deletion
    Route::post('/account/delete', [AuthController::class, 'deleteAccount'])->name('account.delete');
    
    // Messaging interface - updated to use MessagingViewController
    Route::get('/messages', [MessagingViewController::class, 'show'])->name('messages');
    
    // Legacy explicit routes for testing purposes
    Route::get('/messages/desktop', function () {
        return view('messages');
    })->name('messages.desktop');
    
    Route::get('/messages/mobile', function () {
        return view('messages-mobile');
    })->name('messages.mobile');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Messages and Conversations
    Route::post('/api/messages', [MessageController::class, 'send']);
    Route::get('/api/messages', [MessageController::class, 'index']);
    Route::get('/api/messages/poll', [MessageController::class, 'poll']);
    Route::patch('/api/messages/{messageId}/read', [MessageController::class, 'markAsRead']);
    Route::delete('/api/messages/{messageId}', [MessageController::class, 'deleteMessage']);
    Route::get('/api/conversations', [MessageController::class, 'getConversations']);
    Route::get('/api/conversations/{userId}', [MessageController::class, 'getConversation']);
    Route::delete('/api/conversations/{userId}', [MessageController::class, 'deleteConversation']);
    
    // Typing Indicators
    Route::post('/api/typing-status', [MessageController::class, 'updateTypingStatus']);
    Route::get('/api/typing-status', [MessageController::class, 'getTypingStatus']);
    
    // API routes for the frontend
    Route::prefix('api')->group(function () {
        // Key Pair Management
        Route::get('/keypair', [KeyPairController::class, 'showOwn']);
        Route::post('/keypair', [KeyPairController::class, 'store']);
        Route::get('/keypair/{userId}', [KeyPairController::class, 'show']);
        
        // Users
        Route::get('/users/search', [UserController::class, 'search']);
        Route::get('/users/search-exact', [UserController::class, 'searchExact']);
        Route::get('/users/{userId}', [UserController::class, 'show']);
    });
});
