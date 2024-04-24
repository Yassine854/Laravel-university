<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'subject_user');
}
}
