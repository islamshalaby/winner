<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\Validator;
use App\Favorite;
use App\Product;
use App\ProductImage;


class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    // get all my favorites
    public function get(){
        $user_id = auth()->user()->id;
        $favorites = Favorite::where('user_id' , $user_id)->select('product_id')->get();
        $products = [];
        for($i = 0; $i < count($favorites); $i++){
            $products[$i] = Product::select('id' , 'title' , 'description' , 'price')->find($favorites[$i]['product_id']);
            $image = ProductImage::select('image')->where('product_id' , $favorites[$i]['product_id'])->first();
            $products[$i]['image'] = $image['image'];
        }
        $data = $products;
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);  
    }

    // add to favorite
    public function add(Request $request){
        $product_id = $request->id;
        $user_id = auth()->user()->id;
        $favorite = Favorite::where('user_id' , $user_id)->where('product_id' , $product_id)->first();
        if($favorite){
            $response = APIHelpers::createApiResponse(true , 406 ,  'هذا المنتج مضاف للمفضله بالفعل' , null);
            return response()->json($response , 406);
        }

        $new_favorite = new Favorite;
        $new_favorite->user_id = $user_id;
        $new_favorite->product_id = $product_id;
        $new_favorite->save();
        
        $response = APIHelpers::createApiResponse(false , 200 , ''  , []);
        return response()->json($response , 200);
    }

    // remove from favorite
    public function remove(Request $request){
        $product_id = $request->id;
        $user_id = auth()->user()->id;
        $favorite = Favorite::where('user_id' , $user_id)->where('product_id' , $product_id)->first();
        if(!$favorite){
            $response = APIHelpers::createApiResponse(true , 406 ,  'هذا النتج غير موجود بالمفضله' , null);
            return response()->json($response , 406);
        }
        $favorite->delete();
        $response = APIHelpers::createApiResponse(false , 200 , ''  , []);
        return response()->json($response , 200);
    }

}