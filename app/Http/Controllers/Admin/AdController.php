<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Ad;
use App\Product;

class AdController extends AdminController{
    
    // type get 
    public function AddGet(){
        $data['products'] = Product::select('id' , 'title')->get();
        return view('admin.ad_form' , ['data' => $data]);
    }

    // type post
    public function AddPost(Request $request){
        if(!$request->type){
            return back()->with('status' , 'Please select an ad type');
        }
        if($request->type == 'inside' && !$request->contentId ){
            return back()->with('status' , 'Please select product');
        }
        if($request->type == 'outside' && !$request->contentLink ){
            return back()->with('status' , 'Please put url');
        }

        if($request->type == 'inside' && $request->contentId ){
            $content = $request->contentId;
        }
        if($request->type == 'outside' && $request->contentLink ){
            $content = $request->contentLink;
        }

        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];    
        $image_new_name = $image_id.'.'.$image_format;
        $ad = new Ad();
        $ad->image = $image_new_name;
        $ad->content = $content;
        $ad->type = $request->type;
        $ad->save();
        return redirect('admin-panel/ads/show'); 
    }


    // get all ads
    public function show(Request $request){
        $data['ads'] = Ad::orderBy('id' , 'desc')->get();
        return view('admin.ads' , ['data' => $data]);
    }

    // get edit page
    public function EditGet(Request $request){
        $data['ad'] = Ad::find($request->id);
        $data['products'] = Product::select('id' , 'title')->get();
        return view('admin.ad_edit' , ['data' => $data]);
    }

    // post edit ad
    public function EditPost(Request $request){
        if(!$request->type){
            return back()->with('status' , 'Please select an ad type');
        }
        if($request->type == 'inside' && !$request->contentId ){
            return back()->with('status' , 'Please select product');
        }
        if($request->type == 'outside' && !$request->contentLink ){
            return back()->with('status' , 'Please put url');
        }

        if($request->type == 'inside' && $request->contentId ){
            $content = $request->contentId;
        }
        if($request->type == 'outside' && $request->contentLink ){
            $content = $request->contentLink;
        }
        
        $ad = Ad::find($request->id);
        if($request->file('image')){
            $image = $ad->image;
            $publicId = substr($image, 0 ,strrpos($image, "."));    
            Cloudder::delete($publicId);
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;
            $ad->image = $image_new_name;
        }
        $ad->content = $content;
        $ad->type = $request->type;
        $ad->save();
        return redirect('admin-panel/ads/show');
    }

    public function details(Request $request){
        $data['ad'] = Ad::find($request->id);
        if($data['ad']['type'] == 'inside' ){
            $product = Product::select('id' , 'title')->find($data['ad']['content']);
            $data['ad']['product'] = $product;
        }
        return view('admin.ad_details' , ['data' => $data]);
    }

    public function delete(Request $request){
        $ad = Ad::find($request->id);
        if($ad){
            $ad->delete();
        }
        return redirect('admin-panel/ads/show');
    }
}