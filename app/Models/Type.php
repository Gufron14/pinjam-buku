<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_jenis',
    ];

    /**
     * Get the books for the type.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'id_jenis', 'id_jenis');
    }
}
