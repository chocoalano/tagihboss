@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
            <img src="vendors/images/banner-img.png" alt="">
        </div>
        <div class="col-md-8">
            <h4 class="font-20 weight-500 mb-10 text-capitalize">
                Welcome back <div class="weight-600 font-30 text-blue">Mr/Mrs.{{ ucfirst(strtolower(Auth::user()->name)) }}!</div>
            </h4>
            <p class="font-18 max-width-600">{{ $info->title }}</p>
            <p class="font-12 max-width-600"><?php echo $info->information; ?></p>
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
            <div class="chat-list bg-light-gray">
                <div class="chat-search">
                    <h3>Users Online</h3>
                </div>
                <div class="notification-list chat-notification-list customscroll mCustomScrollbar _mCS_4"><div id="mCSB_4" class="mCustomScrollBox mCS-dark-2 mCSB_vertical mCSB_inside" tabindex="0" style="max-height: none;"><div id="mCSB_4_container" class="mCSB_container" style="position: relative; top: 0px; left: 0px;" dir="ltr">
                    <ul>
                        @php $users = DB::table('users')->get(); @endphp
                        @foreach($users as $user)
                        <li>
                            <a href="#">
                                @if($user->gender == 'other')
                                <img src="{{asset('user.png')}}" alt="" class="mCS_img_loaded">
                                @elseif($user->gender == 'pria')
                                <img src="{{asset('man.png')}}" alt="" class="mCS_img_loaded">
                                @elseif($user->gender == 'wanita')
                                <img src="{{asset('woman.png')}}" alt="" class="mCS_img_loaded">
                                @else
                                <img src="{{asset('transgender.png')}}" alt="" class="mCS_img_loaded">
                                @endif
                                <h3 class="clearfix">{{ucfirst(strtolower($user->name))}}</h3>
                                @if(Cache::has('user-is-online-' . $user->id))
                                <p><i class="fa fa-circle text-light-green"></i> online</p>
                                @else
                                <p><i class="fa fa-circle text-light-orange"></i> offline</p>
                                @endif
                                
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div><div id="mCSB_4_scrollbar_vertical" class="mCSB_scrollTools mCSB_4_scrollbar mCS-dark-2 mCSB_scrollTools_vertical mCSB_scrollTools_onDrag_expand" style="display: block;"><div class="mCSB_draggerContainer"><div id="mCSB_4_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; display: block; height: 29px; max-height: 152.625px; top: 0px;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="chat-detail" url="{{url('home-task-assigment')}}">
                <div class="data-task"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- <script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script> -->
<script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
<!-- <script src="{{ asset('vendors/scripts/dashboard.js') }}"></script> -->
<script src="{{ asset('js/page/home.js') }}"></script>
@endsection
