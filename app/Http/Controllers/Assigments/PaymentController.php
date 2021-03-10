<?php

namespace App\Http\Controllers\Assigments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Assigments\PaymentAssigment;
use App\Models\Notification\NotificationModels;
use App\Models\Notification\NotificationTo;
use App\Models\userRoles;
use App\User;
use App\Models\Assigments\ViewTaskAssigment;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use PDF;
use Browser;

class PaymentController extends Controller
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
              'title'=>'payment show',
              'event'=>'show',
              'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses tab payment pada menu task assigment.',
              'ip_address'=>$request->ip(),
              'platform'=>Browser::platformName(),
              'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
              'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
              'device'=>$device,
              'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
              'browser_engine'=>Browser::browserEngine(),
              'agent'=>Browser::userAgent()
          ]);
            $query = PaymentAssigment::with('Task');
            $data=$query->get();
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnDownload=(Gate::allows('assigment-list'))?'<a class="dropdown-item" href="'.url('assigments-payment-print', Crypt::encrypt($row->id)).'");><i class="dw dw-download"></i> Payment Receipt</a>':'';
                $btnView=(Gate::allows('assigment-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('assigments.show', Crypt::encrypt($row->id)).'","payment");><i class="dw dw-binocular"></i> View</button>':'';
                $btnEdit=(Gate::allows('assigment-edit'))?'<button class="dropdown-item" type="button" onclick=edit("'.route('assigments.edit', Crypt::encrypt($row->id)).'","payment");><i class="dw dw-edit2"></i> Edit</button>':'';
                $btnDelete=(Gate::allows('assigment-delete'))?'<button class="dropdown-item" type="button" onclick=destroy("'.url('assigments-destroy-payment', Crypt::encrypt($row->id)).'","payment");><i class="dw dw-delete-3"></i> Delete</button>':'';
                $btn = '
                <div class="dropdown">
                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    '.$btnDownload.''.$btnView.''.$btnEdit.''.$btnDelete.'
                    </div>
                </div>
                ';
                return $btn;
            })
            ->addColumn('angsuran', function($row){
                return 'Rp. '.number_format($row->angsuran,0,',','.');
            })
            ->addColumn('collect', function($row){
                return 'Rp. '.number_format($row->collect_fee,2);
            })
            ->addColumn('denda', function($row){
                return 'Rp. '.number_format($row->denda,2);
            })
            ->addColumn('titipan', function($row){
                return 'Rp. '.number_format($row->titipan,2);
            })
            ->addColumn('total', function($row){
                return 'Rp. '.number_format($row->total_bayar,2);
            })
            ->addColumn('sisa_angsuran', function($row){
                return 'Rp. '.number_format($row->sisa_angsuran,2);
            })
            ->addColumn('sisa_denda', function($row){
                return 'Rp. '.number_format($row->sisa_denda,2);
            })
            ->rawColumns(['action','collect','denda','titipan','total','sisa_angsuran','sisa_denda','angsuran'])
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
            "task_code" => 'required|numeric',
            "angsuran" => 'required|numeric',
            "bayar_denda" => 'required|numeric',
            "collect_fee" => 'required|numeric',
            "titipan" => 'required|numeric',
            "ang_ke" => 'required|numeric',
            "tenor" => 'required|numeric',
            "no_bss" => 'required|numeric',
            "total_bayar_angsuran" => 'required|numeric',
            "sisa_angsuran" => 'required|numeric',
            "sisa_denda" => 'required|numeric',
            'tgl_jt' => 'required',
            'take_foto' => 'required',
            'signature64Nasabah' => 'required',
            'signature64Collection' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $file = base64_decode($input['take_foto']);
            $fileSignatureNasabah = base64_decode($input['signature64Nasabah']);
            $fileSignatureCollection = base64_decode($input['signature64Collection']);

            $folderName = public_path('img/payment/pic/'.date('F-Y'));
            $SignaturefolderName = public_path('img/payment/signature/'.date('F-Y'));
            if (!file_exists($folderName)) {
                mkdir($folderName,0777);
                mkdir($SignaturefolderName,0777);
            }
            $safeName = time().'-payment-'.$input['task_code'].'.'.'png';
            $destinationPath = public_path() . $folderName;
            $success = file_put_contents(public_path().'/img/payment/pic/'.date('F-Y').'/'.$safeName, $file);
            $filesaved="img/payment/pic/".date('F-Y')."/".$safeName;

            $safeNameSignatureNasabah = time().'-payment-signature-nasabah-'.$input['task_code'].'.'.'png';
            $safeNameSignatureCollection = time().'-payment-signature-collection-'.$input['task_code'].'.'.'png';
            $destinationPathSignature = public_path() . $SignaturefolderName;
            file_put_contents(public_path().'/img/payment/signature/'.date('F-Y').'/'.$safeNameSignatureNasabah, $fileSignatureNasabah);
            file_put_contents(public_path().'/img/payment/signature/'.date('F-Y').'/'.$safeNameSignatureCollection, $fileSignatureCollection);
            $filesavedSignatureNasabah="img/payment/signature/".date('F-Y')."/".$safeNameSignatureNasabah;
            $filesavedSignatureCollection="img/payment/signature/".date('F-Y')."/".$safeNameSignatureCollection;

            $stored=PaymentAssigment::create([
                'task_code'=>$input['task_code'],
                'ang_ke'=>$input['ang_ke'],
                'tenor'=>$input['tenor'],
                'angsuran'=>$input['angsuran'],
                'denda'=>$input['bayar_denda'],
                'collect_fee'=>$input['collect_fee'],
                'titipan'=>$input['titipan'],
                'total_bayar'=>$input['total_bayar_angsuran'],
                'sisa_angsuran'=>$input['sisa_angsuran'],
                'sisa_denda'=>$input['sisa_denda'],
                'no_bss'=>$input['no_bss'],
                'tgl_jt_tempo'=>$input['tgl_jt'],
                'file'=>$filesaved,
                'file_ttd_nasabah'=>$filesavedSignatureNasabah,
                'file_ttd_collection'=>$filesavedSignatureCollection
            ]);
            if ($stored) {
                $status='success';
                $msg='Request Successfuly';
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
                    'title'=>'payment store',
                    'event'=>'store',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah menambahkan payment baru dengan task_code: '.$input['task_code'].' pada tab payment yang terdapat pada menu task assigment.',
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
                    'event'=>'payment',
                    'user_id'=>Auth::user()->id,
                    'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menambahkan payment baru dengan no pengajuan : '.$f->no_rekening.'.',
                    'link_show'=>'notif-link-show/show-payment/'.$input['task_code']
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
                $msg='Request Unsuccessfuly';
            }
            return response()->json(['status'=>$status, 'validator'=>true, 'msg'=>$msg]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request, $id)
    {
        $q=PaymentAssigment::find(Crypt::decrypt($id));
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
            'title'=>'payment print',
            'event'=>'print',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah mencetak data payment dengan task_code: '.$q->task_code.' pada tab payment yang terdapat pada menu task assigment.',
            'ip_address'=>$request->ip(),
            'platform'=>Browser::platformName(),
            'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
            'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
            'device'=>$device,
            'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
            'browser_engine'=>Browser::browserEngine(),
            'agent'=>Browser::userAgent()
        ]);
        $x=ViewTaskAssigment::where('task_code',$q->task_code)->first();
        // dd($x);
        $pdf = PDF::loadview('download.pdf.paymentpdf',['q'=>$q,'x'=>$x],[ 
          'title' => 'Invoice', 
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        return $pdf->download('payment-receipt-pdf');
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
            "task_code" => 'required|numeric',
            "angsuran" => 'required|numeric',
            "bayar_denda" => 'required|numeric',
            "collect_fee" => 'required|numeric',
            "titipan" => 'required|numeric',
            "ang_ke" => 'required|numeric',
            "tenor" => 'required|numeric',
            "no_bss" => 'required|numeric',
            "total_bayar_angsuran" => 'required|numeric',
            "sisa_angsuran" => 'required|numeric',
            "sisa_denda" => 'required|numeric',
            'tgl_jt' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'validator'=>$validator->messages(), 'msg'=>'Requested Unuccessfully']);
        }else{
            $input=$request->all();
            $stored=PaymentAssigment::find(Crypt::decrypt($id))->update([
                'task_code'=>$input['task_code'],
                'ang_ke'=>$input['ang_ke'],
                'tenor'=>$input['tenor'],
                'angsuran'=>$input['angsuran'],
                'denda'=>$input['bayar_denda'],
                'collect_fee'=>$input['collect_fee'],
                'titipan'=>$input['titipan'],
                'total_bayar'=>$input['total_bayar_angsuran'],
                'sisa_angsuran'=>$input['sisa_angsuran'],
                'sisa_denda'=>$input['sisa_denda'],
                'no_bss'=>$input['no_bss'],
                'tgl_jt_tempo'=>$input['tgl_jt']
            ]);
            if ($stored) {
                $status='success';
                $msg='Request Successfuly';
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
                    'title'=>'payment update',
                    'event'=>'update',
                    'description'=>'User dengan akun username: '.Auth::user()->name.' telah memperbaharui data payment dengan task_code: '.$input['task_code'].' pada tab payment yang terdapat pada menu task assigment.',
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
                    'event'=>'payment',
                    'user_id'=>Auth::user()->id,
                    'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah memperbaharui data payment dengan no pengajuan : '.$f->no_rekening.'.',
                    'link_show'=>'notif-link-show/show-payment/'.$input['task_code']
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
                $msg='Request Unsuccessfuly';
            }
            return response()->json(['status'=>$status, 'validator'=>true, 'msg'=>$msg]);
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
        $find=PaymentAssigment::find(Crypt::decrypt($id));
        $cekimageFile=public_path($find->file);
        $cekimageTTDnasabah=public_path('/'.$find->file_ttd_nasabah);
        $cekimageTTDcollection=public_path('/'.$find->file_ttd_collection);
        if (file_exists($cekimageFile)) {
            @unlink($cekimageFile);
        }
        if (file_exists($cekimageTTDnasabah)) {
            @unlink($cekimageTTDnasabah);
        }
        if (file_exists($cekimageTTDcollection)) {
            @unlink($cekimageTTDcollection);
        }
        if (Browser::isDesktop() == true) {
            $device = 'Computer/Laptop/Notebook';
        }elseif (Browser::isTablet() == true) {
            $device = 'Tablet Device';
        }else{
            $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
        }
        $user = User::find(Auth::user()->id);
        $update = $user->log_timelines()->create([
            'name'=>Auth::user()->name,
            'title'=>'payment delete',
            'event'=>'delete',
            'description'=>'User dengan akun username: '.Auth::user()->name.' telah menghapus data payment dengan task_code: '.$delete->task_code.' pada tab payment yang terdapat pada menu task assigment.',
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
            'event'=>'payment',
            'user_id'=>Auth::user()->id,
            'desc'=>ucfirst(strtolower(Auth::user()->name)).' telah menghapus payment dengan no pengajuan : '.$f->no_rekening.'.',
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
