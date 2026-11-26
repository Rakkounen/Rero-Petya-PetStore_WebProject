<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'code',
    'customer_id',
    'pet_id',
    'groomer_id',
    'boarding_id',
    'scheduled_at',
    'started_at',
    'finished_at',
    'subtotal',
    'discount',
    'total_price',
    'notes',
    'status',
    'payment_status',
])]
class Grooming extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function groomer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'groomer_id');
    }

    public function boarding(): BelongsTo
    {
        return $this->belongsTo(Boarding::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('id', 'price')
            ->withTimestamps();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
