<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class banners extends Model
{
    protected $fillable = ['title', 'image', 'activation', 'created_at', 'updated_at'];

}
