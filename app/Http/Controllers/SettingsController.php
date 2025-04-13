<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    /**
     * Update user visibility settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateVisibility(Request $request)
    {
        $user = Auth::user();
        
        // Update the is_hidden status based on checkbox
        $user->is_hidden = $request->has('is_hidden');
        $user->save();
        
        return redirect()->route('settings')
            ->with('visibility_status', 'Your visibility preferences have been updated successfully.');
    }
    
    /**
     * Export all user data as a JSON file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportUserData()
    {
        $user = Auth::user();
        
        // Collect user data
        $userData = [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'is_hidden' => $user->is_hidden,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'keypair' => $user->keyPair ? [
                'id' => $user->keyPair->id,
                'public_key' => $user->keyPair->public_key,
                'created_at' => $user->keyPair->created_at,
                'updated_at' => $user->keyPair->updated_at,
            ] : null,
            'sent_messages' => $user->sentMessages()->get()->map(function($message) {
                return [
                    'id' => $message->id,
                    'recipient_id' => $message->recipient_id,
                    'recipient_username' => $message->recipient->username,
                    'encrypted_content' => $message->encrypted_content,
                    'is_read' => $message->is_read,
                    'is_ephemeral' => $message->is_ephemeral,
                    'created_at' => $message->created_at,
                    'updated_at' => $message->updated_at,
                ];
            }),
            'received_messages' => $user->receivedMessages()->get()->map(function($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_username' => $message->sender->username,
                    'encrypted_content' => $message->encrypted_content,
                    'is_read' => $message->is_read,
                    'is_ephemeral' => $message->is_ephemeral,
                    'created_at' => $message->created_at,
                    'updated_at' => $message->updated_at,
                ];
            }),
        ];
        
        $filename = 'privmsg_data_export_' . $user->username . '_' . date('Y-m-d_H-i-s') . '.json';
        
        // Create a streamed response with the JSON data
        return new StreamedResponse(function() use ($userData) {
            echo json_encode($userData, JSON_PRETTY_PRINT);
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
