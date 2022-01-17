<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Product;
use App\ProductImage;
use App\Favorite;


class ProductController extends Controller
{
    // get product details
    public function details(Request $request){
        $product = Product::select('id' , 'price' , 'title' , 'description', 'quantity as total_quantity' , 'remaining_quantity' , 'prize_name' , 'prize_image' )->find($request->id);
        
        // images
        $images = ProductImage::select('image')->where('product_id' , $product['id'])->get();
        $newimages = [];
        for($i = 0; $i < count($images); $i++){
            $newimages[$i] = $images[$i]['image'];
        }
        $product['images'] = $newimages;
        
        // favorite
        if(auth()->user()){
            $favorite = Favorite::where('product_id' , $product['id'])->where('user_id' , auth()->user()->id)->first();
            if($favorite){
                $product['is_favorite'] = true;
            }else{
                $product['is_favorite'] = false;
            }
        }else{
            $product['is_favorite'] = false;
        }

        // type
        $sold = $product['total_quantity'] - $product['remaining_quantity'];
        $percentage = ($sold / $product['total_quantity']) * 100;
        $product['percentage'] =  $percentage;

        if($percentage >= 0 && $percentage <= 50){
            // احدث العروض
            $product['type'] = 1; 
        }
        if($percentage > 50 && $percentage < 100 ){
            // سينتهي قريبا
            $product['type'] = 2;
        }
        
        // if($percentage > 80 && $percentage < 100 ){
        //     // سينتهي قريبا
        //     $products[$i]['type'] = 3;
        // }
        if($percentage == 100){
            // منتهي
            $product['type'] = 3;
        }
        
        $data = $product;
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200); 
    }
} 