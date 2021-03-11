<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    //
    protected $fillable = [
        'vendor_id',
        'first_name',
        'role',
        'last_name',
        'services'
    ];


    public static function getWorker($id)
    {
        return Worker::find($id);
    }
    public static function getAllWorkers($request)
    {
        $business_type = Setting::getSettingValue('business_type');
        if(Auth::user()->is_admin){
         $query = Worker::all();
        }
        else{
        $vendor_id = Auth::user()->vendor_id;
        $query = Worker::select('*')->where('vendor_id', $vendor_id);
        }

        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();
            return ['data' => $allData, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }
}
