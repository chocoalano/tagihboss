<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Auth;
use App\User;
use Spatie\Permission\Models\Role;
use Hash;
use Crypt;
use Redirect;
use Browser;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        $ip=$request->ip();
        $input=$request->all();
        if ($input['param']=='auth') {
            $loginType = filter_var($input['post']['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
            $login = [
                $loginType => $input['post']['email'],
                'password' => $input['post']['password']
            ];
            if (auth()->attempt($login)) {
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
                    'title'=>'authenticated',
                    'event'=>'login',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah berhasil login non sync micro bpr online.',
                    'ip_address'=>$ip,
                    'platform'=>Browser::platformName(),
                    'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                    'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                    'device'=>$device,
                    'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                    'browser_engine'=>Browser::browserEngine(),
                    'agent'=>Browser::userAgent()
                ]);
                return json_encode(['status'=>'success','msg' => 'Login Success','url'=>url('/home')]);
            }
            return json_encode(['status'=>'error','msg' => 'Email/Username/Password salah!','url'=>url('/login')]);
        }else{
            $client=new Client();
            try {
                $request = $client->request('POST', 'http://103.31.232.146/API_ABSENSI/login',['form_params' => [
                    'user' => $input['post']['email'],
                    'password' => $input['post']['password']
                ]]);
                $status = $request->getStatusCode();
                if($status == 200){
                    $api=$request->getBody()->getContents();
                    $data=json_decode($api,true);
                    $user_id=$data['payload']['id'];
                    $email=$data['payload']['email'];
                    $username=$data['payload']['usename'];
                    $lastValue = User::orderBy('id', 'desc')->first();
                    $nik=$data['payload']['nik'];
                    $nama=$data['payload']['nama'];
                    $divisi=$data['payload']['divisi_id'];
                    $jabatan=$data['payload']['jabatan'];
                    $email=$data['payload']['email'];
                    $id_lokasi=$data['user']['id_lokasi'];
                    $jam_masuk=$data['user']['jam_masuk'];
                    $jam_keluar=$data['user']['jam_keluar'];
                    $initial=$data['user']['initial'];

                    $credentials = [
                        'name'=>$username,
                        'email'=>$email
                    ];

                    $cek=User::where($credentials)->count();
                    if ($cek < 1) {
                        $user=user::create([
                          'user_id'=>$user_id,
                          'location'=>$id_lokasi,
                          'nik'=>$nik,
                          'name'=>$username,
                          'email'=>$email,
                          'password'=>Hash::make($input['post']['password'])
                        ]);
                        $user->assignRole('Collections');
                        $login = [
                            'name' => $input['post']['email'],
                            'password' => $input['post']['password']
                        ];
                        if (auth()->attempt($login)) {
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
                                'title'=>'authenticated',
                                'event'=>'login',
                                'description'=>'User dengan akun username: '.Auth::user()->name.' telah berhasil login with sync micro bpr online.',
                                'ip_address'=>$ip,
                                'platform'=>Browser::platformName(),
                                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                                'device'=>$device,
                                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                                'browser_engine'=>Browser::browserEngine(),
                                'agent'=>Browser::userAgent()
                            ]);
                            return json_encode(['status'=>'success','msg' => 'Login success','url'=>url('/home')]);
                        }
                    }else{
                        $user=user::where($credentials)->update([
                            'user_id'=>$user_id,
                            'location'=>$id_lokasi,
                            'nik'=>$nik,
                            'name'=>$username,
                            'email'=>$email,
                            'password'=>Hash::make($input['post']['password'])
                        ]);
                        $login = [
                            'name' => $input['post']['email'],
                            'password' => $input['post']['password']
                        ];
                        if (auth()->attempt($login)) {
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
                                'title'=>'authenticated',
                                'event'=>'login',
                                'description'=>'User dengan akun username: '.Auth::user()->name.' telah berhasil login with sync micro bpr online.',
                                'ip_address'=>$ip,
                                'platform'=>Browser::platformName(),
                                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                                'device'=>$device,
                                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                                'browser_engine'=>Browser::browserEngine(),
                                'agent'=>Browser::userAgent()
                            ]);
                            return json_encode(['status'=>'success','msg' => 'Login success','url'=>url('/home')]);
                        }
                    }
                }else{
                    throw new \Exception('Failed');
                }
            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                return $e->getResponse()->getStatusCode();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return $e->getResponse()->getStatusCode();
            }
            $status = $request->getStatusCode();
            if ($status==200) {
                $login = [
                    'name' => $input['post']['email'],
                    'password' => $input['post']['password']
                ];
                if (auth()->attempt($login)) {
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
                        'title'=>'authenticated',
                        'event'=>'login',
                        'description'=>'User dengan akun username: '.Auth::user()->name.' telah berhasil login with sync micro bpr online.',
                        'ip_address'=>$ip,
                        'platform'=>Browser::platformName(),
                        'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                        'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                        'device'=>$device,
                        'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                        'browser_engine'=>Browser::browserEngine(),
                        'agent'=>Browser::userAgent()
                    ]);
                    return json_encode(['status'=>'success','msg' => 'Login success','url'=>url('/home')]);
                }
            }else{
                return json_encode(['status'=>'error','msg' => 'Email/Username/Password salah!','url'=>url('/login')]);
            }
        }
    }
    public function logout(Request $request) {
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
            'title'=>'authenticated',
            'event'=>'logout',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah berhasil logout.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $user->update(['last_active'=>date('Y-m-d H:i:s')]);
        Auth::logout();
        return redirect('/login');
    }
}
