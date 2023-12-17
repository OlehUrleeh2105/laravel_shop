<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_order';

    protected $fillable = [
        'user_id',
        'product_id',
        'total_discount',
        'total_price',
        'total_quantity',
        'order_id',
        'status',
        'created_at',
    ];
}
