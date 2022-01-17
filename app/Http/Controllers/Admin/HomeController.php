<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Product;
use App\Order;
use App\ProductCoupon;

class HomeController extends AdminController{

    // get all contact us messages
    public function show(){
        $data['users'] = User::count();
        $data['contact_us'] = ContactUs::count();
        $data['products'] = Product::where('deleted' , 0)->count();
        $data['orders'] = Order::count();
        $data['coupons'] = ProductCoupon::count();
        $data['winner_coupons'] = ProductCoupon::where('winner' , 1)->count();
        return view('admin.home' , ['data' => $data]);   
    }

}