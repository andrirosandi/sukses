<?php

namespace App\Models;

// app/Models/Reminder.php
// namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'reminder_message', 'record_category_id', 'enabled',
        'repeat_every', 'on_days', 'on_time', 'created_by', 'updated_by'
    ];

    // protected $casts = [
    //     'on_days' => 'array',
    //     'on_times' => 'array'
    // ];

    public function recordCategory(): BelongsTo
    {
        return $this->belongsTo(RecordCategory::class, 'record_category_id');
    }
}
