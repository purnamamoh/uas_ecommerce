<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_per_item',
    ];

    public function order()
    {
        return $this->belongsTo(OrderModel::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
