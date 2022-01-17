<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Setting;

class AppPagesController extends AdminController{

    // get about app edit page
    public function GetAboutApp(){
        $data['setting'] = Setting::find(1);
        return view('admin.aboutapp' , ['data' => $data]);
    }

    // post about app edit page
    public function PostAboutApp(Request $request){

        if(!$request->aboutapp_ar){
            return redirect('admin-panel/app_pages/aboutapp')->with('status' , 'About App Text in Arabic Required');
        }        
        $setting = Setting::find(1);
        $setting->aboutapp_ar = $request->aboutapp_ar;
        // return $setting;
        $setting->save();        
        return redirect('admin-panel/app_pages/aboutapp');
    }

    // get Terms And Conditions edit page
    public function GetTermsAndConditions(){
        $data['setting'] = Setting::find(1);
        return view('admin.termsandconditions' , ['data' => $data]);
    }

    // get Terms And Conditions edit page
    public function PostTermsAndConditions(Request $request){

        if(!$request->termsandconditions_ar){
            return redirect('admin-panel/app_pages/termsandconditions')->with('status' , 'Terms And Conditions Text in Arabic Required');
        }
        $setting = Setting::find(1);
        $setting->termsandconditions_ar = $request->termsandconditions_ar;
        $setting->save();    
        return redirect('admin-panel/app_pages/termsandconditions');
    }
	
	
	
	
	    // get return policy edit page
    public function GetReturnPolicy(){
        $data['setting'] = Setting::find(1);
        return view('admin.return_policy' , ['data' => $data]);
    }

    // post about app edit page
    public function PostReturnPolicy(Request $request){

        if(!$request->return_policy){
            return redirect('admin-panel/app_pages/return_policy')->with('status' , ' Text  Required');
        }        
        $setting = Setting::find(1);
        $setting->return_policy = $request->return_policy;
        // return $setting;
        $setting->save();        
        return redirect('admin-panel/app_pages/return_policy');
    }
	
		    // get return policy edit page
    public function getcompetition_terms(){
        $data['setting'] = Setting::find(1);
        return view('admin.competition_terms' , ['data' => $data]);
    }

    // post about app edit page
    public function postcompetition_terms(Request $request){

        if(!$request->competition_terms){
            return redirect('admin-panel/app_pages/competition_terms')->with('status' , ' Text  Required');
        }        
        $setting = Setting::find(1);
        $setting->competition_terms = $request->competition_terms;
        // return $setting;
        $setting->save();        
        return redirect('admin-panel/app_pages/competition_terms');
    }
	
	
	
    

}