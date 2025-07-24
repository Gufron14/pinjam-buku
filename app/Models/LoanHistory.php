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
        'status', 
        'bukti_pinjam',
        'bukti_kembali',
        'denda',
        'denda_dibayar',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function isOverdue()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        // 2 Minggu
        // $dueDate = Carbon::parse($this->tanggal_pinjam)->addWeeks(2);
        // return Carbon::now()->gt($dueDate);

        // 30 Detik
        $dueDate = Carbon::parse($this->tanggal_pinjam)->addSeconds(30);
        return Carbon::now()->gt($dueDate);
    }

    public function daysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        // 2 Minggu
        // $dueDate = Carbon::parse($this->tanggal_pinjam)->addWeeks(2);
        // return Carbon::now()->diffInWeeks($dueDate);

        // 30 Detik
        $dueDate = Carbon::parse($this->tanggal_pinjam)->addSeconds(30);
        return Carbon::now()->diffInSeconds($dueDate);
    }

    public function calculateFine()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        // $secondsOverdue = $this->daysOverdue();
        $finePerBook = 10000;
        return $finePerBook;
    }

    /**
     * Get fine information
     */
    public function getFineInfo()
    {
        return [
            'seconds_overdue' => $this->daysOverdue(),
            'fine_per_book' => 10000,
            'total_fine' => $this->denda,
            'is_paid' => $this->denda_dibayar,
            // 'due_date' => Carbon::parse($this->tanggal_pinjam)->addWeeks(2)->format('d/m/Y H:i:s'),
            'due_date' => Carbon::parse($this->tanggal_pinjam)->addSeconds(30)->format('d/m/Y H:i:s'),
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
