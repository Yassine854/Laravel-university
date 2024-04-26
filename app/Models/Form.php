<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'description',
        'state',
        'student_id',
        'file',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
