<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory',
        'quantity',
        'cart_id'
    ];
    protected $with = ['inventory'];

    public function cart()
    {
    	return $this->belongsTo(Cart::class);
    }
    public function inventory()
    {
    	return $this->belongsTo(Inventory::class, 'inventory');
    }

}
