<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'code',
    'customer_id',
    'pet_id',
    'boarding_room_id',
    'handled_by',
    'check_in_date',
    'check_out_date',
    'actual_check_in_at',
    'actual_check_out_at',
    'total_days',
    'price_per_day',
    'addon_total',
    'discount',
    'total_price',
    'feeding_instructions',
    'special_notes',
    'belongings',
    'status',
    'payment_status',
])]
class Boarding extends Model
{
    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'actual_check_in_at' => 'datetime',
            'actual_check_out_at' => 'datetime',
            'price_per_day' => 'decimal:2',
            'addon_total' => 'decimal:2',
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

    public function room(): BelongsTo
    {
        return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('id', 'quantity', 'price')
            ->withTimestamps();
    }

    public function groomings(): HasMany
    {
        return $this->hasMany(Grooming::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
