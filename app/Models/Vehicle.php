<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'vehicle_category_id', 'merk', 'type', 'license_plate',
        'registered_owner', 'assigned_user', 'tax_expiry_date',
        'plate_expiry_date', 'remind_me', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'tax_expiry_date' => 'date',
        'plate_expiry_date' => 'date',
        'remind_me' => 'boolean',
    ];

    public function vehicleCategory(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
