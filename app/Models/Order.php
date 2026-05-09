<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total', 'status', 'shipping_address', 'billing_address', 'payment_method',
        'payment_status', 'payment_reference', 'transaction_id'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

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
