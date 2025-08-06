<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
        'ktp',
        'avatar',
    ];

    /**
     * Relasi dengan Genre
     */
    // public function genre()
    // {
    //     return $this->belongsTo(Genre::class);
    // }

    /**
     * Relasi dengan Loans (menggunakan LoanHistory)
     */
    // public function loans()
    // {
    //     return $this->hasMany(LoanHistory::class);
    // }

    /**
     * Mendapatkan peminjaman yang sedang aktif
     */
    // public function activeLoans()
    // {
    //     return $this->hasMany(LoanHistory::class)->where('status', 'dipinjam');
    // }

    /**
     * Mendapatkan riwayat peminjaman
     */
    // public function loanHistory()
    // {
    //     return $this->hasMany(LoanHistory::class)->orderBy('created_at', 'desc');
    // }

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
}
