<?php

namespace App\Http\Controllers\Assigments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assigments\VisitTempatTinggal;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\Assigments\ViewTaskAssigment;
use App\Models\userRoles;
use App\User;
use App\Models\Assigments\VisitJaminan;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class VisitController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:assigment-list|assigment-create|assigment-edit|assigment-delete', ['only' => ['index','store']]);
         $this->middleware('permission:assigment-create', ['only' => ['create','store']]);
         $this->middleware('permission:assigment-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:assigment-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTempTgl(Request $request){
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
          'title'=>'visit access',
          'event'=>'access',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses tab visit tempat tinggal pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
        return view('page.tasklist.assigment.visit.tempat-tinggal.index');
    }
    public function indexJmnan(Request $request){
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
          'title'=>'visit access',
          'event'=>'access',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses tab visit jaminan pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
        return view('page.tasklist.assigment.visit.jaminan.index');
    }
    public function tempattinggal(Request $request)
    {
        if ($request->ajax()) {
            $query = VisitTempatTinggal::with('Task');
            $data=$query->get();
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('assigment-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('assigments.show', Crypt::encrypt($row->id)).'","tt");><i class="dw dw-binocular"></i> View</button>':'';
                $btnEdit=(Gate::allows('assigment-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('assigments.edit', Crypt::encrypt($row->id)).'","tt");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('assigment-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.url('assigments-destroy-tt', Crypt::encrypt($row->id)).'","tt");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
            ->addColumn('no_rekening', function($row){
                return $row->Task->no_rekening;
            })
            ->addColumn('kondisi', function($row){
                return str_replace("-", " ", $row->kondisi_tempat);
            })
            ->rawColumns(['action','no_rekening','kondisi'])
            ->make(true);
        }
    }
    public function jaminan(Request $request)
    {
        if ($request->ajax()) {
            $query = VisitJaminan::with('Task');
            $data=$query->get();
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('assigment-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('assigments.show', Crypt::encrypt($row->id)).'","jm");><i class="dw dw-binocular"></i> View</button>':'';
                $btnEdit=(Gate::allows('assigment-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('assigments.edit', Crypt::encrypt($row->id)).'","jm");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('assigment-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.url('assigments-destroy-jm', Crypt::encrypt($row->id)).'","jm");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
            ->addColumn('no_rekening', function($row){
                return $row->Task->no_rekening;
            })
            ->addColumn('kondisi', function($row){
                return str_replace("-", " ", $row->kondisi_tempat);
            })
            ->rawColumns(['action','no_rekening','kondisi'])
            ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTT(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_code' => 'required|numeric',
            'kondisi_tempat_tinggal' => 'required|string',
            'latitude_tempat_tinggal' => 'required|string',
            'longitude_tempat_tinggal' => 'required|string',
            'longitude_tempat_tinggal' => 'required|string',
            'imageTempatTinggal1' => 'required|string',
            'imageTempatTinggal2' => 'required|string',
            'imageTempatTinggal3' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $img1 = base64_decode($input['imageTempatTinggal1']);
            $img2 = base64_decode($input['imageTempatTinggal2']);
            $img3 = base64_decode($input['imageTempatTinggal3']);
            $public_path=public_path('img/visit/'.date('F-Y'));
            if (!file_exists($public_path)) {
            mkdir($public_path,0777);
            }
            $safeName1 = time().'-visit-tempat-tinggal-first-'.$input['task_code'].'.'.'png';
            $safeName2 = time().'-visit-tempat-tinggal-seconds-'.$input['task_code'].'.'.'png';
            $safeName3 = time().'-visit-tempat-tinggal-Three-'.$input['task_code'].'.'.'png';
            $destinationPath = public_path() . $public_path;
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName1, $img1);
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName2, $img2);
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName3, $img3);
            $filesaved1="img/visit/".date('F-Y')."/".$safeName1;   
            $filesaved2="img/visit/".date('F-Y')."/".$safeName2;   
            $filesaved3="img/visit/".date('F-Y')."/".$safeName3;
            $stored=VisitTempatTinggal::create([
                'task_code' => $input['task_code'],
                'kondisi_tempat' => $input['kondisi_tempat_tinggal'],
                'latitude' => $input['latitude_tempat_tinggal'],
                'longitude' => $input['longitude_tempat_tinggal'],
                'file1' => $filesaved1,
                'file2' => $filesaved2,
                'file3' => $filesaved3
            ]);
            if ($stored) {
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
                  'title'=>'visit store',
                  'event'=>'store',
                  'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan visit tempat tinggal  baru dengan task_code: '.$input['task_code'].' pada tab visit tempat tinggal yang terdapat pada menu task assigment.',
                  'ip_address'=>$request->ip(),
                  'platform'=>Browser::platformName(),
                  'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                  'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                  'device'=>$device,
                  'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                  'browser_engine'=>Browser::browserEngine(),
                  'agent'=>Browser::userAgent()
                ]);
                // NOTIF::STARTED
                $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
                $UC=userRoles::where('rolename','Unit Head Collections')->first();
                $DC=userRoles::where('rolename','Dept Head Collections')->first();
                $SA=userRoles::where('rolename','Superadmin')->first();
                $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
                $notifCreate=NotificationModels::create([
                    'event'=>'visit',
                    'user_id'=>Auth::user()->id,
                    'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menambahkan visit tempat tinggal baru dengan no pengajuan : '.$f->no_rekening.'.',
                    'link_show'=>'notif-link-show/show-visit-tempat-tinggal/'.$input['task_code']
                ]);
                $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
                $notifCreate->notifTo()->saveMany($dataNotifTo);
                // NOTIF::ENDED
                return response()->json(['status'=>'success', 'validator'=>true, 'msg'=>'Requested Successfully']);
            }
        }
    }
    public function storeJM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_code' => 'required|numeric',
            'kondisi_jaminan' => 'required|string',
            'latitude_jaminan' => 'required|string',
            'longitude_jaminan' => 'required|string',
            'imageJaminan1' => 'required|string',
            'imageJaminan2' => 'required|string',
            'imageJaminan3' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $img1 = base64_decode($input['imageJaminan1']);
            $img2 = base64_decode($input['imageJaminan2']);
            $img3 = base64_decode($input['imageJaminan3']);
            $public_path=public_path('img/visit/'.date('F-Y'));
            if (!file_exists($public_path)) {
            mkdir($public_path,0777);
            }
            $safeName1 = time().'-visit-jaminan-first-'.$input['task_code'].'.'.'png';
            $safeName2 = time().'-visit-jaminan-seconds-'.$input['task_code'].'.'.'png';
            $safeName3 = time().'-visit-jaminan-Three-'.$input['task_code'].'.'.'png';
            $destinationPath = public_path() . $public_path;
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName1, $img1);
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName2, $img2);
            file_put_contents(public_path().'/img/visit/'.date('F-Y').'/'.$safeName3, $img3);
            $filesaved1="img/visit/".date('F-Y')."/".$safeName1;   
            $filesaved2="img/visit/".date('F-Y')."/".$safeName2;   
            $filesaved3="img/visit/".date('F-Y')."/".$safeName3;
            $stored=VisitJaminan::create([
                'task_code' => $input['task_code'],
                'kondisi_tempat' => $input['kondisi_jaminan'],
                'latitude' => $input['latitude_jaminan'],
                'longitude' => $input['longitude_jaminan'],
                'file1' => $filesaved1,
                'file2' => $filesaved2,
                'file3' => $filesaved3
            ]);
            if ($stored) {
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
                    'title'=>'visit store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan visit jaminan  baru dengan task_code: '.$input['task_code'].' pada tab visit jaminan yang terdapat pada menu task assigment.',
                    'ip_address'=>$request->ip(),
                    'platform'=>Browser::platformName(),
                    'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                    'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                    'device'=>$device,
                    'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                    'browser_engine'=>Browser::browserEngine(),
                    'agent'=>Browser::userAgent()
                ]);
                // NOTIF::STARTED
                $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
                $UC=userRoles::where('rolename','Unit Head Collections')->first();
                $DC=userRoles::where('rolename','Dept Head Collections')->first();
                $SA=userRoles::where('rolename','Superadmin')->first();
                $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
                $notifCreate=NotificationModels::create([
                    'event'=>'visit',
                    'user_id'=>Auth::user()->id,
                    'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menambahkan visit jaminan baru dengan no pengajuan : '.$f->no_rekening.'.',
                    'link_show'=>'notif-link-show/show-visit-jaminan/'.$input['task_code']
                ]);
                $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
                $notifCreate->notifTo()->saveMany($dataNotifTo);
                // NOTIF::ENDED
                return response()->json(['status'=>'success', 'validator'=>true, 'msg'=>'Requested Successfully']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showtempattinggal($id)
    {
        dd($request->all());
    }

    public function showjaminan($id)
    {
        dd($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tempattinggalUpdate(Request $request, $id)
    {
        $input=$request->all();
        $validator = Validator::make($request->all(), [
            'kondisi' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'longitude' => 'required|string',
            'image1' => 'mimes:jpeg,jpg,png,gif|required|max:40000',
            'image2' => 'mimes:jpeg,jpg,png,gif|required|max:40000',
            'image3' => 'mimes:jpeg,jpg,png,gif|required|max:40000'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $find=VisitTempatTinggal::find($id);
            $img1check=$find->file1;
            $img2check=$find->file2;
            $img3check=$find->file3;
            if (file_exists($img1check)&&file_exists($img2check)&&file_exists($img3check)) {
                $extractPath=explode("/",$img1check);
                if (date("F-Y")==$extractPath[2]) {
                    unlink($img1check);
                    unlink($img2check);
                    unlink($img3check);
                }else{
                    rmdir(public_path('img/visit/'.$extractPath[2]),0777);
                }
            }
            $rImg1=$request->file('image1');
            $rImg2=$request->file('image2');
            $rImg3=$request->file('image3');
            $img1 = time().'-tempattinggal-first-'.$input['code'].'.'.$rImg1->extension();
            $img2 = time().'-tempattinggal-second-'.$input['code'].'.'.$rImg2->extension();
            $img3 = time().'-tempattinggal-thirth-'.$input['code'].'.'.$rImg3->extension();
            $public_path=public_path('img/visit/'.date('F-Y'));
            if (!file_exists($public_path)) {
              mkdir($public_path,0777);
            }
              $rImg1->move(\base_path()."/public/img/visit/".date('F-Y'),$img1);
              $rImg2->move(\base_path()."/public/img/visit/".date('F-Y'),$img2);
              $rImg3->move(\base_path()."/public/img/visit/".date('F-Y'),$img3);
              $filesaved1="img/visit/".date('F-Y')."/".$img1;   
              $filesaved2="img/visit/".date('F-Y')."/".$img2;   
              $filesaved3="img/visit/".date('F-Y')."/".$img3;
              $stored=$find->update([
                'kondisi_tempat' => $input['kondisi'],
                'latitude' => $input['latitude'],
                'longitude' => $input['longitude'],
                'file1' => $filesaved1,
                'file2' => $filesaved2,
                'file3' => $filesaved3
            ]);
        }
        if (!$stored) {
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
                'title'=>'visit update',
                'event'=>'update',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui visit tempat tinggal  baru dengan task_code: '.$input['task_code'].' pada tab visit tempat tinggal yang terdapat pada menu task assigment.',
                'ip_address'=>$request->ip(),
                'platform'=>Browser::platformName(),
                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                'device'=>$device,
                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                'browser_engine'=>Browser::browserEngine(),
                'agent'=>Browser::userAgent()
            ]);
            // NOTIF::STARTED
            $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
            $UC=userRoles::where('rolename','Unit Head Collections')->first();
            $DC=userRoles::where('rolename','Dept Head Collections')->first();
            $SA=userRoles::where('rolename','Superadmin')->first();
            $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
            $notifCreate=NotificationModels::create([
              'event'=>'visit',
              'user_id'=>Auth::user()->id,
              'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui visit tempat tinggal dengan no pengajuan : '.$f->no_rekening.'.',
              'link_show'=>'notif-link-show/show-visit-tempat-tinggal/'.$input['task_code']
            ]);
            $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
            $notifCreate->notifTo()->saveMany($dataNotifTo);
            // NOTIF::ENDED
        }
        return json_encode(['status'=>$state,'msg'=>$msg]);
    }
    public function jaminanUpdate(Request $request, $id)
    {
        $input=$request->all();
        $validator = Validator::make($request->all(), [
            'kondisi' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'longitude' => 'required|string',
            'image1' => 'mimes:jpeg,jpg,png,gif|required|max:40000',
            'image2' => 'mimes:jpeg,jpg,png,gif|required|max:40000',
            'image3' => 'mimes:jpeg,jpg,png,gif|required|max:40000'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $find=VisitJaminan::find($id);
            $img1check=$find->file1;
            $img2check=$find->file2;
            $img3check=$find->file3;
            if (file_exists($img1check)&&file_exists($img2check)&&file_exists($img3check)) {
                $extractPath=explode("/",$img1check);
                if (date("F-Y")==$extractPath[2]) {
                    unlink($img1check);
                    unlink($img2check);
                    unlink($img3check);
                }else{
                    rmdir(public_path('img/visit/'.$extractPath[2]),0777);
                }
            }
            $rImg1=$request->file('image1');
            $rImg2=$request->file('image2');
            $rImg3=$request->file('image3');
            $img1 = time().'-tempattinggal-first-'.$input['code'].'.'.$rImg1->extension();
            $img2 = time().'-tempattinggal-second-'.$input['code'].'.'.$rImg2->extension();
            $img3 = time().'-tempattinggal-thirth-'.$input['code'].'.'.$rImg3->extension();
            $public_path=public_path('img/visit/'.date('F-Y'));
            if (!file_exists($public_path)) {
              mkdir($public_path,0777);
            }
              $rImg1->move(\base_path()."/public/img/visit/".date('F-Y'),$img1);
              $rImg2->move(\base_path()."/public/img/visit/".date('F-Y'),$img2);
              $rImg3->move(\base_path()."/public/img/visit/".date('F-Y'),$img3);
              $filesaved1="img/visit/".date('F-Y')."/".$img1;   
              $filesaved2="img/visit/".date('F-Y')."/".$img2;   
              $filesaved3="img/visit/".date('F-Y')."/".$img3;
              $stored=$find->update([
                'kondisi_tempat' => $input['kondisi'],
                'latitude' => $input['latitude'],
                'longitude' => $input['longitude'],
                'file1' => $filesaved1,
                'file2' => $filesaved2,
                'file3' => $filesaved3
            ]);
        }
        if (!$stored) {
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
                'title'=>'visit update',
                'event'=>'update',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui visit jaminan  baru dengan task_code: '.$input['task_code'].' pada tab visit jaminan yang terdapat pada menu task assigment.',
                'ip_address'=>$request->ip(),
                'platform'=>Browser::platformName(),
                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                'device'=>$device,
                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                'browser_engine'=>Browser::browserEngine(),
                'agent'=>Browser::userAgent()
            ]);
            // NOTIF::STARTED
            $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
            $UC=userRoles::where('rolename','Unit Head Collections')->first();
            $DC=userRoles::where('rolename','Dept Head Collections')->first();
            $SA=userRoles::where('rolename','Superadmin')->first();
            $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
            $notifCreate=NotificationModels::create([
              'event'=>'visit',
              'user_id'=>Auth::user()->id,
              'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui visit jaminan dengan no pengajuan : '.$f->no_rekening.'.',
              'link_show'=>'notif-link-show/show-visit-jaminan/'.$input['task_code']
            ]);
            $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
            $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
            $notifCreate->notifTo()->saveMany($dataNotifTo);
            // NOTIF::ENDED
        }
        return json_encode(['status'=>$state,'msg'=>$msg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tempattinggalDestroy(Request $request, $id)
    {
        $find=VisitTempatTinggal::find(Crypt::decrypt($id));
        $img1check=$find->file1;
        $img2check=$find->file2;
        $img3check=$find->file3;
        if (file_exists($img1check)&&file_exists($img2check)&&file_exists($img3check)) {
            $extractPath=explode("/",$img1check);
            if (date("F-Y")==$extractPath[2]) {
                unlink($img1check);
                unlink($img2check);
                unlink($img3check);
            }
        }
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
          'title'=>'visit delete',
          'event'=>'delete',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus visit tempat tinggal  baru dengan task_code: '.$find->task_code.' pada tab visit tempat tinggal yang terdapat pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
            // NOTIF::STARTED
        $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
        $UC=userRoles::where('rolename','Unit Head Collections')->first();
        $DC=userRoles::where('rolename','Dept Head Collections')->first();
        $SA=userRoles::where('rolename','Superadmin')->first();
        $f=ViewTaskAssigment::where('task_code',$find->task_code)->first();
        $notifCreate=NotificationModels::create([
          'event'=>'visit',
          'user_id'=>Auth::user()->id,
          'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menghapus visit tempat tinggal dengan no pengajuan : '.$f->no_rekening.'.',
          'link_show'=>'#'
        ]);
        $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
        $notifCreate->notifTo()->saveMany($dataNotifTo);
            // NOTIF::ENDED
        $delete=$find->delete();
        if (!$delete) {
            $state='badreq';
            $msg='Request unsuccessfuly !';
        }else{
            $state='success';
            $msg='Request successfuly !';
        }
        return json_encode(['status'=>$state,'msg'=>$msg]);
    }
    public function jaminanDestroy(Request $request, $id)
    {
        $find=VisitJaminan::find(Crypt::decrypt($id));
           $img1check=$find->file1;
           $img2check=$find->file2;
           $img3check=$find->file3;
           if (file_exists($img1check)&&file_exists($img2check)&&file_exists($img3check)) {
            $extractPath=explode("/",$img1check);
            if (date("F-Y")==$extractPath[2]) {
                unlink($img1check);
                unlink($img2check);
                unlink($img3check);
            }
        }
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
          'title'=>'visit delete',
          'event'=>'delete',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus visit jaminan  baru dengan task_code: '.$input['task_code'].' pada tab visit jaminan yang terdapat pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
        // NOTIF::STARTED
        $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
        $UC=userRoles::where('rolename','Unit Head Collections')->first();
        $DC=userRoles::where('rolename','Dept Head Collections')->first();
        $SA=userRoles::where('rolename','Superadmin')->first();
        $f=ViewTaskAssigment::where('task_code',$find->task_code)->first();
        $notifCreate=NotificationModels::create([
          'event'=>'visit',
          'user_id'=>Auth::user()->id,
          'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menghapus visit jaminan dengan no pengajuan : '.$f->no_rekening.'.',
          'link_show'=>'#'
        ]);
        $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
        $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
        $notifCreate->notifTo()->saveMany($dataNotifTo);
        // NOTIF::ENDED
        $delete=$find->delete();
        if (!$delete) {
            $state='badreq';
            $msg='Request unsuccessfuly !';
        }else{
            $state='success';
            $msg='Request successfuly !';
        }
        return json_encode(['status'=>$state,'msg'=>$msg]);
    }
}
