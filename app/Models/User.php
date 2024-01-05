<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function straightConnections(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_connections', 'user_id', 'connected_user_id')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function inverseConnections(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_connections', 'connected_user_id', 'user_id')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function connections(): Collection
    {
        return $this->straightConnections
            ->merge($this->inverseConnections)
            ->unique('id')
            ->sortByDesc('pivot.created_at');
    }

    public function sentRequests(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'from_user_id');
    }

    public function receivedRequests(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'to_user_id');
    }
}
