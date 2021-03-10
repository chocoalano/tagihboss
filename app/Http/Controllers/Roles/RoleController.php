<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu\Menu;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use DB;
use Auth;
use Browser;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
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
            'title'=>'role access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses role setting submenu konfigurasi role.',
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
            $data = Role::orderBy('id','DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('role-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('roles.show', Crypt::encrypt($row->id)).'");><i class="dw dw-eye"></i> View</button>':'';
                $btnEdit=(Gate::allows('role-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('roles.edit', Crypt::encrypt($row->id)).'");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('role-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.route('roles.destroy', Crypt::encrypt($row->id)).'");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
        return view('page.roles.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('page.roles.create');
    }
    public function permissionData(Request $request)
    {
        if ($request->ajax()) {
            $permission = Permission::orderBy('id','DESC')->get();
            $rolePermissions = null;
        return view('page.roles.dataPermission',compact('permission','rolePermissions'));
        }
    }
    public function permissionDataEdit($id)
    {
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
        return view('page.roles.dataPermission',compact('permission','rolePermissions'));
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
            'role' => 'required',
            'permission' => 'required',
        ]);
        $input=$request->all();
        $role = Role::create(['name' => $input['role']]);
        $role->syncPermissions($input['permission']);
        $msg = (!$role) ? 'Role created unsuccessfuly' : 'Role created successfuly' ;
        $status = (!$role) ? 'error' : 'success' ;
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
            'title'=>'role store',
            'event'=>'store',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data role dengan nama role : '.$input['role'].' pada menu setting submenu konfigurasi role.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return json_encode(['status'=>$status,'msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $role = Role::find(Crypt::decrypt($id));
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",Crypt::decrypt($id))
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
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
            'title'=>'role show',
            'event'=>'show',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah melihat data role dengan nama role : '.$role->name.' pada menu setting submenu konfigurasi role.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('page.roles.show',compact('role','permission','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find(Crypt::decrypt($id));
        return view('page.roles.edit',compact('role'));
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
            'role' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find(Crypt::decrypt($id));
        $role->name = $request->input('role');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        $msg = ($role) ? 'Role updated successfuly':'Role updated unsuccessfuly' ;
        $status = ($role) ? 'success' : 'error' ;
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
            'title'=>'role update',
            'event'=>'update',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data role dengan nama role : '.$role->name.' pada menu setting submenu konfigurasi role.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return json_encode(['status'=>$status,'msg'=>$msg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role=DB::table("roles")->where('id',Crypt::decrypt($id))->delete();
        $msg = (!$role) ? 'Role updated unsuccessfuly' : 'Role updated successfuly' ;
        $status = (!$role) ? 'success' : 'error' ;
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
            'title'=>'role delete',
            'event'=>'delete',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus data role dengan nama role : '.$role->name.' pada menu setting submenu konfigurasi role.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return json_encode(['status'=>$status,'msg'=>$msg]);
    }
}
