<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;

class ReminderRecipient extends Model
{
    use HasFactory;

    protected $fillable = ['reminder_id', 'contact_id', 'created_by', 'updated_by'];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class,'reminder_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class,'contact_id');
    }
    
}
