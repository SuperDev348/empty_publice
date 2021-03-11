<?php

namespace App\Http\Controllers;
use App\banners;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
class bannersController extends Controller
{
    public function Index()
    {
        return view('banners.index');
    }

    public function getData(Request $request)
    {
        $getbannersList =[];
        $query = banners::select('*') ;
        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();
            $getbannersList = ['data' => $allData, 'count' => $count];
        } else {
            $getbannersList = $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
     
         $allList = $getbannersList['data'];   
      return ['datarows' => $allList, 'count' => $getbannersList['count']];
        
    }

    public function getBanner($id)
    { 
        return  banners::where('id', $id)->select('*')->first();
    }

    public function getallActiveBanner()
    { 
        return  banners::where('activation', '1')->select('*');
    }


    public function store(Request $request)
    {
        $oiginalName='';
        if (  $request->has('uploads')) {
        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
        $file->move(public_path('files'), $oiginalName);
        }else{
            $oiginalName = 'service.png';
        }
        if (banners::create( array_merge($request->all(),['image'=>$oiginalName]))) {
   
                 $response = [
                     'message' => Lang::get('lang.created_successfully'),
                 ];
             return response()->json($response, 201);
   
        }
 
            $response = [
                'message' => Lang::get('lang.error_during_creating')
            ];

            return response()->json($response, 404);
    }


    public function edit(Request $request, $id)
    {
        $banner = banners::find($id);
        if ( $request->has('uploads')) {
            $file = $request->uploads;
            $uniqid = Str::random(9);
            $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
            $file->move(public_path('files'), $oiginalName);
            $banner->update(array_merge($request->all(),['image'=>$oiginalName]));
            }
        else
        $banner->update($request->all());
        if($banner){
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

    public function delete($id)
    {
        banners::where('id', $id)->delete();
  
        $response = [
            'message' => Lang::get('lang.service_deleted_successfully')
        ];
        return response()->json($response, 200);
    }


}
