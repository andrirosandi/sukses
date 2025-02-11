<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecordCategory extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name','description', 'created_by', 'updated_by'];
}
