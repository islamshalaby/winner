<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\ProductImage;
use App\ProductCoupon;

class ProductController extends AdminController{

    // add get 
    public function AddGet(){
        return view('admin.product_form');
    }

    // add post
    public function AddPost(Request $request){
        // upload prize image
        $prize_image_name = $request->file('prize_image')->getRealPath();
        Cloudder::upload($prize_image_name, null);
        $prizeimagereturned = Cloudder::getResult();
        $image_id = $prizeimagereturned['public_id'];
        $image_format = $prizeimagereturned['format'];    
        $prize_image_new_name = $image_id.'.'.$image_format;

        // insert product details
        $product = new Product();
        $product->title = $request->title;
        $product->quantity = $request->quantity;
        $product->remaining_quantity = $request->quantity;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->prize_name = $request->prize_name;
        $product->prize_image = $prize_image_new_name;
        $product->save();

        // insert product images
        for($i = 0; $i < count($request->file('image')); $i++){
            $image_name = $request->file('image')[$i]->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;

            $product_image = new ProductImage();
            $product_image->image = $image_new_name;
            $product_image->product_id = $product->id;
            $product_image->save();
        }
        return redirect('/admin-panel/products/show');
    }

    // get all products
    public function show(){
        $data['products'] = Product::where('deleted' , 0)->orderBy('id' , 'desc')->get();
        for($i = 0 ; $i < count($data['products']); $i++ ){
            $image = ProductImage::where('product_id' , $data['products'][$i]['id'])->first();
            if($image){
                $data['products'][$i]['image'] = $image['image'];
            }else{
                $data['products'][$i]['image'] = null;
            }     
        }
        return  view('admin.products' , ['data' => $data]);
    }

    // delete product
    public function delete(Request $request){
        $product_id = $request->id;
        $product = Product::find($product_id);
        // $publicId = substr($product['prize_image'], 0 ,strrpos($product['prize_image'], "."));    
        // Cloudder::delete($publicId);
        // $product_images = ProductImage::where('product_id' , $product->id)->get();
        // for($i = 0 ; $i < count($product_images) ; $i++){
        //     $publicId = substr($product_images[$i]['image'], 0 ,strrpos($product_images[$i]['image'], "."));    
        //     Cloudder::delete($publicId);
        //     $product_images[$i]->delete();    
        // }
        // $product->delete();
        $product->deleted = 1;
        $product->save();
        return redirect('/admin-panel/products/show');
    }

    // delete product image
    public function deleteImage(Request $request){
        $product_image = ProductImage::find($request->id);
        $publicId = substr($product_image['image'] , 0 , strrpos($product_image['image'], "."));    
        Cloudder::delete($publicId);
        $product_image->delete();   
        return redirect()->back();
    }

    // get edit page 
    public function edit(Request $request){
        $data['product'] = Product::find($request->id);
        $data['product']['images'] = ProductImage::where('product_id' , $request->id)->get();
        return view('admin.products_edit' , ['data' => $data]);    
    }

    // get product details 
    public function details(Request $request){
        $data['product'] = Product::find($request->id);
        $data['product']['images'] = ProductImage::where('product_id' , $request->id)->get();
        $data['product']['coupons'] = ProductCoupon::where('product_id' , $request->id)->get();
        return view('admin.product_details' , ['data' => $data]);
    }   

    // post edit
    public function EditPost(Request $request){
        $product_id = $request->id;
        $product = Product::find($product_id);

        if($request->file('image')){
            for($i = 0 ; $i < count($request->file('image')); $i++ ){
                $image_name = $request->file('image')[$i]->getRealPath();
                Cloudder::upload($image_name, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];    
                $image_new_name = $image_id.'.'.$image_format;
                $product_image = new ProductImage();
                $product_image->image = $image_new_name;
                $product_image->product_id = $product->id;
                $product_image->save();
            }
        }

        if($request->file('prize_image')){
            $publicId = substr($product['prize_image'] , 0 , strrpos($product['prize_image'], "."));    
            Cloudder::delete($publicId);    
            $prize_image_name = $request->file('prize_image')->getRealPath();
            Cloudder::upload($prize_image_name, null);
            $prizeimagereturned = Cloudder::getResult();
            $prize_image_id = $prizeimagereturned['public_id'];
            $prize_image_format = $prizeimagereturned['format'];    
            $prize_image_new_name = $prize_image_id.'.'.$prize_image_format;
            $product->prize_image = $prize_image_new_name;
        }



        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->remaining_quantity = $request->remaining_quantity;
        $product->prize_name = $request->prize_name;
        
        $product->description = $request->description;
        $product->save();
        return redirect('/admin-panel/products/show');
    }




}