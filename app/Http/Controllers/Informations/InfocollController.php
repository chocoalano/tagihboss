<?php

namespace App\Http\Controllers\Informations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Menu\Menu;
use App\Models\Informations\InfocollModels;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class InfocollController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:infocoll-list|infocoll-create|infocoll-edit|infocoll-delete', ['only' => ['index','store']]);
         $this->middleware('permission:infocoll-create', ['only' => ['create','store']]);
         $this->middleware('permission:infocoll-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:infocoll-delete', ['only' => ['destroy']]);
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
            'title'=>'information collections access',
            'event'=>'access',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu information.',
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
            $data=InfocollModels::orderBy('id','DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('infocoll-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('infocolls.show', Crypt::encrypt($row->id)).'");><i class="dw dw-eye"></i> View</button>':'';
                $btnEdit=(Gate::allows('infocoll-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('infocolls.edit', Crypt::encrypt($row->id)).'");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('infocoll-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.route('infocolls.destroy', Crypt::encrypt($row->id)).'");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
        return view('page.InfoCollection.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('page.InfoCollection.create');
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
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $insert=InfocollModels::create([
                'title'=>$input['title'],
                'slug'=>Str::slug($input['title']),
                'information'=>$input['information'],
                'state'=>$input['status']
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
                    'title'=>'information collections store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan data information collection dengan title : '.$input['title'].' pada menu information.',
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
    public function show($id)
    {
        $findInfo=InfocollModels::find(Crypt::decrypt($id));

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
            'title'=>'information collections show',
            'event'=>'show',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah melihat data information collection dengan title : '.$findInfo->title.' pada menu information.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        return view('page.InfoCollection.view', compact('findInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $findInfo=InfocollModels::find(Crypt::decrypt($id));
        return view('page.InfoCollection.edit', compact('findInfo'));
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
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages()]);
        }else{
            $insert=InfocollModels::find(Crypt::decrypt($id))->update([
                'title'=>$input['title'],
                'slug'=>Str::slug($input['title']),
                'information'=>$input['information'],
                'state'=>$input['status']
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
                    'title'=>'information collections update',
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
    public function destroy($id)
    {
        $delete=InfocollModels::find(Crypt::decrypt($id));
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
            'title'=>'information collections delete',
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
