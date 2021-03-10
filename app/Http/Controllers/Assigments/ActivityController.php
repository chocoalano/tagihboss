<?php

namespace App\Http\Controllers\Assigments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assigments\ActivityAssigment;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\Assigments\ViewTaskAssigment;
use App\Models\userRoles;
use App\User;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
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
                'title'=>'activity access',
                'event'=>'access',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses tab activity pada menu task assigment.',
                'ip_address'=>$request->ip(),
                'platform'=>Browser::platformName(),
                'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                'device'=>$device,
                'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                'browser_engine'=>Browser::browserEngine(),
                'agent'=>Browser::userAgent()
            ]);
            $query = ActivityAssigment::with('Task');
            $data=$query->get();
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('assigment-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('assigments.show', Crypt::encrypt($row->id)).'","activity");><i class="dw dw-binocular"></i> View</button>':'';
                $btnEdit=(Gate::allows('assigment-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('assigments.edit', Crypt::encrypt($row->id)).'","activity");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('assigment-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.url('assigments-destroy-activity', Crypt::encrypt($row->id)).'","activity");><i class="dw dw-delete-3"></i> Delete</button>':'';
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_code' => 'required|numeric',
            'kunjungan_ke' => 'required|numeric',
            'bertemu' => 'required|string',
            'karakter_debitur' => 'required|string',
            'total_penghasilan' => 'required|string',
            'kondisi_pekerjaan' => 'required|string',
            'asset_debt' => 'required|string',
            'janji_byr' => 'required|string',
            'case_category' => 'required|string',
            'next_action' => 'required|string',
            'keterangan' => 'required|string',
            'take_foto' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $file = base64_decode($input['take_foto']);
            $folderName = public_path('img/activity/'.date('F-Y'));
            // $folderName = 'public/img/activity/'.date('F-Y');
            if (!file_exists($folderName)) {
                mkdir($folderName,0777);
            }
            $safeName = time().'-activity-'.$input['task_code'].'.'.'png';
            $destinationPath = public_path() . $folderName;
            $success = file_put_contents(public_path().'/img/activity/'.date('F-Y').'/'.$safeName, $file);
            $filesaved="img/activity/".date('F-Y')."/".$safeName;
            $q=str_replace('Rp. ', '', $input['total_penghasilan']);
            $penghasilan=str_replace('.', '', $q);
            $stored=ActivityAssigment::create([
                'task_code'=>$input['task_code'],
                'kunjungan_ke'=>$input['kunjungan_ke'],
                'bertemu'=>$input['bertemu'],
                'karakter_debitur'=>$input['karakter_debitur'],
                'total_penghasilan'=>$penghasilan,
                'kondisi_pekerjaan'=>$input['kondisi_pekerjaan'],
                'asset_debt'=>$input['asset_debt'],
                'janji_byr'=>$input['janji_byr'],
                'tgl_janji_byr'=>$input['tgl_janji_byr'],
                'case_category'=>$input['case_category'],
                'next_action'=>$input['next_action'],
                'keterangan'=>$input['keterangan'],
                'file'=>$filesaved,
              ]);
              if ($stored) {
                $status='success';
                $pesan='Request successfuly';
                // TIMELINE LOG::STARTED
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
                    'title'=>'activity store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan activity baru dengan task_code: '.$input['task_code'].' pada tab activity yang terdapat pada menu task assigment.',
                    'ip_address'=>$request->ip(),
                    'platform'=>Browser::platformName(),
                    'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
                    'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
                    'device'=>$device,
                    'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
                    'browser_engine'=>Browser::browserEngine(),
                    'agent'=>Browser::userAgent()
                ]);
                // TIMELINE LOG::ENDED
                // NOTIF::STARTED
                $SPVC=userRoles::where('rolename','Supervisor Collections')->first();
                $UC=userRoles::where('rolename','Unit Head Collections')->first();
                $DC=userRoles::where('rolename','Dept Head Collections')->first();
                $SA=userRoles::where('rolename','Superadmin')->first();
                $f=ViewTaskAssigment::where('task_code',$input['task_code'])->first();
                $notifCreate=NotificationModels::create([
                    'event'=>'activity',
                    'user_id'=>Auth::user()->id,
                    'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menambahkan activity baru dengan no pengajuan : '.$f->no_rekening.'.',
                    'link_show'=>'notif-link-show/show-activity/'.$input['task_code']
                ]);
                $dataSPV=(!is_null($SPVC)) ? new NotificationTo(['user_id'=>$SPVC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataUC=(!is_null($UC)) ? new NotificationTo(['user_id'=>$UC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataDC=(!is_null($DC)) ? new NotificationTo(['user_id'=>$DC->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataSA=(!is_null($SA)) ? new NotificationTo(['user_id'=>$SA->id,'show'=>'n']) : new NotificationTo(['user_id'=>0,'show'=>'n']);
                $dataNotifTo=[$dataSPV,$dataUC,$dataDC,$dataSA];
                $notifCreate->notifTo()->saveMany($dataNotifTo);
                // NOTIF::ENDED
              }else{
                $status='error';
                $pesan='Request unsuccessfuly';
              }
            return json_encode(['status'=>$status, 'validator'=>true, 'msg'=>$pesan]);
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
        //
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'task_code' => 'required|numeric',
            'kunjungan_ke' => 'required|string',
            'bertemu' => 'required|string',
            'karakter_debitur' => 'required|string',
            'total_penghasilan' => 'required|string',
            'kondisi_pekerjaan' => 'required|string',
            'asset_debt' => 'required|string',
            'janji_byr' => 'required|string',
            'next_action' => 'required|string',
            'keterangan' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $tgljb=(isset($input['tgl_janji_byr']))? $input['tgl_janji_byr']: NULL;
            $find=ActivityAssigment::find(Crypt::decrypt($id));
            $update=$find->update([
                'task_code'=>$input['task_code'],
                'kunjungan_ke'=>$input['kunjungan_ke'],
                'bertemu'=>$input['bertemu'],
                'karakter_debitur'=>$input['karakter_debitur'],
                'total_penghasilan'=>$input['total_penghasilan'],
                'kondisi_pekerjaan'=>$input['kondisi_pekerjaan'],
                'asset_debt'=>$input['asset_debt'],
                'janji_byr'=>$input['janji_byr'],
                'tgl_janji_byr'=>$tgljb,
                'next_action'=>$input['next_action'],
                'keterangan'=>$input['keterangan']
            ]);
        }
        if (!$update) {
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
                'title'=>'activity update',
                'event'=>'update',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui activity dengan task_code: '.$input['task_code'].' pada tab activity yang terdapat pada menu task assigment.',
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
                'event'=>'activity',
                'user_id'=>Auth::user()->id,
                'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah selesai memperbaharui activity dengan no pengajuan : '.$f->no_rekening.'.',
                'link_show'=>'notif-link-show/show-activity/'.$input['task_code']
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
    public function destroy($id)
    {
        $find=ActivityAssigment::find(Crypt::decrypt($id));
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
                'title'=>'activity delete',
                'event'=>'delete',
                'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus activity dengan task_code: '.$find->task_code.' pada tab activity yang terdapat pada menu task assigment.',
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
                'event'=>'activity',
                'user_id'=>Auth::user()->id,
                'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah selesai menghapus activity dengan no pengajuan : '.$f->no_rekening.'.',
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
