<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'dob',
        'gender',
        'email',
        'phone',
        'class',
        'avatar',
        'order_id',
    ];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
