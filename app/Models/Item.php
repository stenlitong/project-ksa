<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class);
    }

    public function orderDo(){
        return $this->hasMany(OrderDo::class);
    }
}
