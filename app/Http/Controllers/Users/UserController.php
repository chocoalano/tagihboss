<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\User;
use App\Models\Menu\Menu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Browser;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
            'title'=>'users access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu setting submenu konfigurasi user.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        if ($request->ajax()) {
            $query = User::select('users.*','roles.name as rolename')
            ->join('model_has_roles','model_has_roles.model_id','=','users.id')
            ->join('roles','roles.id','=','model_has_roles.role_id');
            if (Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Superadmin')) {
                $query->where('roles.name','!=','Superadmin');
            }elseif (!Auth::user()->hasRole('Superadmin') && !Auth::user()->hasRole('Administrator')) {
                $query->where('roles.name',Auth::user()->getRoleNames());
            }
            $data=$query->orderBy('id','DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('role-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('users.show', Crypt::encrypt($row->id)).'");><i class="dw dw-eye"></i> View</button>':'';
                $btnEdit=(Gate::allows('role-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('users.edit', Crypt::encrypt($row->id)).'");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('role-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.route('users.destroy', Crypt::encrypt($row->id)).'");><i class="dw dw-delete-3"></i> Delete</button>':'';
                $btn = '
                <div class="dropdown">
                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    '.$btnView.''.$btnEdit.''.$btnDelete.'
                    </div>
                </div>
                ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('page.users.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['Administrator','Superadmin'])->get();
        return view('page.users.create',compact('roles'));
    }
    public function cekpost(Request $request)
    {
        $input=$request->all();
        $client=new Client();
        try {
            $request = $client->request('POST', 'http://103.31.232.146/API_ABSENSI/login',['form_params' => [
                'user' => $input['user'],
                'password' => $input['password']
            ]]);
            $status = $request->getStatusCode();
            if($status == 200){
                $api=$request->getBody()->getContents();
                $data=json_decode($api,true);

                $email=$data['payload']['email'];
                $username=$data['payload']['usename'];
                $nik=$data['payload']['nik'];
                $id_lokasi=$data['user']['id_lokasi'];
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
            return json_encode([
                'status'=>'success',
                'msg' => 'Login success',
                'username'=>$username,
                'email'=>$email,
                'nik'=>$nik,
                'id_lokasi'=>$id_lokasi,
            ]);
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
                'title'=>'users store',
                'event'=>'store',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data user dengan username : '.$username.' pada menu setting submenu konfigurasi user.',
                'ip_address'=>$request->ip(),
                'platform'=>Browser::platformName(),
                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                'device'=>$device,
                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                'browser_engine'=>Browser::browserEngine(),
                'agent'=>Browser::userAgent()
            ]);
        }else{
            return json_encode(['status'=>'error','msg' => 'Email/Username/Password salah!']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'nik' => 'required|unique:users,nik',
            'password' => 'required',
            'location' => 'required',
            'rolename' => 'required'
        ]);
        $input=$request->all();
        $input['password'] = Hash::make($input['password']);
        $count=User::where(['name'=>$input['name'],'password'=>$input['password']])->count();
        if ($count > 0) {
            $status='error';
            $msg='error duplicated data function';
        }else{
            $user = User::create($input);
            $user->assignRole($request->input('rolename'));
            if (!$user) {
                $status='error';
                $msg='error request not allowed function';
            }else{
                $status='success';
                $msg='Data created successfuly';
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
                    'title'=>'users store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data user dengan username : '.$input['name'].' pada menu setting submenu konfigurasi user.',
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
        }
        return json_encode(['status'=>$status,'msg' => $msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find(Crypt::decrypt($id));
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
            'title'=>'users show',
            'event'=>'show',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah melihat data user dengan username : '.$user->name.' pada menu setting submenu konfigurasi user.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('page.users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(Crypt::decrypt($id));
        $roles = Role::all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('page.users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'nik' => 'required',
            'password' => 'required',
            'location' => 'required',
            'rolename' => 'required'
        ]);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }
        $user = User::find(Crypt::decrypt($id));
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole($input['rolename']);
        if (!$user) {
            $status='error';
            $msg='error request not allowed function';
        }else{
            $status='success';
            $msg='Data updated successfuly';
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
                'title'=>'users update',
                'event'=>'update',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data user dengan username : '.$input['name'].' pada menu setting submenu konfigurasi user.',
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
        return json_encode(['status'=>$status,'msg' => $msg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete=User::find(Crypt::decrypt($id));
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
            'title'=>'users delete',
            'event'=>'delete',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus data user dengan username : '.$delete->name.' pada menu setting submenu konfigurasi user.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $delete->->delete();
        if (!$delete) {
            $status='error';
            $msg='error request not allowed function';
        }else{
            $status='success';
            $msg='Data deleted successfuly';
        }
        return json_encode(['status'=>$status,'msg' => $msg]);
    }
}
