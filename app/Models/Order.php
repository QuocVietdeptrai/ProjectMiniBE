<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'customer_name',
        'order_date',
        'status',
        'payment_method',
        'total',
    ];

    // Quan hệ với Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Quan hệ với OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
