<?php

namespace App\Http\Controllers;

use App\productOrders;
use App\product;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductOrdersController extends Controller
{
 
    public function index()
    {
        return view("products-order.index");
    }
 
    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        $user = Auth::user();;
        $p=product::find($request->id);
        $quantity=$request->quantity;
        $total= $p->price*$quantity;
        $orderdata = ['order_id' => $p->id,
                      'user_id'=>$user->id,
                      'phone_number'=>$user->phone,
                      'quantity'=>$quantity,
                      'total'=>$total,
                      'product_price'=>$p->price
        ]; 

        if(productOrders::create($orderdata)){
        $response = [
        'message' => Lang::get('lang.booking_saved_successfully')
        ];
        return response()->json($response, 200);
        }

    }

 
    public function show(productOrders $productOrders)
    {
        //
    }

 
    public function edit(productOrders $productOrders)
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        $order = productOrders::find($id);
        $order->update($request->all());
        if ($request->status == 'confirmed') {
            $returnData['message'] = Lang::get('lang.booking_is_confirmed');
        } elseif ($request->status == 'canceled') {
            $returnData['message'] = Lang::get('lang.booking_is_canceled');
        }
        return response()->json($returnData, 200);
    }

    

    
    public function getData(Request $request)
    {
        $getproductsList =[];
        $vendor_id = Auth::user()->vendor_id;
        $query =(Auth::user()->is_admin)?
        //if admin
        productOrders::join('users', 'product_orders.user_id', '=', 'users.id')
        ->join('products', 'product_orders.order_id', '=', 'products.id')
        ->select('product_orders.*',DB::raw('concat(users.first_name," ",users.last_name) as full_name'),'products.title as product_name') 
        ://else
        productOrders::join('users', 'product_orders.user_id', '=', 'users.id')
        ->join('products', 'product_orders.order_id', '=', 'products.id')
        ->where('products.vendor_id', '=', $vendor_id)
        ->select('product_orders.*',DB::raw('concat(users.first_name," ",users.last_name) as full_name'),'products.title as product_name');

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

 
}
