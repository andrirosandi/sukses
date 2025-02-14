<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageProvider extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'config'];

    protected $casts = [
        'config' => 'array', // Konversi otomatis ke array saat diambil
    ];

    public function setConfigAttribute($value)
    {
        $this->attributes['config'] = json_encode(
            collect($value)->map(function ($item) {
                return [
                    'key' => $item['key'],
                    'value' => Crypt::encryptString($item['value']),
                ];
            })->toArray()
        );
    }

    /**
     * Decrypt the config data when retrieving.
     */
    public function getConfigAttribute($value)
    {
        return collect(json_decode($value, true))->map(function ($item) {
            return [
                'key' => $item['key'],
                'value' => Crypt::decryptString($item['value']),
            ];
        })->toArray();
    }
    

}
