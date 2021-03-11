<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class vendors extends Model
{

    protected $fillable = ['name', 'address', 'phone', 'address','activation','category_id','offday_setting','title','about','banner1','banner2','banner3','rank','city'];
    public static function getAllVendors($request)
    {
       
        $query = vendors::join('categories','categories.id','=','vendors.category_id')->select('vendors.*','categories.name as category');

        if (empty($requestType)) {

            $count = $query->get()->count();
            $allData = $query->get();
            return ['data' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }
}
