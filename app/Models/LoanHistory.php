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
    ];
    
    /**
     * Get the book that is loaned.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id_buku');
    }

        /**
     * Get the user that owns the loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

/**
 * Check if the loan is overdue (more than 30 seconds for testing)
 */
public function isOverdue()
{
    if ($this->status === 'dikembalikan') {
        return false;
    }

    $dueDate = Carbon::parse($this->tanggal_pinjam)->addSeconds(30); // Changed to 30 seconds for testing
    return Carbon::now()->gt($dueDate);
}

/**
 * Calculate days overdue (changed to seconds for testing)
 */
public function daysOverdue()
{
    if (!$this->isOverdue()) {
        return 0;
    }

    $dueDate = Carbon::parse($this->tanggal_pinjam)->addSeconds(30); // Changed to 30 seconds
    return Carbon::now()->diffInSeconds($dueDate); // Changed to seconds
}

/**
 * Calculate fine amount based on seconds overdue (for testing)
 */
public function calculateFine()
{
    if (!$this->isOverdue()) {
        return 0;
    }
    
    $secondsOverdue = $this->daysOverdue();
    $finePerSecond = 100; // Rp 100 per detik untuk testing
    return $secondsOverdue * $finePerSecond;
}

/**
 * Get fine information
 */
public function getFineInfo()
{
    return [
        'seconds_overdue' => $this->daysOverdue(), // Changed to seconds
        'fine_per_second' => 100, // Changed to per second
        'total_fine' => $this->denda,
        'is_paid' => $this->denda_dibayar,
        'due_date' => Carbon::parse($this->tanggal_pinjam)->addSeconds(30)->format('d/m/Y H:i:s'), // Show with time
    ];
}

/**
 * Update status to 'terlambat' and calculate fine if overdue
 */
public function checkAndUpdateOverdueStatus()
{
    if ($this->isOverdue() && $this->status === 'dipinjam') {
        $this->status = 'terlambat';
        // Calculate fine: 100 rupiah per second overdue (for testing)
        $this->denda = $this->calculateFine();
        $this->save();
        return true;
    }
    return false;
}


}
