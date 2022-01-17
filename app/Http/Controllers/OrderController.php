<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\APIHelpers;
use App\Product;
use App\ProductImage;
use App\Setting;
use App\Order;
use App\ProductCoupon;
use App\UserAddress;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['excute_pay' , 'pay_sucess' , 'pay_error']]);
    }

    //  create order
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'address_id' => 'required',
            'count' => 'required',
            'payment_method' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة' , null);
            return response()->json($response , 406);
        }

        $product = Product::find($request->product_id);

        if(!$product){
            $response = APIHelpers::createApiResponse(true , 406 ,  'منتج غير صالح' , null);
            return response()->json($response , 406);            
        }

        if($product['deleted'] == 1){
            $response = APIHelpers::createApiResponse(true , 406 ,  'لقد تم مسح هذا المنتج' , null);
            return response()->json($response , 406);
        }

        if($product['remaining_quantity'] < $request->count){
            $response = APIHelpers::createApiResponse(true , 406 ,  'عدد المنتج المتبقي اقل من العدد المطلوب' , null);
            return response()->json($response , 406);
        }

        if($product['competition_over'] == 1){
            $response = APIHelpers::createApiResponse(true , 406 ,  'لقد انتهت المسابقه' , null);
            return response()->json($response , 406);
        }

        

        // get delivery cost
        $delivery_cost = Setting::select('delivery_cost')->find(1);
        $delivery_cost = $delivery_cost['delivery_cost'];
        
        // unit cost
        $unit_cost = $product['price'];
        
        // total cost
        $sub_total_cost = $unit_cost * $request->count;
        $total_cost =  $sub_total_cost + $delivery_cost;

        if($request->payment_method == 2){

    		$product->remaining_quantity = $product->remaining_quantity - $request->count;
        	$product->save();
            // generate order number
            $time = time();
            $rand = rand(1,9);
            $order_number = $time.$rand;
    
    
            // insert order
            $order = new Order();
            $order->order_number = $order_number;
            $order->delivery_cost = $delivery_cost;
            $order->unit_cost = $unit_cost;
            $order->count = $request->count;
            $order->total_cost = $total_cost;
            $order->payment_method = $request->payment_method;
            $order->address_id = $request->address_id;
            $order->product_id = $request->product_id;
            $order->user_id = auth()->user()->id;
            $order->save();
            
            // generate coupons 
            $coupons = [];
            for($i = 0; $i < $request->count ; $i++){
                $time_coupon = substr(time(),6);
                $coupon_number = $time_coupon.rand(1000,9000).rand(1,9);
                $product_coupon = new ProductCoupon();
                $product_coupon->coupon_number	= $coupon_number;
                $product_coupon->product_id	= $request->product_id;
                $product_coupon->order_id	= $order->id;
                $product_coupon->user_id	= auth()->user()->id;
                $product_coupon->save();            
            }
            $data = $order;
            $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
            return response()->json($response , 200); 
        }else{

            $user = auth()->user();

            $root_url = $request->root();

            $path='https://api.myfatoorah.com/v2/SendPayment';
            $token="bearer M075PylU9VQ6eBC7O7oAQDDBJ50UYJrBmvV_7X6_P72kzb0fhbqreu9SYisngJ053Sruyhatzrrazak6MNkrxm_v90LeljmhnrbPDN8XOcSIJINfkSUHrfhxG-bnv6qF_LK46LzfKpOQKvWNO4ghvokHPuQfpIRYjOdZ5fOnXC_SBNMCEfXAzC7ct47QvtOgZjK8lT8YB_TdPCbb3A0dDHPwx873T2X3RAe20iOpDjNZOatNRwwL0qE8USZTCp_OvB9j2QycvgNpKiDeAPtkhk7pzriIT3Q6eXj-k47W5EZMLOfwpiVxpCBlL7mk9slKVUiUbGtorPKOPp_zW1sxMUcot21NwBYrKb6bhRbvJFBoVZB_sfmwLNr2Cr1MIxNNm2gbOGkAtNciAMdZpZYZVLUJoKNCaxHfafzDRuZhq_oQrAFZzUCG0bk4RXBX2__OChZCHdzqtYmbFiAOfdTE1sfwkCf2t-8ykpBNrZIcJexJ2Tojt1EFyOqvSRzWfOiywFtxxHNiALGLU-qot8f8WTrPeQ_tFRORGOeAGqn1OYtqFIRjEALRDevDsHohxpxnOw61ykmfPhh5y0QLMoI0Z9uFGwWWHdQxqpwFyyHYgQ5GPwCgqn3VFlogkbuLGudJlYzdPZ92A4YP-MNbZy5e6LQuyodfvdEgwGbM9baNN9FqfXga";

            $headers = array(
                'Authorization:' .$token,
                'Content-Type:application/json'
            );
            $price = $total_cost;
            $call_back_url = $root_url."/api/excute_pay?user_id=".$user->id."&product_id=".$request->product_id."&count=".$request->count."&address_id=".$request->address_id;
            $error_url = $root_url."/api/pay/error";
            $fields =array(
                "CustomerName" => $user->name,
                "NotificationOption" => "LNK",
                "InvoiceValue" => $price,
                "CallBackUrl" => $call_back_url,
                "ErrorUrl" => $error_url,
                "Language" => "AR",
                "CustomerEmail" => $user->email
            );  

            $payload =json_encode($fields);
            $curl_session =curl_init();
            curl_setopt($curl_session,CURLOPT_URL, $path);
            curl_setopt($curl_session,CURLOPT_POST, true);
            curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
            curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
            $result=curl_exec($curl_session);
			//dd($result);
            curl_close($curl_session);
            $result = json_decode($result);
			
            $data = (object)["url" => $result->Data->InvoiceURL];
            
            $response = APIHelpers::createApiResponse(false , 200 ,  '' , $data );
            return response()->json($response , 200); 




        }    

 

    }


    public function excute_pay(Request $request){
        $product = Product::find($request->product_id);
        $product->remaining_quantity = $product->remaining_quantity - $request->count;
        $product->save();

        // get delivery cost
        $delivery_cost = Setting::select('delivery_cost')->find(1);
        $delivery_cost = $delivery_cost['delivery_cost'];
        
        // unit cost
        $unit_cost = $product['price'];
        
        // total cost
        $sub_total_cost = $unit_cost * $request->count;
        $total_cost =  $sub_total_cost + $delivery_cost;

        // generate order number
        $time = time();
        $rand = rand(1,9);
        $order_number = $time.$rand;


        // insert order
        $order = new Order();
        $order->order_number = $order_number;
        $order->delivery_cost = $delivery_cost;
        $order->unit_cost = $unit_cost;
        $order->count = $request->count;
        $order->total_cost = $total_cost;
        $order->payment_method = 1;
        $order->address_id = $request->address_id;
        $order->product_id = $request->product_id;
        $order->user_id = $request->user_id;
        $order->save();
        
        // generate coupons 
        $coupons = [];
        for($i = 0; $i < $request->count ; $i++){
            $time_coupon = substr(time(),6);
            $coupon_number = $time_coupon.rand(1000,9000).rand(1,9);
            $product_coupon = new ProductCoupon();
            $product_coupon->coupon_number	= $coupon_number;
            $product_coupon->product_id	= $request->product_id;
            $product_coupon->order_id	= $order->id;
            $product_coupon->user_id	= $request->user_id;
            $product_coupon->save();            
        }

        return redirect('api/pay/success'); 
    }

    public function pay_sucess(){
        return "Please wait ...";
    }

    public function pay_error(){
        return "Please wait ...";
    }



    // get orders
    public function GetOrders(){
        $user_id = auth()->user()->id;
        $orders = Order::where('user_id' , $user_id)->orderBy('id' , 'desc')->get();
        $data = [];
        for($i =0 ; $i < count($orders); $i++){
            $product = Product::find($orders[$i]['product_id']);
            $data[$i]['id'] =  $orders[$i]['id'];
            $data[$i]['title'] =  $product->title;
			
            $data[$i]['date'] =  date_format($orders[$i]['created_at'] , 'd M Y');
			
            $data[$i]['count'] =  $orders[$i]['count'];
            $data[$i]['total_cost'] =  $orders[$i]['total_cost'];
            $product_image = ProductImage::where('product_id' , $product->id)->select('image')->first();
            if($product_image){
                $data[$i]['image'] = $product_image['image'];
            }else{
                $data[$i]['image'] = null;
            }

        }
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);  
    }

    // get order details
    public function details(Request $request){
        $order_id = $request->id;
        $order = Order::find($order_id);
        $data['id'] = $order['id'];
        $data['order_number'] = $order['order_number'];
        $data['delivery_cost'] = $order['delivery_cost'];
        $data['total_cost'] = $order['total_cost'];
        $data['date'] = date_format($order['created_at'] , 'd M Y');
        $data['payment_method'] = $order['payment_method'];

        // coupons count
        $data['coupons_count'] = ProductCoupon::where('order_id' , $order_id)->count();
        

        // get address
        $data['address'] = UserAddress::select('government' , 'sector' , 'gadah' , 'street'  , 'building' , 'floor' , 'flat' , 'extra_details')->find($order['address_id']);

        // get product
        $product = Product::find($order['product_id']);
        $data['product']['id'] = $product->id;
        $data['product']['title'] = $product->title;
        $data['product']['price'] = $order['unit_cost'];
        $data['product']['count'] = $order['count'];
        $image =  ProductImage::select('image')->where('product_id' , $product->id)->first(); 
        $data['product']['image'] = $image['image'];

        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200); 
    }

    // get order coupons
    public function coupons(Request $request){
        $order_id = $request->id;
        $data['coupons'] = ProductCoupon::where('order_id' , $order_id)->select('id' , 'coupon_number' , 'winner', 'product_id' , 'created_at as date')->get();
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