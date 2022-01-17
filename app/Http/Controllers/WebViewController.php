<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class WebViewController extends Controller
{
    // get about
    public function getabout(Request $request){
        $data['lang'] = $request->lang;
        $setting = Setting::find(1);
        if($data['lang'] == 'en' ){
            $data['text'] = $setting['aboutapp_en'];
        }else{
            $data['text'] = $setting['aboutapp_ar'];
        }
        return view('webview.about' , $data);
    }

    // get terms and conditions
    public function gettermsandconditions(Request $request){
        $data['lang'] = $request->lang;
        $setting = Setting::find(1);
        if($data['lang'] == 'en' ){
            $data['title'] = 'Terms and Conditions';
            $data['text'] = $setting['termsandconditions_en'];
        }else{
            $data['title'] = 'الشروط و الأحكام';
            $data['text'] = $setting['termsandconditions_ar'];
        }
        return view('webview.termsandconditions' , $data);
    }
	
	    // get return policy
    public function getreturnpolicy(Request $request){
        $data['lang'] = $request->lang;
        $setting = Setting::find(1);
         $data['text'] = $setting['return_policy'];
        return view('webview.return_policy' , $data);
    }
	
	public function competition_terms(Request $request){
	        $data['lang'] = $request->lang;
        $setting = Setting::find(1);
         $data['text'] = $setting['competition_terms'];
        return view('webview.competition_terms' , $data);
	}
	
	public function getcert(){
	 return "64795ED5A2776B01E86CF14E205C5FF1773453835DEB2673E87FD834E3C58B94
comodoca.com
f6aab4c723034dc";
	}

	
}
