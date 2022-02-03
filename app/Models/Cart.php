<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'user_id',
        'payment_ref',
        'order_ref',
    ];
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function cart_item()
    {
    	return $this->hasMany(CartItem::class);
    }
}
