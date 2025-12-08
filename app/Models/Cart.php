<?php

namespace App\Models;

use App\Models\BaseModel;

class Cart extends BaseModel
{
    protected $primaryKey = 'cart_id';
    protected $table = 'carts';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}

