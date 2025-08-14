<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;

    protected $fillable = ['nama_age_group'];

    protected $table = 'age_groups';

    protected $primaryKey = 'id_age_group';

    public function users()
    {
        return $this->hasMany(User::class, 'id_age_group', 'id_age_group');
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'id_age_group', 'id_age_group');
    }
}
