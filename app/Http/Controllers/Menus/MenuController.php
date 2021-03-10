<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Menu\Menu;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class MenuController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:menu-list|menu-create|menu-edit|menu-delete', ['only' => ['index','store']]);
         $this->middleware('permission:menu-create', ['only' => ['create','store']]);
         $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
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
            'title'=>'menu access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu setting submenu konfigurasi menu.',
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
            $data=Menu::orderBy('id','DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('menu-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('menus.show', Crypt::encrypt($row->id)).'");><i class="dw dw-eye"></i> View</button>':'';
                $btnEdit=(Gate::allows('menu-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('menus.edit', Crypt::encrypt($row->id)).'");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('menu-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.route('menus.destroy', Crypt::encrypt($row->id)).'");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
        return view('page.menus.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu=Menu::all();
        return view('page.menus.create',compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|string',
            'type' => 'required|string',
            'masters_id' => 'required|numeric|min:0',
            'authorization' => 'required|string',
            'list' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $input=$request->all();
            if ($input['type']=='main-menu') {
                $permission=[
                    'name'=>$input['authorization'],
                    'guard_name'=>'web'
                ];
            }else{
                $permission=[
                    [
                        'name'=>$input['authorization'],
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>str_replace("list","create",$input['authorization']),
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>str_replace("list","edit",$input['authorization']),
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>str_replace("list","delete",$input['authorization']),
                        'guard_name'=>'web'
                    ],
                ];
            }
            $method=($input['type']=='main-menu')?'create':'insert';
            $insert=Permission::$method($permission);
            // dd($permission);
            $insert=Menu::create([
                'name'=>$input['name'],
                'type'=>$input['type'],
                'masters_id'=>$input['masters_id'],
                'icon'=>$input['icon'],
                'link'=>$input['link'],
                'authorization'=>$input['authorization'],
                'list'=>$input['list']
            ]);
            if (!$insert) {
                $state='badreq';
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
                $user = User::find(Auth::user()->id);
                $user->log_timelines()->create([
                    'name'=>Auth::user()->name,
                    'title'=>'menu store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data menu dengan nama menu : '.$input['name'].' pada menu setting submenu konfigurasi menu.',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $findmenu=Menu::find(Crypt::decrypt($id));
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
            'title'=>'menu show',
            'event'=>'show',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah melihat data menu dengan nama menu : '.$findmenu->name.' pada menu setting submenu konfigurasi menu.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $datamenu=Menu::get();
        return view('page.menus.show',compact('findmenu','datamenu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $findmenu=Menu::find(Crypt::decrypt($id));
        $datamenu=Menu::get();
        return view('page.menus.edit',compact('findmenu','datamenu'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|string',
            'type' => 'required|string',
            'masters_id' => 'required|numeric|min:0',
            'authorization' => 'required|string',
            'list' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $input=$request->all();
            $exp=explode("-",$input['authorization']);
            if ($input['type']=='main-menu') {
                $permission=[
                    'name'=>$exp[0].'-list',
                    'guard_name'=>'web'
                ];
            }else{
                $permission=[
                    [
                        'name'=>$input['authorization'],
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>$exp[0].'-create',
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>$exp[0].'-edit',
                        'guard_name'=>'web'
                    ],
                    [
                        'name'=>$exp[0].'-delete',
                        'guard_name'=>'web'
                    ],
                ];
            }
            $update=Permission::where('name', 'like', '%' . $exp[0] . '%')->update($permission);
            $update=Menu::find(Crypt::decrypt($id));
            
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
                'title'=>'menu update',
                'event'=>'update',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data menu dengan nama menu : '.$update->name.' menjadi '.$input['name'].' pada menu setting submenu konfigurasi menu.',
                'ip_address'=>$request->ip(),
                'platform'=>Browser::platformName(),
                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                'device'=>$device,
                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                'browser_engine'=>Browser::browserEngine(),
                'agent'=>Browser::userAgent()
            ]);
            $update->update([
                'name'=>$input['name'],
                'type'=>$input['type'],
                'masters_id'=>$input['masters_id'],
                'icon'=>$input['icon'],
                'link'=>$input['link'],
                'authorization'=>$input['authorization'],
                'list'=>$input['list']
            ]);
            if (!$update) {
                $state='error';
                $msg='Request unsuccessfuly !';
            }else{
                $state='success';
                $msg='Request successfuly !';
            }
            return json_encode(['status'=>$state,'msg'=>$msg]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find=Menu::find(Crypt::decrypt($id));
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
            'title'=>'menu delete',
            'event'=>'delete',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus data menu dengan nama menu : '.$find->name.' pada menu setting submenu konfigurasi menu.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $exp=explode("-",$find->authorization);
        if ($find->type=='main-menu') {
            $permission=[
                            'name'=>$exp[0].'-list',
                            'guard_name'=>'web'
                        ];
            $permission=Permission::where($permission)->delete();
        }else{
            $permission=[
                            [
                                'name'=>$exp[0].'-list',
                                'guard_name'=>'web'
                            ],
                            [
                                'name'=>$exp[0].'-create',
                                'guard_name'=>'web'
                            ],
                            [
                                'name'=>$exp[0].'-edit',
                                'guard_name'=>'web'
                            ],
                            [
                                'name'=>$exp[0].'-delete',
                                'guard_name'=>'web'
                            ],
                        ];
            foreach ($permission as $key => $value) {
                $permission=Permission::where(['name'=>$value['name'],'guard_name'=>$value['guard_name']])->delete();
            }
        }
        
        $delete=Menu::find(Crypt::decrypt($id))->delete();
        if (!$delete && !$permission) {
            $state='error';
            $msg='Request unsuccessfuly !';
        }else{
            $state='success';
            $msg='Request successfuly !';
        }
        return json_encode(['status'=>$state,'msg'=>$msg]);
    }
}
