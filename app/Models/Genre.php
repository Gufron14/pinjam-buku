<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_genre';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_genre',
        'id_jenis',
    ];

    /**
     * Get the category that owns the genre.
     */
    // public function category()
    // {
    //     return $this->belongsTo(Categories::class, 'id_kategori', 'id_kategori');
    // }

    /**
     * Get the books for the genre.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'id_genre', 'id_genre');
    }

    /**
     * Relasi ke jenis buku (belongsTo)
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'id_jenis', 'id_jenis');
    }
}