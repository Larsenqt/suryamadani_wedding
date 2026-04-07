<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'order_date',
        'total_price',
        'status',
        'notes',
        'address',
        'phone',
        'approved_at',
        'invoice_number'
    ];

    protected $casts = [
        'order_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
        });
        
        static::saving(function ($order) {
            $order->total_price = $order->details->sum('subtotal');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}