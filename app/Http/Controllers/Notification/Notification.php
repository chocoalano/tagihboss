<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification\NotificationTo;
use Auth;

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

class Notification extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        $x=NotificationTo::with('notifFrom')->where(['user_id'=>Auth::user()->id, 'show'=>'n']);
        $count=$x->count();
        return json_encode(['count'=>$count]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $x=NotificationTo::with('notifFrom')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->paginate(3);
        return view('notifRender',compact('x'));
    }
    public function showActivity(Request $request, $id)
    {
        NotificationTo::where(['id'=>$request->input('idNotif'),'user_id'=>Auth::user()->id])
        ->update(['show'=>'y']);
        $q=ActivityAssigment::with('Task')->where('task_code',$id)->first();
        $key="activity";
        return view('page.tasklist.assigment.form.show',compact('q','key'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPayment(Request $request, $id)
    {
        NotificationTo::where(['id'=>$request->input('idNotif'),'user_id'=>Auth::user()->id])
        ->update(['show'=>'y']);
        $q=PaymentAssigment::with('Task')->where('task_code',$id)->first();
        $key="payment";
        return view('page.tasklist.assigment.form.show',compact('q','key'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTempatTinggal(Request $request, $id)
    {
        NotificationTo::where(['id'=>$request->input('idNotif'),'user_id'=>Auth::user()->id])
        ->update(['show'=>'y']);
        $q=VisitTempatTinggal::where('task_code',$id)->first();
        $key="tt";
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
        return view('page.tasklist.assigment.form.show',compact('q','key'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showJaminan(Request $request, $id)
    {
        NotificationTo::where(['id'=>$request->input('idNotif'),'user_id'=>Auth::user()->id])
        ->update(['show'=>'y']);
        $q=VisitJaminan::where('task_code',$id)->first();
        $key="jm";
        $q['detail']=TaskAssigment::with('Master','Detail')->where('task_code', $q->task_code)->first();
        return view('page.tasklist.assigment.form.show',compact('q','key'));
    }
}
