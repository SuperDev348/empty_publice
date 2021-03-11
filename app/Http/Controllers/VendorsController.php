<?php

namespace App\Http\Controllers;
use App\vendors;
use App\API\CustomDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Models\VendorImage;
use Illuminate\Support\Str;
class VendorsController extends Controller
{
    public function vendorsIndex()
    {
        return view('vendors.index');
    }

    public function getData(Request $request)
    {
       
        $getVendorList = vendors::getAllVendors($request);
       // error_log('xx'.json_encode($getVendorList));
         $allgetVendorList = $getVendorList['data'];
    
         
      return ['datarows' => $allgetVendorList, 'count' => $getVendorList['count']];
    
    }

    public function getVendor($id)
    { 
        return  vendors::where('vendors.id', $id)->select('*')->first();
    }
    public function updateVendor(Request $request, $id)
    { 

      
        $vendor = vendors::find($id);
        $Temparr= $request->all();
        if (  $request->has('uploadsbanner1')) {
            $file = $request->uploadsbanner1;
            $uniqid = Str::random(9);
            $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
            $file->move(public_path('files'), $oiginalName);
            $Temparr= array_merge($Temparr,['banner1'=>$oiginalName]);
            }
            if (  $request->has('uploadsbanner2')) {
                $file = $request->uploadsbanner2;
                $uniqid = Str::random(9);
                $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                $file->move(public_path('files'), $oiginalName);
                $Temparr= array_merge($Temparr,['banner2'=>$oiginalName]);
                }
                if (  $request->has('uploadsbanner3')) {
                    $file = $request->uploadsbanner3;
                    $uniqid = Str::random(9);
                    $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                    $file->move(public_path('files'), $oiginalName);
                    $Temparr= array_merge($Temparr,['banner3'=>$oiginalName]);
                    }
        $vendor->update($Temparr);
        if ($vendor) {
             
        $ServiceImage=-1;
        if ($vendor->id && $request->has('uploads')) {

        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.$file->getClientOriginalName());
        $file->move(public_path('files'), $oiginalName);
        $arr = array(
            "vendor_id" => $vendor->id,
            "image" => $oiginalName,
            "is_default" => 1,
        );
        $ServiceImage =VendorImage::where('vendor_id', $id)->update($arr);
    }


 
        $response = [
            'message' => Lang::get('lang.service_updated_successfully'),
        ];
  
           
     }
            return response()->json($response, 201);
        

        $response = [
            'message' => Lang::get('lang.error_during_update')
        ];

        return response()->json($response, 404);
            
    }
    public function addVendor(Request $request)
    {
        $Temparr= $request->all();
        if (  $request->has('uploadsbanner1')) {
            $file = $request->uploadsbanner1;
            $uniqid = Str::random(9);
            $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
            $file->move(public_path('files'), $oiginalName);
            $Temparr= array_merge($Temparr,['banner1'=>$oiginalName]);
            }
            if (  $request->has('uploadsbanner2')) {
                $file = $request->uploadsbanner2;
                $uniqid = Str::random(9);
                $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                $file->move(public_path('files'), $oiginalName);
                $Temparr= array_merge($Temparr,['banner2'=>$oiginalName]);
                }
                if (  $request->has('uploadsbanner3')) {
                    $file = $request->uploadsbanner3;
                    $uniqid = Str::random(9);
                    $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                    $file->move(public_path('files'), $oiginalName);
                    $Temparr= array_merge($Temparr,['banner3'=>$oiginalName]);
                    }

        if ($service = vendors::create($Temparr)) {
 
           $ServiceImage=-1;
            if ($service->id && $request->has('uploads')) {

                $file = $request->uploads;
                $uniqid = Str::random(9);
                $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                $file->move(public_path('files'), $oiginalName);
                $arr = array(
                    "vendor_id" => $service->id,
                    "image" => $oiginalName,
                    "is_default" => 1,
                );
                $ServiceImage =VendorImage::insert($arr);

            } else if ($service->id) {
                $oiginalName = 'service.png';
                $arr = array(
                    "vendor_id" => $service->id,
                    "image" => $oiginalName,
                    "is_default" => 1,
                );
            
                $ServiceImage = VendorImage::insert($arr);
            }
            


            if($ServiceImage){
                $response = [
                    'message' => Lang::get('lang.created_successfully'),
                ];
            }

            return response()->json($response, 201);
        }

            $response = [
                'message' => Lang::get('lang.error_during_creating')
            ];

            return response()->json($response, 404);
         
    }

    public function allVendorsid()
    {
        $data = vendors::all();

        return $data;
    }
    public function getvendorsByCategory($id)
    { 
        $data = vendors::join('vendor_images','vendors.id','=','vendor_images.vendor_id')->
        select('vendors.*','vendor_images.image as image')->where('category_id',$id)
        ->orderBy('rank', 'ASC')->get();
        return $data;
    }

    public function vendorprofileData()
    { 
        $is_admin = Auth::user()->is_admin;
        if($is_admin){
            return ['is_admin'=>$is_admin] ;

        }
        $vendor_id = Auth::user()->vendor_id;
        $data = vendors::where('id',$vendor_id)->first();
        return $data;
    }

    public function updateMyProfile(Request $request)
    { 
        $vendor_id = Auth::user()->vendor_id;
        $vendor = vendors::find($vendor_id);
        $Temparr= $request->all();
        if (  $request->has('uploadsbanner1')) {
            $file = $request->uploadsbanner1;
            $uniqid = Str::random(9);
            $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
            $file->move(public_path('files'), $oiginalName);
            $Temparr= array_merge($Temparr,['banner1'=>$oiginalName]);
            }
            if (  $request->has('uploadsbanner2')) {
                $file = $request->uploadsbanner2;
                $uniqid = Str::random(9);
                $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                $file->move(public_path('files'), $oiginalName);
                $Temparr= array_merge($Temparr,['banner2'=>$oiginalName]);
                }
                if (  $request->has('uploadsbanner3')) {
                    $file = $request->uploadsbanner3;
                    $uniqid = Str::random(9);
                    $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
                    $file->move(public_path('files'), $oiginalName);
                    $Temparr= array_merge($Temparr,['banner3'=>$oiginalName]);
                    }
        $vendor->update($Temparr);
        if ($vendor) {
             
        $ServiceImage=-1;
        if ($vendor->id && $request->has('uploads')) {

        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
        $file->move(public_path('files'), $oiginalName);
        $arr = array(
            "vendor_id" => $vendor->id,
            "image" => $oiginalName,
            "is_default" => 1,
        );
        $ServiceImage =VendorImage::where('vendor_id', $id)->update($arr);
    }


 
        $response = [
            'message' => Lang::get('lang.service_updated_successfully'),
        ];
  
           
     }
            return response()->json($response, 201);
        

        $response = [
            'message' => Lang::get('lang.error_during_update')
        ];

        return response()->json($response, 404);
            
    }

    
    public function getallvendors()
    { 
        $data = vendors::join('vendor_images','vendors.id','=','vendor_images.vendor_id')->
        select('vendors.*','vendor_images.image as image')
        ->orderBy('rank', 'ASC')->get();
        return $data;
    }
    
    public function vendorOffdays(Request $request)
    {
        $is_admin = Auth::user()->is_admin;
        if($is_admin){
            $response = [
                'message' => Lang::get('lang.error_update')
            ];
            return response()->json($response, 404);

        }

        //else if vendor
        $offday_setting = $request->offday_setting;
        $vendor_id = Auth::user()->vendor_id;
        $updateData = vendors::where('vendors.id', $vendor_id)->update(['offday_setting' => implode(',', $offday_setting)]);
        if ($updateData) {
            $response = [
                'message' => Lang::get('lang.offday_successfully_saved')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.error_update')
            ];
            return response()->json($response, 404);
        }
    }

    public function vendorOffdaysData()
    {
       
        $is_admin = Auth::user()->is_admin;
        if($is_admin){
            return ['is_admin'=>$is_admin] ;

        }
        $vendor_id = Auth::user()->vendor_id;
        $data=  vendors::where('vendors.id', $vendor_id)->select('offday_setting')->first();
           $data->offday_setting = explode(',', $data->offday_setting);
           $data->is_admin=$is_admin;
           return $data;

    }

}
