<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
        protected $fillable = [
        'full_name',
        'dob',
        'gender',
        'email',
        'phone',
        'class',
        'avatar',
    ];
}
