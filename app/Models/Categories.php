<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Get the genres for the category.
     */
    public function genres()
    {
        return $this->hasMany(Genre::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the books for the category.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'id_kategori', 'id_kategori');
    }
}
