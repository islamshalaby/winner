<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Ad;
use App\Product;
use App\ProductImage;
use App\Favorite;


class HomeController extends Controller
{
    // get ads
    public function getads(){
        $data = Ad::get();
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);
    }

    // get stories
    public function getstories(){
        $data = Product::where('competition_over' , 1)->where('deleted' , 0)->select('id' , 'winner_video as link' , 'prize_image' , 'prize_name' )->orderBy('winner_date' , 'desc')->get();
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);        
    }

    // get home products
    public function getproducts(Request $request){
        $products = Product::where('deleted' , 0)->select('id' ,'title' , 'price' , 'quantity as total_quantity' , 'remaining_quantity' , 'prize_name')->orderBy('id' , 'desc')->get();
        for($i = 0 ; $i < count($products) ; $i++){
            $sold = $products[$i]['total_quantity'] - $products[$i]['remaining_quantity'];
            $percentage = ($sold / $products[$i]['total_quantity']) * 100;
            $products[$i]['percentage'] =  $percentage;
            

            if($percentage >= 0 && $percentage <= 50){
                // احدث العروض
                $products[$i]['type'] = 1; 
            }
            if($percentage > 50 && $percentage < 100 ){
                // سينتهي قريبا
                $products[$i]['type'] = 2;
            }
            // if($percentage > 80 && $percentage < 100 ){
            //     // سينتهي قريبا
            //     $products[$i]['type'] = 3;
            // }
            if($percentage == 100){
                // منتهي
                $products[$i]['type'] = 3;
            }

            $image = ProductImage::where('product_id' , $products[$i]['id'])->first();
            $products[$i]['image'] = $image['image'];

            // check if product favorite to this user
            if(auth()->user()){
                $favorite = Favorite::where('product_id' , $products[$i]['id'])->where('user_id' , auth()->user()->id)->first();
                if($favorite){
                    $products[$i]['is_favorite'] = true; 
                }else{
                    $products[$i]['is_favorite'] = false; 
                }
            }else{
                $products[$i]['is_favorite'] = false; 
            }
                       
        }

        $products = collect($products)->sortBy('type')->toArray();
		
		if($request->type){
			$newproducts = [];
			$j = 0;
			for($i =0 ; $i < count($products); $i++){
				if($products[$i]['type'] == $request->type){
					
					$newproducts[$j] =new \stdClass();;
					$newproducts[$j] = $products[$i];
					$j++;
				}
			}
			$products = $newproducts;
		}
		
        $data = array_values($products);
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);         
    }
    
}