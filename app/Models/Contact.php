<?php

namespace App\Models;

use App\Models\ContactType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    //
    use HasFactory;

    protected $fillable = ['contact_type_id', 'contact_account', 'contact_name', 'created_by', 'updated_by'];

    public function contactType() {
        return $this->belongsTo(ContactType::class);
    }
}
