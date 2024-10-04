<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cassier extends Model
{
    protected $fillable = [
        'number',
        'is_rented',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
