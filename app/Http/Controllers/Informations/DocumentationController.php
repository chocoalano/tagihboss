<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Menu\Menu;
use Spatie\Permission\Models\Permission;
use App\Models\Documentations\DocumentationsModels;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class DocumentationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:documentation-list|documentation-create|documentation-edit|documentation-delete', [
            'only' => ['index','store']
        ]);
        $this->middleware('permission:documentation-create', ['only' => ['create','store']]);
        $this->middleware('permission:documentation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:documentation-delete', ['only' => ['destroy']]);
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

        // if (Browser::isDesktop() == true) {
        //     $device = 'Computer/Laptop/Notebook';
        // }elseif (Browser::isTablet() == true) {
        //     $device = 'Tablet Device';
        // }else{
        //     $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
        // }

        // $user = User::find(Auth::user()->id);
        // $user->log_timelines()->create([
        //     'name'=>Auth::user()->name,
        //     'title'=>'information documentation access',
        //     'event'=>'access',
        //     'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu information.',
        //     'ip_address'=>$request->ip(),
        //     'platform'=>Browser::platformName(),
        //     'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
        //     'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
        //     'device'=>$device,
        //     'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
        //     'browser_engine'=>Browser::browserEngine(),
        //     'agent'=>Browser::userAgent()
        // ]);
        if ($request->ajax()) {
            $data=DocumentationsModels::orderBy('id','DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('documentation-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('documentations.show', Crypt::encrypt($row->id)).'");><i class="dw dw-eye"></i> View</button>':'';
                $btnEdit=(Gate::allows('documentation-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('documentations.edit', Crypt::encrypt($row->id)).'");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('documentation-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.route('documentations.destroy', Crypt::encrypt($row->id)).'");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
        return view('page.Documentation.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission=Permission::get();
        return view('page.Documentation.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'information' => 'required|string',
            'attention' => 'required|string',
            'authorization' => 'required|string',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $insert=DocumentationsModels::create([
                'title'=>$input['title'],
                'slug'=>Str::slug($input['title']),
                'information'=>$input['information'],
                'attention'=>$input['attention'],
                'authorization'=>$input['authorization'],
                'state'=>$input['status'],
                'created_by_name'=>Auth::user()->name
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
                    'title'=>'information documentation store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data information documentation dengan title : '.$input['title'].' pada menu information sub menu documentation.',
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
        $findInfo=DocumentationsModels::find(Crypt::decrypt($id));

        if (Browser::isDesktop() == true) {
            $device = 'Computer/Laptop/Notebook';
        }elseif (Browser::isTablet() == true) {
            $device = 'Tablet Device';
        }else{
            $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
        }
        $permission=Permission::get();
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
            'name'=>Auth::user()->name,
            'title'=>'information documentation show',
            'event'=>'show',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah melihat data information documentation dengan title : '.$findInfo->title.' pada menu information.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('page.Documentation.view', compact('findInfo','permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $findInfo=DocumentationsModels::find(Crypt::decrypt($id));
        $permission=Permission::get();
        return view('page.Documentation.edit', compact('findInfo','permission'));
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
        $input=$request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'information' => 'required|string',
            'attention' => 'required|string',
            'authorization' => 'required|string',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $insert=DocumentationsModels::find(Crypt::decrypt($id))->update([
                'title'=>$input['title'],
                'slug'=>Str::slug($input['title']),
                'information'=>$input['information'],
                'attention'=>$input['attention'],
                'authorization'=>$input['authorization'],
                'state'=>$input['status'],
                'created_by_name'=>Auth::user()->name
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
                    'title'=>'information documentation update',
                    'event'=>'update',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data information collection dengan title : '.$input['title'].' pada menu information.',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $delete=DocumentationsModels::find(Crypt::decrypt($id));
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
            'title'=>'information documentation delete',
            'event'=>'delete',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus data information collection dengan title : '.$delete->title.' pada menu information.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $delete->delete();
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
