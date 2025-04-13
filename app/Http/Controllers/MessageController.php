<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    /**
     * Send a new encrypted message.
     */
    public function send(Request $request)
    {
        // Debug the incoming request
        \Log::info('Message request data:', [
            'all' => $request->all(),
            'is_ephemeral' => $request->input('is_ephemeral'),
            'is_ephemeral_raw' => $request->get('is_ephemeral'),
            'is_ephemeral_type' => gettype($request->input('is_ephemeral')),
            'request_content' => $request->getContent()
        ]);
        
        $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'encrypted_content' => 'required|string',
            'signature' => 'required|string',
            'is_ephemeral' => 'boolean'
        ]);

        $sender = Auth::user();
        $recipient = User::findOrFail($request->recipient_id);
        
        // Ensure both users have key pairs
        if (!$sender->keyPair || !$recipient->keyPair) {
            return response()->json([
                'message' => 'Both sender and recipient must have registered public keys'
            ], 400);
        }
        
        // Explicitly convert is_ephemeral to boolean
        $isEphemeral = false;
        if ($request->has('is_ephemeral')) {
            $isEphemeralValue = $request->input('is_ephemeral');
            if (is_bool($isEphemeralValue)) {
                $isEphemeral = $isEphemeralValue;
            } else if (is_string($isEphemeralValue)) {
                $isEphemeral = $isEphemeralValue === 'true' || $isEphemeralValue === '1';
            } else if (is_numeric($isEphemeralValue)) {
                $isEphemeral = (bool)$isEphemeralValue;
            }
        }
        
        // Create the message
        $message = new Message();
        $message->sender_id = $sender->id;
        $message->recipient_id = $recipient->id;
        $message->encrypted_content = $request->encrypted_content;
        $message->signature = $request->signature;
        $message->is_read = false;
        $message->is_ephemeral = $isEphemeral;
        
        // Debug the message before saving
        \Log::info('Message before save:', [
            'message' => $message->toArray(),
            'is_ephemeral_final' => $isEphemeral,
            'is_ephemeral_type_final' => gettype($isEphemeral)
        ]);
        
        $message->save();
        
        // Debug the message after saving
        \Log::info('Message after save:', [
            'message' => $message->toArray()
        ]);
        
        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    /**
     * Get all messages for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get messages where the user is sender or recipient
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(100)  // Limit to recent messages
            ->get();
        
        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Long polling for new messages (more efficient than constant polling).
     */
    public function poll(Request $request)
    {
        $request->validate([
            'last_message_id' => 'nullable|integer'
        ]);
        
        $userId = Auth::id();
        $lastMessageId = $request->input('last_message_id', 0);
        
        // Set a timeout for long polling (20 seconds)
        $timeout = 20;
        $start = time();
        
        // Keep checking for new messages until timeout
        while (time() - $start < $timeout) {
            // Check for new messages
            $newMessages = Message::where(function($query) use ($userId) {
                    $query->where('sender_id', $userId)
                          ->orWhere('recipient_id', $userId);
                })
                ->where('id', '>', $lastMessageId)
                ->orderBy('id', 'asc')
                ->get();
            
            // Clean up any read ephemeral messages - these should have been deleted
            // but might still exist due to race conditions or bugs
            $messagesToReturn = $newMessages->filter(function($message) use ($userId) {
                // If this is a read ephemeral message sent to current user, it should be deleted
                if ($message->is_ephemeral && $message->is_read && $message->recipient_id == $userId) {
                    \Log::info('Cleaning up read ephemeral message found during polling', [
                        'message_id' => $message->id
                    ]);
                    
                    // Delete it
                    $message->delete();
                    
                    // Don't include it in the results
                    return false;
                }
                
                // Include all other messages
                return true;
            });
            
            // If new messages are found, return them
            if ($messagesToReturn->count() > 0) {
                return response()->json([
                    'messages' => $messagesToReturn->values()
                ]);
            }
            
            // Wait for a short time before checking again
            usleep(500000); // 500ms
        }
        
        // If timeout is reached with no new messages
        return response()->json([
            'messages' => []
        ]);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(int $messageId)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $messageId)
            ->where('recipient_id', $user->id)
            ->firstOrFail();
        
        // Debug the message
        \Log::info('Marking message as read:', [
            'message_id' => $messageId,
            'is_ephemeral' => $message->is_ephemeral,
            'is_ephemeral_type' => gettype($message->is_ephemeral)
        ]);
        
        // Check if this is an ephemeral message
        if ($message->is_ephemeral) {
            // Delete ephemeral message after reading
            $message->delete();
            
            \Log::info('Deleted ephemeral message', ['message_id' => $messageId]);
            
            return response()->json([
                'message' => 'Ephemeral message viewed and deleted'
            ]);
        } else {
            // Regular message handling
            $message->is_read = true;
            $message->save();
            
            \Log::info('Marked message as read', [
                'message_id' => $messageId,
                'is_read' => $message->is_read
            ]);
            
            return response()->json([
                'message' => 'Message marked as read',
                'data' => $message
            ]);
        }
    }

    /**
     * Get all conversations for the authenticated user.
     */
    public function getConversations()
    {
        $userId = Auth::id();
        
        // Get users who have had conversation with the current user
        $conversationUsers = DB::select("
            SELECT 
                u.id, 
                u.username,
                COALESCE(unread.count, 0) as unread_count,
                latest.id as latest_message_id,
                latest.sender_id,
                latest.recipient_id,
                latest.encrypted_content,
                latest.signature,
                latest.is_read,
                latest.created_at
            FROM users u
            JOIN (
                SELECT 
                    CASE 
                        WHEN sender_id = ? THEN recipient_id
                        ELSE sender_id
                    END as user_id,
                    MAX(id) as max_id
                FROM messages
                WHERE sender_id = ? OR recipient_id = ?
                GROUP BY user_id
            ) as max_msgs ON u.id = max_msgs.user_id
            LEFT JOIN messages latest ON latest.id = max_msgs.max_id
            LEFT JOIN (
                SELECT 
                    sender_id, 
                    COUNT(*) as count
                FROM messages
                WHERE recipient_id = ? AND is_read = 0
                GROUP BY sender_id
            ) as unread ON unread.sender_id = u.id
            WHERE u.id != ?
            ORDER BY latest.created_at DESC
        ", [$userId, $userId, $userId, $userId, $userId]);
        
        $conversations = [];
        
        foreach ($conversationUsers as $convoUser) {
            $latestMessage = null;
            
            if ($convoUser->latest_message_id) {
                $latestMessage = [
                    'id' => $convoUser->latest_message_id,
                    'sender_id' => $convoUser->sender_id,
                    'recipient_id' => $convoUser->recipient_id,
                    'encrypted_content' => $convoUser->encrypted_content,
                    'signature' => $convoUser->signature,
                    'is_read' => (bool)$convoUser->is_read,
                    'created_at' => $convoUser->created_at
                ];
            }
            
            $conversations[] = [
                'user' => [
                    'id' => $convoUser->id,
                    'username' => $convoUser->username
                ],
                'unread_count' => $convoUser->unread_count,
                'latest_message' => $latestMessage
            ];
        }
        
        return response()->json([
            'conversations' => $conversations
        ]);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function getConversation(int $userId)
    {
        $currentUserId = Auth::id();
        
        // Make sure the user exists
        User::findOrFail($userId);
        
        // Get messages between the two users
        $messages = Message::where(function($query) use ($currentUserId, $userId) {
                $query->where(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $currentUserId)
                      ->where('recipient_id', $userId);
                })->orWhere(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $userId)
                      ->where('recipient_id', $currentUserId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to recent messages
            ->get();
        
        // Automatically mark messages as read when retrieving a conversation
        // This ensures that when a user opens a conversation, all messages are marked as read
        $unreadMessages = $messages->filter(function($message) use ($currentUserId) {
            return $message->recipient_id == $currentUserId && !$message->is_read;
        });
        
        if ($unreadMessages->count() > 0) {
            // Handle ephemeral and regular messages separately
            $ephemeralIds = [];
            $regularIds = [];
            
            foreach ($unreadMessages as $message) {
                if ($message->is_ephemeral) {
                    $ephemeralIds[] = $message->id;
                } else {
                    $regularIds[] = $message->id;
                }
                
                // Update the is_read property in the collection we're returning
                $message->is_read = true;
            }
            
            // Bulk update regular messages
            if (count($regularIds) > 0) {
                Message::whereIn('id', $regularIds)
                    ->update(['is_read' => true]);
            }
            
            // Delete ephemeral messages
            if (count($ephemeralIds) > 0) {
                Message::whereIn('id', $ephemeralIds)->delete();
                \Log::info('Deleted ephemeral messages from conversation load', [
                    'message_ids' => $ephemeralIds,
                    'count' => count($ephemeralIds)
                ]);
            }
        }
        
        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Update the typing status of a user
     */
    public function updateTypingStatus(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'is_typing' => 'required|boolean'
        ]);

        $user = Auth::user();
        $recipientId = $request->recipient_id;
        $isTyping = $request->is_typing;
        
        // Use DB to store typing status instead of cache
        DB::table('typing_statuses')
            ->updateOrInsert(
                [
                    'sender_id' => $user->id,
                    'recipient_id' => $recipientId
                ],
                [
                    'is_typing' => $isTyping,
                    'updated_at' => now()
                ]
            );
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    /**
     * Get typing status for all users typing to the authenticated user
     */
    public function getTypingStatus()
    {
        $user = Auth::user();
        $typingUsers = [];
        
        // Get typing statuses where current user is the recipient
        $typingStatuses = DB::table('typing_statuses')
            ->where('recipient_id', $user->id)
            ->where('is_typing', true)
            ->where('updated_at', '>', now()->subSeconds(10)) // Only consider recent typing activity (within 10 seconds)
            ->get();
            
        foreach ($typingStatuses as $status) {
            $typingUsers[$status->sender_id] = true;
        }
        
        return response()->json([
            'typing_users' => $typingUsers
        ]);
    }

    /**
     * Delete all messages in a conversation between two users.
     * This will delete messages from both sides of the conversation.
     */
    public function deleteConversation(int $userId)
    {
        $currentUserId = Auth::id();
        
        // Make sure the user exists
        User::findOrFail($userId);
        
        // Delete all messages between the two users
        $deletedCount = Message::where(function($query) use ($currentUserId, $userId) {
                $query->where(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $currentUserId)
                      ->where('recipient_id', $userId);
                })->orWhere(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $userId)
                      ->where('recipient_id', $currentUserId);
                });
            })
            ->delete();
        
        return response()->json([
            'message' => 'Conversation deleted successfully',
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Delete a specific message.
     * Both sender and recipient can delete messages.
     */
    public function deleteMessage(int $messageId)
    {
        $user = Auth::user();
        
        // Find the message and verify the user has permission to delete it
        $message = Message::where('id', $messageId)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            })
            ->firstOrFail();
        
        // Delete the message
        $message->delete();
        
        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }
}
