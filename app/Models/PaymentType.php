<?php
namespace App\Models;

use App\Models\PaymentTotal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function paymentTypes(): BelongsTo
    {
        return $this->belongsTo(PaymentTotal::class);
    }
}
