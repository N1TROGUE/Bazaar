<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'name',
        'email',
        'password',
        'role_id'
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 4; //
    }

    public function isAdvertiser(): bool
    {
        return in_array($this->role_id, [2, 3]);
    }

    /**
     * The adverts that the user has favorited.
     */
    public function favoriteAdverts()
    {
        return $this->belongsToMany(Advertisement::class, 'favorite_advertisements', 'user_id', 'advertisement_id')->withTimestamps();
    }

    public function hasFavorited(Advertisement $advertisement): bool
    {
        return $this->favoriteAdverts()->where('advertisement_id', $advertisement->id)->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasReviewed(Advertisement $advertisement): bool
    {
        return $this->reviews()->where('advertisement_id', $advertisement->id)->exists();
    }
}
