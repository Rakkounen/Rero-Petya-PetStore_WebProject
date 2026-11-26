<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'customer_id',
    'name',
    'species',
    'breed',
    'gender',
    'birth_date',
    'weight',
    'size',
    'color',
    'is_vaccinated',
    'last_vaccination_date',
    'medical_notes',
    'behavior_notes',
    'photo',
])]
class Pet extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'weight' => 'decimal:2',
            'is_vaccinated' => 'boolean',
            'last_vaccination_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function boardings(): HasMany
    {
        return $this->hasMany(Boarding::class);
    }

    public function groomings(): HasMany
    {
        return $this->hasMany(Grooming::class);
    }
}
