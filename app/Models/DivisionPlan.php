<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DivisionPlan extends Model
{
    protected $table = 'division_plans';
    protected $fillable = [
        'name',
        'total_parts',
    ];

    public function deadlines(): HasMany
    {
        return $this->hasMany(DivisionDeadline::class);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
