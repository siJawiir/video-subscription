<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $primaryKey = 'order_id';
    protected $table = 'orders';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount'
    ];

    public function casts(): array
    {
        return [
            'status' => 'integer',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
