<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_buku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'id_kategori',
        'id_genre',
        'id_jenis',
        'stok',
        'penulis',
        'tahun_terbit',
    ];

    /**
     * Check if the book is available for borrowing
     */
    public function isAvailable()
    {
        return $this->stok > 0;
    }

    /**
     * Get the category that owns the book.
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the genre that owns the book.
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'id_genre', 'id_genre');
    }

    /**
     * Get the type that owns the book.
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'id_jenis', 'id_jenis');
    }

    /**
     * Get loan histories for this book
     */
    public function loanHistories()
    {
        return $this->hasMany(LoanHistory::class, 'id_buku', 'id_buku');
    }

    /**
     * Get current borrowers count
     */
    public function getCurrentBorrowersCount()
    {
        return $this->loanHistories()->where('status', 'dipinjam')->count();
    }

    /**
     * Get available stock
     */
    public function getAvailableStock()
    {
        return $this->stok - $this->getCurrentBorrowersCount();
    }
}
