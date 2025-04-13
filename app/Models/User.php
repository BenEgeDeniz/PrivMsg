<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'is_hidden',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_hidden' => 'boolean',
        ];
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($user) {
            // Delete all related data when a user is deleted
            $user->keyPair()->delete();
            $user->sentMessages()->delete();
            $user->receivedMessages()->delete();
        });
    }
    
    /**
     * Get the keyPair associated with the user.
     */
    public function keyPair(): HasOne
    {
        return $this->hasOne(KeyPair::class);
    }
    
    /**
     * Get the sent messages for the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    /**
     * Get the received messages for the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
}
