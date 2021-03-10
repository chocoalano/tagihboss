<?php

namespace App\Http\Controllers\Assigments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Menu\Menu;
use App\User;
use App\Models\Assigments\TaskAssigment;
use App\Models\Assigments\TaskAssigmentDetail;
use App\Models\Assigments\Nasabah;
use App\Models\Assigments\VisitTempatTinggal;
use App\Models\Assigments\VisitJaminan;
use App\Models\Assigments\ActivityAssigment;
use App\Models\Assigments\PaymentAssigment;
use App\Models\Assigments\MasterAssetDebitur;
use App\Models\Assigments\MasterCaseCategory;
use App\Models\Assigments\MasterKondisiPekerjaan;
use App\Models\Assigments\MasterNextAction;
use App\Models\Assigments\ViewTaskAssigment;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Crypt;
use Hash;
use DB;
use Auth;
use Validator;
use Browser;

class AssigmentController extends Controller
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
    public function index(Request $request)
    {
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
        'title'=>'assigment access',
        'event'=>'access',
        'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses menu task assigment.',
        'ip_address'=>$request->ip(),
        'platform'=>Browser::platformName(),
        'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
        'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
        'device'=>$device,
        'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
        'browser_engine'=>Browser::browserEngine(),
        'agent'=>Browser::userAgent()
      ]);
        $getMenuMain=Menu::where('type','main-menu')->get();
        $getMenuSub=Menu::where('type','sub-menu')->get();
        if ($request->ajax()) {
            // $data = ViewTaskAssigment::get();
            $data = ViewTaskAssigment::where(['user_id'=>Auth::user()->user_id, 'flag_aktif'=>1])->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btnView=(Gate::allows('assigment-list'))?'<button class="dropdown-item" type="button" onclick=view("'.route('assigments.show', Crypt::encrypt($row->task_code)).'","masters");><i class="dw dw-binocular"></i> View</button>':'';
                $btnACT=(Gate::allows('assigment-create'))?'<button class="dropdown-item" type="button" onclick=create("'.route('assigments.create').'","'.Crypt::encrypt($row->task_code).'");><i class="dw dw-diamond"></i> Action</button>':'';
                $btn = '
                <div class="dropdown">
                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    '.$btnView.''.$btnACT.'
                    </div>
                </div>
                ';
                return $btn;
            })
            ->addColumn('bakidebet', function($row){
                return 'Rp. '.number_format($row->baki_debet);
            })
            ->addColumn('tunggakan', function($row){
                return 'Rp. '.number_format($row->total_tunggakan);
            })
            ->addColumn('angsuran', function($row){
                return 'Rp. '.number_format($row->angsuran);
            })
            ->addColumn('total_tagihan', function($row){
                return 'Rp. '.number_format($row->total_tagihan);
            })
            ->addColumn('os_pokok', function($row){
                return 'Rp. '.number_format($row->baki_debet);
            })
            ->addColumn('dpd', function($row){
                return $row->ft_hari;
            })
            ->addColumn('date', function($row){
                return date('d F Y', strtotime($row->assignment_date));
            })
            ->addColumn('nama', function($row){
                return ucfirst(strtolower($row->nama_nasabah));
            })
            ->rawColumns([
              'action',
              'bakidebet',
              'tunggakan',
              'angsuran',
              'total_tagihan',
              'collect_fee',
              'dpd',
              'date',
              'nama'
            ])
            ->make(true);
        }
        return view('page.tasklist.assigment.index',compact('getMenuMain','getMenuSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $assigment=TaskAssigment::where('task_code', Crypt::decrypt($request->input('key')))->first();
        return view('page.tasklist.assigment.form.create', compact('assigment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
      $key=$request->input('key');
      if (Browser::isDesktop() == true) {
        $device = 'Computer/Laptop/Notebook';
      }elseif (Browser::isTablet() == true) {
        $device = 'Tablet Device';
      }else{
        $device = 'Mobilephone/Smartphone Device '.Browser::deviceFamily().' model: '.Browser::deviceModel().' grade: '.Browser::mobileGrade();
      }
      if ($key=="tt") {
        $q=VisitTempatTinggal::find(Crypt::decrypt($id));
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>'visit show',
          'event'=>'show',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data visit dengan task_code: '.$q->task_code.' pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
      }elseif ($key=="jm") {
        $q=VisitJaminan::find(Crypt::decrypt($id));
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>'visit show',
          'event'=>'show',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data visit dengan task_code: '.$q->task_code.' pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
      }elseif ($key=="masters") {
        $q=TaskAssigment::with('Master','Detail')->where('task_code',Crypt::decrypt($id))->first();
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>'visit show',
          'event'=>'show',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data master assigment dengan task_code: '.$q->task_code.' pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
      }elseif ($key=="activity"){
        $q=ActivityAssigment::with('Task')->find(Crypt::decrypt($id));
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>'visit show',
          'event'=>'show',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data activity dengan task_code: '.$q->task_code.' pada menu task assigment.',
          'ip_address'=>$request->ip(),
          'platform'=>Browser::platformName(),
          'is_in_apps'=>(Browser::isInApp() == true) ? 'true' : 'false',
          'boot'=>(Browser::isBot() == true) ? 'true' : 'false',
          'device'=>$device,
          'browser'=>Browser::browserFamily().' version '.Browser::browserVersion(),
          'browser_engine'=>Browser::browserEngine(),
          'agent'=>Browser::userAgent()
        ]);
      }elseif ($key=="payment"){
        $q=PaymentAssigment::with('Task')->find(Crypt::decrypt($id));
        $user = User::find(Auth::user()->id);
        $user->log_timelines()->create([
          'name'=>Auth::user()->name,
          'title'=>'visit show',
          'event'=>'show',
          'description'=>'User dengan akun username: '.Auth::user()->name.' telah mengakses data payment dengan task_code: '.$q->task_code.' pada menu task assigment.',
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
      return view('page.tasklist.assigment.form.show',compact('q','key'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $key=$request->input('key');
      if ($key=="tt") {
        $q=VisitTempatTinggal::find(Crypt::decrypt($id));
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
        $q['param']='tt';
        $q['url']=url('tempattinggal-updated',Crypt::decrypt($id));
      }elseif ($key=="jm") {
        $q=VisitJaminan::find(Crypt::decrypt($id));
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
        $q['param']='jm';
        $q['url']=url('jaminan-updated',Crypt::decrypt($id));
      }elseif ($key=="masters") {
        $q=TaskAssigment::with('Master','Detail')->find(Crypt::decrypt($id));
        $q['url']=route('assigments.update',Crypt::decrypt($id));
      }elseif ($key=="activity"){
        $q=ActivityAssigment::with('Task')->find(Crypt::decrypt($id));
        $q['url']=url('assigments-activity-update',$id);
        $q['asset']=MasterAssetDebitur::get();
        $q['casecategory']=MasterCaseCategory::get();
        $q['kondpekerjaan']=MasterKondisiPekerjaan::get();
        $q['nextact']=MasterNextAction::get();
      }elseif ($key=="payment"){
        $q=PaymentAssigment::with('Task')->find(Crypt::decrypt($id));
        $q['url']=url('assigments-payment-update',$id);
        $q['angs']=TaskAssigmentDetail::where('task_code',$q->task_code)->get();
        $q['key']='edit';
      }
      return view('page.tasklist.assigment.form.edit',compact('q','key'));
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
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getform(Request $request)
    {
        $input=$request->all();
        $q=TaskAssigment::where('task_code', Crypt::decrypt($input['id']))->first();
        if ($request->input('form')=='activity') {
            $q['asset']=MasterAssetDebitur::get();
            $q['casecategory']=MasterCaseCategory::get();
            $q['kondpekerjaan']=MasterKondisiPekerjaan::get();
            $q['nextact']=MasterNextAction::get();
            $q['key']='create';
        }elseif ($request->input('form')=='payment') {
          $q['angs']=TaskAssigmentDetail::where('task_code',$q->task_code)->get();
          $q['detail']=ViewTaskAssigment::where('task_code',Crypt::decrypt($input['id']))->first();
          $q['key']='create';
          // dd($q['detail']);
        }
        return view('page.tasklist.assigment.form.'.$request->input('form'), compact('q'));
    }
    public function locationmap(Request $request)
    {
        return view('page.tasklist.assigment.map');
    }
}
