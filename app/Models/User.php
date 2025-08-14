<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'alamat',
        'no_telepon',
        'jenis_kelamin',
        'ttl',
        'ktp',
        'avatar',
        'tanggal_lahir',
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
        'password' => 'hashed',
    ];

    /**
     * Get the user's age based on birth year from ttl field
     */
    public function getUmurAttribute()
    {
        if (!$this->ttl) {
            return null;
        }

        // Extract birth year from ttl field (assuming format like "Jakarta, 1990-01-01" or just "1990")
        $birthYear = null;
        
        // Try to extract year from various formats
        if (preg_match('/(\d{4})/', $this->ttl, $matches)) {
            $birthYear = (int) $matches[1];
        }

        if ($birthYear) {
            return now()->year - $birthYear;
        }

        return null;
    }
}
