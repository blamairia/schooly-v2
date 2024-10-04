<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyYear extends Model
{
    protected $fillable = [
        'year',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'study_year');
    }
}
