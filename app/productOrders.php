<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productOrders extends Model
{
    protected $fillable = ['order_id', 'user_id', 'phone_number', 'status','quantity','total','product_price','created_at','updated_at'];

}
