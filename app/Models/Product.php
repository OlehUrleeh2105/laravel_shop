<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $casts = [
        'liked'          =>   'string',
        'user_id'        =>   'integer',
        'images'         =>   'string',
        'quantity'       =>   'integer',
        'total_price'    =>   'integer',
        'total_discount' =>   'integer',
        'order_id'       =>   'string',
    ];

    protected $fillable = [
        'title',
        'content',
        'category',
        'available',
        'discount',
        'price',
        'created_by',
    ];
}
