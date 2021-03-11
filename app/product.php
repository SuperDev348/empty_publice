<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = ['title', 'price', 'quantity', 'description','vendor_id','activation','image','created_at','updated_at'];
}
