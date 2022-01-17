<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\UserAddress;
use App\UserNotification;
use App\Notification;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['resetforgettenpassword' , 'checkphoneexistance']]);
    }

    public function getprofile(Request $request){
        $user = auth()->user();
        $returned_user['user_name'] = $user['name'];
        $returned_user['phone'] = $user['phone'];
        $returned_user['email'] = $user['email'];
        $response = APIHelpers::createApiResponse(false , 200 , '' , $returned_user);
        return response()->json($response , 200);  
    }

    public function updateprofile(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            "email" => 'required',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة' , null);
            return response()->json($response , 406);
        }

        $currentuser = auth()->user();
        $user_by_phone = User::where('phone' , '!=' , $currentuser->phone )->where('phone', $request->phone)->first();
        if($user_by_phone){
            $response = APIHelpers::createApiResponse(true , 409 ,  'رقم الهاتف موجود من قبل' , null);
            return response()->json($response , 409);
        }

        $user_by_email = User::where('email' , '!=' ,$currentuser->email)->where('email' , $request->email)->first();
        if($user_by_email){
            $response = APIHelpers::createApiResponse(true , 409 ,  'البريد الإلكتروني موجود من قبل' , null);
            return response()->json($response , 409); 
        }

        User::where('id' , $currentuser->id)->update([
            'name' => $request->name , 
            'phone' => $request->phone , 
            'email' => $request->email  ]);

        $newuser = User::find($currentuser->id);
        $response = APIHelpers::createApiResponse(false , 200  , '' , $newuser);
        return response()->json($response , 200);    
    }


    public function resetpassword(Request $request){
        $validator = Validator::make($request->all() , [
            'password' => 'required',
            "old_password" => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة' , null);
            return response()->json($response , 406);
        }

        $user = auth()->user();
        if(!Hash::check($request->old_password, $user->password)){
			$response = APIHelpers::createApiResponse(true , 406 ,  'كلمه المرور السابقه خطأ' , null );
            return response()->json($response , 406);
		}
		if($request->old_password == $request->password){
			$response = APIHelpers::createApiResponse(true , 406  , 'لا يمكنك تعيين نفس كلمه المرور السابقه' , null);
            return response()->json($response , 406);
		}
        User::where('id' , $user->id)->update(['password' => Hash::make($request->password)]);
        $newuser = User::find($user->id);
        $response = APIHelpers::createApiResponse(false , 200 ,  '' , $newuser);
        return response()->json($response , 200);
    }

    public function resetforgettenpassword(Request $request){
        $validator = Validator::make($request->all() , [
            'password' => 'required',
            'phone' => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة' , null);
            return response()->json($response , 406);
        }

        $user = User::where('phone', $request->phone)->first();
        if(! $user){
            $response = APIHelpers::createApiResponse(true , 403 ,  'رقم الهاتف غير موجود' , null);
            return response()->json($response , 403);
        }

        User::where('phone' , $user->phone)->update(['password' => Hash::make($request->password)]);
        $newuser = User::where('phone' , $user->phone)->first();

        $token = auth()->login($newuser);
        $newuser->token = $this->respondWithToken($token);

        $response = APIHelpers::createApiResponse(false , 200 , ''  , $newuser);
        return response()->json($response , 200);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 432000
        ];
    }

    // check if phone exists before or not
    public function checkphoneexistance(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => 'required'
        ]);

        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'حقل الهاتف اجباري' , null);
            return response()->json($response , 406);
        }
        
        $user = User::where('phone' , $request->phone)->first();
        if($user){
            $response = APIHelpers::createApiResponse(false , 200 ,  '' , $user);
            return response()->json($response , 200);
        }

        $response = APIHelpers::createApiResponse(true , 403 ,  'الهاتف غير موجود من قبل' , null);
        return response()->json($response , 403);

    }

    // get notifications
    public function notifications(){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك من الادمن' , null);
            return response()->json($response , 406);
        }

        $user_id = $user->id;
        $notifications_ids = UserNotification::where('user_id' , $user_id)->orderBy('id' , 'desc')->select('notification_id')->get();
        $notifications = [];
        for($i = 0; $i < count($notifications_ids); $i++){
            $notifications[$i] = Notification::select('id','title' , 'body' ,'image' , 'created_at')->find($notifications_ids[$i]['notification_id']);
        }
        $data['notifications'] = $notifications;
        $response = APIHelpers::createApiResponse(false , 200 ,  '' , $data['notifications']);
        return response()->json($response , 200);  
    }    

    // get address
    public function getaddress(){
        $user_id = auth()->user()->id;
        $data = UserAddress::where('user_id' , $user_id)->where('deleted' , 0)->select('id' , 'address_name' , 'phone' , 'government' , 'sector' , 'gadah')->get();
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);       
    }


    // add address
    public function addaddress(Request $request){
        $validator = Validator::make($request->all(), [
            'address_name' => 'required',
            // 'latitude' => 'required',
            // "longitude" => 'required',
            "phone" => "required",
            "government" => "required",
            "sector" => "required",
            // "street" => "required",
            "gadah" => "required",
            "building" => "required",
            "floor" => "required",
            "flat" => "required",
            // "extra_details" => "required"
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة' , null);
            return response()->json($response , 406);
        }

        $user_id = auth()->user()->id;

        $user_address = new UserAddress();
        $user_address->address_name = $request->address_name;
        if($request->latitude){
            $user_address->latitude = $request->latitude;
        }
        if($request->longitude){
            $user_address->longitude = $request->longitude;
        }
        $user_address->phone = $request->phone;
        $user_address->government = $request->government;
        $user_address->sector = $request->sector;
        if($request->street){
            $user_address->street = $request->street;
        }
        $user_address->gadah = $request->gadah;
        $user_address->building = $request->building;
        $user_address->floor = $request->floor;
        $user_address->flat = $request->flat;
        $user_address->extra_details = $request->extra_details;
        $user_address->user_id = $user_id;
        $user_address->save();
        $data = $user_address;
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);
    }

    // address details
    public function addressDetails(Request $request){
        $user_id = auth()->user()->id;
        $address_id = $request->id;
        $address = UserAddress::find($address_id);
        if($user_id != $address['user_id']){
            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك الصلاحيه لعرض تفاصيل هذا العنوان' , null);
            return response()->json($response , 406);
        }

        $data = $address;
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);        
    }

    // remove address
    public function deleteaddress(Request $request){
        $user_id = auth()->user()->id;
        $address_id = $request->id;
        $address = UserAddress::find($address_id);
        if($user_id != $address['user_id']){
            $response = APIHelpers::createApiResponse(true , 406 ,  'ليس لديك الصلاحيه لحذف هذا العنوان' , null);
            return response()->json($response , 406);
        }

        $address->deleted = 1;
        $address->save();

        $response = APIHelpers::createApiResponse(false , 200 , ''  , []);
        return response()->json($response , 200);
    }
}
