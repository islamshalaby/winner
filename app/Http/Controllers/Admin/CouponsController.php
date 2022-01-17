<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIHelpers;
use App\Product;
use App\ProductCoupon;
use App\User;

class CouponsController extends AdminController{
    
    // get all coupons
    public function show(){
        $data['coupons'] = ProductCoupon::orderBy('id' , 'desc')->get();
        for($i = 0; $i < count($data['coupons']); $i++){
            $data['coupons'][$i]['product'] = Product::select('id' , 'title')->find($data['coupons'][$i]['product_id']);
            $data['coupons'][$i]['user'] = User::select('id' , 'name')->find($data['coupons'][$i]['user_id']);
        }
        return view('admin.coupons' , ['data' => $data]);
    }

    // get coupon details
    public function details(Request $request){
        $data['coupon'] = ProductCoupon::find($request->id);
        $data['coupon']->seen = 1;
        $data['coupon']->save();
        $data['coupon']['user'] = User::select('id' , 'name')->find($data['coupon']['user_id']);
        $data['coupon']['product'] = Product::select('id' , 'title')->find($data['coupon']['product_id']);    
        return view('admin.coupon_details' , ['data' => $data]);
    }

    // delete coupon
    public function delete(Request $request){
        $coupon = ProductCoupon::find($request->id);
        $coupon->delete();
        return back();
    }

    // select Winner Controller
    public function SelectController(Request $request){
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if($product->remaining_quantity > 0){
            return back()->with('status' , 'The stock is not over');
        }
		
		$user_ids = ProductCoupon::where('product_id' , $product_id)->pluck('user_id')->toArray();
        $user_ids = array_unique($user_ids);
		$fcm_tokens = User::whereIn('id' , $user_ids)->pluck('fcm_token')->toArray();
		
		 $coupon = ProductCoupon::find($request->coupon);
        $coupon->winner = 1;
        $coupon->save();
		
		$body = 'رقم الكوبون الفائز هو '.$coupon->coupon_number;
		
		$notificationss = APIHelpers::send_notification('تم السحب علي منتج قمت بشرائه' , $body , '' , null , $fcm_tokens);   
          $product->winner_video = $request->winner_video;
        $product->competition_over = 1;
        $product->winner_date = date("d M Y");
        $product->save();


        return back();
    }
}