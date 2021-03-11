<?php

namespace App\Http\Controllers;

use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oiginalName='';
        $vendor_id = Auth::user()->vendor_id;
        if (  $request->has('uploads')) {
        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
        $file->move(public_path('files'), $oiginalName);
        }else{
            $oiginalName = 'service.png';
        }
        if ($service = product::create( array_merge($request->all(),['vendor_id'=>$vendor_id],['image'=>$oiginalName]))) {
  
            $oiginalName = "";
            $oiginalName = 'service.png';
         
            
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

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        return  product::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $product = product::find($id);
        if ( $request->has('uploads')) {
            $file = $request->uploads;
            $uniqid = Str::random(9);
            $oiginalName = str_replace(' ', '',$uniqid.".".$file->getClientOriginalExtension());
            $file->move(public_path('files'), $oiginalName);
            $product->update(array_merge($request->all(),['image'=>$oiginalName]));
            }
        else
        $product->update($request->all());
        if($product){
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        //
    }



    public function getData(Request $request)
    {
        $getproductsList =[];
        $vendor_id = Auth::user()->vendor_id;
        $query = (Auth::user()->is_admin)?product::select('*') : 
        product::select('*')->where('vendor_id', '=', $vendor_id);
        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();
            $getproductsList = ['data' => $allData, 'count' => $count];
        } else {
            $getproductsList = $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
     
         $allgetProductsList = $getproductsList['data'];
    
         
      return ['datarows' => $allgetProductsList, 'count' => $getproductsList['count']];
    
    }

    public function getProductsListDataByVendor($id)
    {
 
        $products = product::where('activation', 1)
            ->where('vendor_id', $id)->get();
        return [
            'products' => $products,
         
        ];
    }


 
}
