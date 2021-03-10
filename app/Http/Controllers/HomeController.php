<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\userRoles;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\Menu\Menu;
use App\Models\Informations\InfocollModels;
use App\Models\Assigments\ViewTaskAssigment;
use Auth;
use DB;
use Cache;
use Browser;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userOnlineStatus()
    {
        $users = DB::table('users')->get();
    
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id))
                echo "User " . $user->name . " is online.";
            else
                echo "User " . $user->name . " is offline.";
        }
    }
    public function index(Request $request)
    {
        $getMenuMain=Menu::where('type','main-menu')->get();
        $getMenuSub=Menu::where('type','sub-menu')->get();
        $info=InfocollModels::where('state','active')->first();
        $user=User::get();
        if (Browser::isDesktop() == true) {
            $device = 'Computer/Laptop/Notebook';
        }elseif (Browser::isTablet() == true) {
            $device = 'Tablet Device';
        }else{
            $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
        }
        $x = User::find(Auth::user()->id);
        $x->log_timelines()->create([
            'name'=>Auth::user()->name,
            'title'=>'home access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu home.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('home',compact('getMenuMain','getMenuSub','info','user'));
    }

    public function taskAssigment(){
        $task=ViewTaskAssigment::where(['user_id'=>Auth::user()->user_id, 'flag_aktif'=>1])->simplePaginate(3);
        $q='task_assigment';
        return view('tableTask', compact('task','q'));
    }
}