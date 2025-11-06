<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',          
        'customer_name',    
        'phone',         
        'address',          
        'order_date',       
        'delivery_date',    
        'status',           
        'payment_method',  
        'total',          
    ];
}
