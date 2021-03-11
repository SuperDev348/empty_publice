<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name', 'nameAR', 'icon', 'activation' ];
    public static function getCategoryList()
    {
     
            $query = Category::select('*');
            $count = $query->get()->count();
            $allData = $query->get();
            return ['datarows' => $allData, 'count' => $count];
        
    }
}
