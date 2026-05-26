<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = ['make', 'model', 'year', 'price'];

    public function viewBuckets(): HasMany
    {
        return $this->hasMany(VehicleView::class);
    }
}
