<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if (  $request->has('uploads')) {
        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.$file->getClientOriginalName());
        $file->move(public_path('files'), $oiginalName);
        }else{
            $oiginalName = 'service.png';
        }
        if ($service = Category::create(array_merge($request->all(),['icon'=>$oiginalName]))) {
 
            
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
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  Category::where('id', $id)->select('*')->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $vendor = Category::find($id);
        $oiginalName='';
        if (  $request->has('uploads')) {
        $file = $request->uploads;
        $uniqid = Str::random(9);
        $oiginalName = str_replace(' ', '',$uniqid.$file->getClientOriginalName());
        $file->move(public_path('files'), $oiginalName);
        $vendor->update(array_merge($request->all(),['icon'=>$oiginalName]));
        }else{
            $vendor->update($request->all());
        }
       
        if ($vendor) {
       
        $response = [
            'message' => Lang::get('lang.service_updated_successfully'),
        ];
        return response()->json($response, 201);
           
     }
           
        

        $response = [
            'message' => Lang::get('lang.error_during_update')
        ];

        return response()->json($response, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    public function getCategoryList()
    {
      
        return Category::getCategoryList();
    }

    public function getAllCategory()
    {
        $data = Category::all();

        return $data;
    }
}
