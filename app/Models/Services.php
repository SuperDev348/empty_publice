<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $fillable = [
        'title',
        'business_type',
        'referred_service_id',
        'price',
        'allow_cancel',
        'auto_confirm',
        'allow_booking_max_day_ago',
        'service_duration_type',
        'service_starting_date',
        'service_ending_date',
        'service_duration',
        'buffer_time',
        'multiple_bookings',
        'available_seat',
        'description',
        'service_starts',
        'service_ends',
        'max_booking',
        'alias',
        'created_by',
        'activation',
        'consider_children_for_price',
        'age_range',
        'percentage',
        'break_time',
        'vendor_id'
    ];

    public function serviceImage()
    {
        return $this->hasOne('App\Models\ServiceImage', 'service_id');
    }

    public function serviceEmployee()
    {
        return $this->hasMany('App\Models\ServiceEmployee', 'service_id');
    }

    public static function getService($id)
    {
        return Services::find($id);
    }

    public static function getAllService($request)
    {
      
        $business_type = Setting::getSettingValue('business_type');
        if(Auth::user()->is_admin){
        $query = Services::select('*')->where('business_type', $business_type->setting_value);
        }
        else{
        $vendor_id = Auth::user()->vendor_id;
        $query = Services::select('*')->where('business_type', $business_type->setting_value)->where('vendor_id', $vendor_id);

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
