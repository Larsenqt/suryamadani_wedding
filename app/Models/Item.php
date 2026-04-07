<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item_type_id',
        'name',
        'price',
        'stock',
        'image' 
    ];

    public function type()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
