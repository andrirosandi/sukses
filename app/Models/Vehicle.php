<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'license_plate', 'registered_owner', 'assigned_user', 
        'tax_expiry_date', 'plate_expiry_date', 'created_by', 'updated_by'
    ];
}
