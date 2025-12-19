<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parents extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'birth_place',
        'phone_number',
        'address',
        'email',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
    public function getFullNameAttribute(): string
{
    return $this->first_name . ' ' . $this->last_name;
}

}
