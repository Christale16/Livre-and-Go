<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'vehicle_type',
        'is_online',
        'current_lat',
        'current_lng',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'current_lat' => 'float',
        'current_lng' => 'float',
    ];

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isLivreur(): bool
    {
        return $this->role === 'livreur';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Commandes passées en tant que client
    public function ordersAsClient()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    // Commandes prises en tant que livreur
    public function ordersAsLivreur()
    {
        return $this->hasMany(Order::class, 'livreur_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
