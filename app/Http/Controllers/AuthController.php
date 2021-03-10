<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu\Menu;
use App\User;
use App\Models\LogTimelines\LogTimelinesModels;
use App\Models\Documentations\DocumentationsModels;
use Validator;
use Auth;
use Browser;

class AuthController extends Controller
{
    public function profile(Request $request)
    {
        $getMenuMain=Menu::where('type','main-menu')->get();
        $getMenuSub=Menu::where('type','sub-menu')->get();
        if (Browser::isDesktop() == true) {
            $device = 'Computer/Laptop/Notebook';
        }elseif (Browser::isTablet() == true) {
            $device = 'Tablet Device';
        }else{
            $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
        }

        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
            'name'=>Auth::user()->name,
            'title'=>'profile access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data profile akun.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('profile',compact('getMenuMain','getMenuSub'));
    }
    public function profile_post(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'gender' => 'required|string',
            'whatsup_number' => 'required|numeric|min:11',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $input=$request->all();
            if (isset($_post['agree_wa_notification'])) {
                $agree=($input['agree_wa_notification']=='on') ? 'y' : 'n';
            }else{
                $agree='n';
            }
            $update=User::find($id)->update([
                'name' => $input['name'],
                'email' => $input['email'],
                'gender' => $input['gender'],
                'agree_wa_notification' => $agree,
                'whatsup_number' => $input['whatsup_number'],
            ]);
            if (!$update) {
                $state='error';
                $msg='Request unsuccessfuly !';
            }else{
                $state='success';
                $msg='Request successfuly !';
                
                if (Browser::isDesktop() == true) {
                    $device = 'Computer/Laptop/Notebook';
                }elseif (Browser::isTablet() == true) {
                    $device = 'Tablet Device';
                }else{
                    $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
                }

                $user = User::find($id);
                $user->log_timelines()->create([
                    'name'=>Auth::user()->name,
                    'title'=>'profile update',
                    'event'=>'update',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah melakukan pembaharuan akun.',
                    'ip_address'=>$request->ip(),
                    'platform'=>Browser::platformName(),
                    'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                    'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                    'device'=>$device,
                    'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                    'browser_engine'=>Browser::browserEngine(),
                    'agent'=>Browser::userAgent()
                ]);
            }
            return json_encode(['status'=>$state,'msg'=>$msg]);
        }
    }
    public function help()
    {
        $getMenuMain=Menu::where('type','main-menu')->get();
        $getMenuSub=Menu::where('type','sub-menu')->get();
        $documentation=DocumentationsModels::orderBy('id', 'DESC')->get();
        return view('help',compact('getMenuMain','getMenuSub','documentation'));
    }
    public function documentation($authorization)
    {
        $getMenuMain=Menu::where('type','main-menu')->get();
        $getMenuSub=Menu::where('type','sub-menu')->get();
        $documentation=DocumentationsModels::where('authorization', $authorization)->first();
        $doc=DocumentationsModels::orderBy('id', 'DESC')->get();
        return view('documentation',compact('getMenuMain','getMenuSub','documentation','doc'));
    }
    public function timeline(Request $request)
    {
        $timeline=LogTimelinesModels::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->simplePaginate(3);
        $q='profile';
        return view('tableTask', compact('timeline','q'));
    }
}
