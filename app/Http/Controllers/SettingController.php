<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\Validator;
use App\Setting;

class SettingController extends Controller
{
    public function deliverycost(){
        $delivery_cost = Setting::select('delivery_cost')->find(1);
        $data['delivery_cost'] = $delivery_cost['delivery_cost'];
        $response = APIHelpers::createApiResponse(false , 200 , ''  , $data);
        return response()->json($response , 200);  
    }

    public function getappnumber(Request $request){
        $setting = Setting::select('phone')->find(1);
        $response = APIHelpers::createApiResponse(false , 200  , '' , $setting['phone'] );
        return response()->json($response , 200);
    }

    public function getwhatsapp(Request $request){
        $setting = Setting::select('app_phone')->find(1);
        $response = APIHelpers::createApiResponse(false , 200  , '' , $setting['app_phone'] );
        return response()->json($response , 200);
    }


}