<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleView extends Model
{
    protected $fillable = [
        'vehicle_id',
        'bucket_hour',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'bucket_hour' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
