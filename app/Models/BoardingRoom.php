<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'room_type', 'pet_species', 'size', 'price_per_day', 'facilities', 'status'])]
class BoardingRoom extends Model
{
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
        ];
    }

    public function boardings(): HasMany
    {
        return $this->hasMany(Boarding::class);
    }
}
