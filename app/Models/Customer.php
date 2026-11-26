<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'phone', 'email', 'address', 'emergency_contact', 'emergency_phone'])]
class Customer extends Model
{
    use SoftDeletes;

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function boardings(): HasMany
    {
        return $this->hasMany(Boarding::class);
    }

    public function groomings(): HasMany
    {
        return $this->hasMany(Grooming::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
