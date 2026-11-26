<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['type', 'name', 'description', 'pet_species', 'price', 'duration_minutes', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function boardings(): BelongsToMany
    {
        return $this->belongsToMany(Boarding::class)
            ->withPivot('id', 'quantity', 'price')
            ->withTimestamps();
    }

    public function groomings(): BelongsToMany
    {
        return $this->belongsToMany(Grooming::class)
            ->withPivot('id', 'price')
            ->withTimestamps();
    }
}
