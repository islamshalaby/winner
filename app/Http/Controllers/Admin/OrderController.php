<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\User;
use App\Product;
use App\UserAddress;
use App\ProductImage;

class OrderController extends AdminController{

    // get all orders
    public function show(){
        $data['orders'] = Order::with(['coupons', 'user', 'product'])->orderBy('id' , 'desc')->get();
        
        return view('admin.orders' ,['data' => $data]);
    }

    // get order details
    public function details(Request $request){
        $order_id = $request->id;
        $data['order'] = Order::find($order_id);
        
        $data['order']->seen = 1;
        $data['order']->save();

        $data['order']['user'] = User::find($data['order']['user_id']);
        $data['order']['product'] = Product::find($data['order']['product_id']);
        $data['order']['product']['image'] = ProductImage::where('product_id' , $data['order']['product']['id'])->first()['image'];
        $data['order']['address'] = UserAddress::find($data['order']['address_id']);
        return view('admin.order_details' , ['data' => $data]);
    }

    // set order delivered
    public function delivered(Request $request){
        $order = Order::find($request->id);
        $order->delivered = 1;
        $order->save();
        return back();
    }

}