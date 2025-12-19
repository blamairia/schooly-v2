<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyClass extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $table = 'study_classes';

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_assigned_id');
    }
}
