<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanHistory extends Model
{
    use HasFactory;

    protected $table = 'loan_histories';
    protected $primaryKey = 'id_pinjaman';

    protected $fillable = [
        'id_user',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status', // 'dipinjam', 'dikembalikan', 'terlambat'
        'bukti_pinjam',
        'bukti_kembali',
        'denda',
        'denda_dibayar',
        'konfirmasi_admin',
    ];

    /**
     * Check if the loan is overdue (more than 2 weeks)
     */
    public function isOverdue()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        $dueDate = Carbon::parse($this->tanggal_pinjam)->addWeeks(2);
        return Carbon::now()->gt($dueDate);
    }

    /**
     * Calculate days overdue
     */
    public function daysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $dueDate = Carbon::parse($this->tanggal_pinjam)->addWeeks(2);
        return Carbon::now()->diffInDays($dueDate);
    }

    /**
     * Check if user has any unpaid fines
     */
    public static function hasUnpaidFines($userId)
    {
        return self::where('id_user', $userId)
            ->where(function ($query) {
                $query->where('denda', '>', 0)->where(function ($q) {
                    $q->where('denda_dibayar', false)->orWhere(function ($q2) {
                        $q2->where('denda_dibayar', true)->where('konfirmasi_admin', false);
                    });
                });
            })
            ->exists();
    }

    /**
     * Update status to 'terlambat' and calculate fine if overdue
     */
    public function checkAndUpdateOverdueStatus()
    {
        if ($this->isOverdue() && $this->status === 'dipinjam') {
            $this->status = 'terlambat';
            // Calculate fine: 5000 rupiah per day overdue
            $this->denda = $this->daysOverdue() * 5000;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Get the user that owns the loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Get the book that is loaned.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id_buku');
    }
}
