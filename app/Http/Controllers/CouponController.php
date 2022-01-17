<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Http\Requests\SendContactMessage;
use App\ProductCoupon;
use App\Product;
use App\ProductImage;


class CouponController extends Controller
{
    // products
    public function products(Request $request){
        $user_id = auth()->user()->id;
        $product_coupon = ProductCoupon::where('user_id' , $user_id)->orderBy('id' , 'desc')->get();
        
        $product_ids = [];
        for($i = 0; $i < count($product_coupon) ; $i++){
            $product_ids[$i] = $product_coupon[$i]['product_id'];
        }

        $product_ids = array_unique($product_ids);
		$product_ids = array_values($product_ids);
        
        $products = [];
        for($i= 0 ; $i < count($product_ids); $i++){
            $product = Product::select('id' , 'title' , 'competition_over' , 'winner_date as competition_date')->find($product_ids[$i]);
            $image = ProductImage::where('product_id' , $product['id'])->select('image')->first();
            $product['image'] = $image['image'];
            $product['coupons_count'] = ProductCoupon::where('user_id' , $user_id)->where('product_id' , $product['id'])->count();;
            $products[$i] = $product;
        }

        $response = APIHelpers::createApiResponse(false , 200 , ''  , $products);
        return response()->json($response , 200);  

    }

    public function productCoupons(Request $request){
        $product_id = $request->id;
        $user_id = auth()->user()->id;
        $data['coupons'] = ProductCoupon::where('user_id' , $user_id)->where('product_id' , $product_id)->select('id' , 'coupon_number' , 'winner', 'product_id' , 'created_at as date')->get();
        $product = Product::find($data['coupons'][0]['product_id']);
        $data['prize_name'] = $product['prize_name'];
		$data['prize_image'] = $product['prize_image'];
        $data['competition_over'] = $product['competition_over'];
        $data['winner_video'] = $product['winner_video'];
        $data['total_coupons_count'] = ProductCoupon::where('product_id' , $data['coupons'][0]['product_id'])->count();
		$data['winner_coupon_number'] = null;
		
		$coupons =  $data['coupons'];
		$first_coupon = $coupons[0];
		for($i = 0; $i < count($coupons); $i++){
			if($coupons[$i]['winner'] == 1){
				$data['winner_coupon_number'] = $coupons[$i]['coupon_number'];
				$data['coupons'][0] = $coupons[$i];
				$data['coupons'][$i] = $first_coupon;
			}
		}
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200); 
    }

}