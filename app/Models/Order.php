<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total', 'status', 'shipping_address', 'billing_address', 'payment_method',
        'payment_status', 'payment_reference', 'transaction_id', 'order_number'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope to filter orders by payment status
     */
    public function scopeByPaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }
}
