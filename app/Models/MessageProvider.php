<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageProvider extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'config'];

    protected $casts = [
        'config' => 'array', // Konversi otomatis ke array saat diambil
    ];
}
