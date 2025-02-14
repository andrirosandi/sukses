<?php

namespace App\Models;

use App\Models\ContactCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    //
    use HasFactory;

    protected $fillable = ['contact_category_id', 'account', 'name', 'created_by', 'updated_by'];

    public function contactCategory() {
        return $this->belongsTo(ContactCategory::class);
    }

    public function reminders() : BelongsToMany {
        return $this->belongsToMany(Reminder::class,'reminder_recipients');
    }
    
}
