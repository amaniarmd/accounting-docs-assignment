<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $reviewerRole = Role::where('name', 'reviewer')->first();
            $registrantRole = Role::where('name', 'registrant')->first();

            $user->roles()->attach([$reviewerRole->id, $registrantRole->id]);
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isReviewer()
    {
        return $this->roles()->where('name', 'reviewer')->exists();
    }

    public function blacklistDocs()
    {
        return $this->belongsToMany(Doc::class, 'blacklist', 'user_id', 'doc_id')->withTimestamps();
    }
}
