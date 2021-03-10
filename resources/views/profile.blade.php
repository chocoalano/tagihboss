@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/switchery/switchery.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/sweetalert2/sweetalert2.css') }}">
@endsection
@section('content')
<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    @if(Auth::user()->gender == 'pria')
                    <img src="{{asset('man.png')}}" alt="" class="avatar-photo">
                    @elseif(Auth::user()->gender == 'wanita')
                    <img src="{{asset('woman.png')}}" alt="" class="avatar-photo">
                    @elseif(Auth::user()->gender == 'other')
                    <img src="{{asset('transgender.png')}}" alt="" class="avatar-photo">
                    @else
                    <img src="{{asset('user.png')}}" alt="" class="avatar-photo">
                    @endif
                </div>
                <h5 class="text-center h5 mb-0">{{ucfirst(strtolower(Auth::user()->name))}}</h5>
                <?php $roleGet=str_replace('"]','',Auth::user()->getRoleNames()); ?>
                <p class="text-center text-muted font-14">{{ str_replace('["','',$roleGet) }}</p>
                <div class="profile-info">
                    <h5 class="mb-20 h5 text-blue">Contact Information</h5>
                    <ul>
                        <li>
                            <span>Email Address:</span>
                            {{ucfirst(strtolower(Auth::user()->email))}}
                        </li>
                        <li>
                            <span>Phone/Whatsup Number:</span>
                            @if(Auth::user()->whatsup_number !== '')
                            {{ Auth::user()->whatsup_number }}
                            @else
                            {{'not found!'}}
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab" aria-selected="true">Log Activity</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-selected="false">Settings</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade active show" id="timeline" role="tabpanel">
                                <div class="pd-20">
                                    <div class="profile-timeline" url="{{ url('timeline-log') }}">
                                        
                                    </div>
                                </div>
                            </div>
                            <!-- Timeline Tab End -->
                            
                            <!-- Setting Tab start -->
                            <div class="tab-pane fade height-100-p" id="setting" role="tabpanel">
                                <div class="profile-setting">
                                    <form id="profile-setting-authentication" actions="{{ url('profile-update', Auth::user()->id) }}">
                                        <ul class="profile-edit-list row">
                                            <li class="weight-500 col-md-12">
                                                <h4 class="text-blue h5 mb-20">Edit Your Personal Setting</h4>
                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input class="form-control form-control-lg" type="text" readonly="" value="{{Auth::user()->name}}" name="name">
                                                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="name"></p></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control form-control-lg" type="email" readonly="" value="{{Auth::user()->email}}" name="email">
                                                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="email"></p></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <div class="d-flex">
                                                        <div class="custom-control custom-radio mb-5 mr-20">
                                                            <input type="radio" id="customRadio4" name="gender" value="pria" class="custom-control-input" {{ (Auth::user()->gender =='pria')? 'checked' : ''}}>
                                                            <label class="custom-control-label weight-400" for="customRadio4">Male</label>
                                                        </div>
                                                        <div class="custom-control custom-radio mb-5">
                                                            <input type="radio" id="customRadio5" name="gender" value="wanita" class="custom-control-input" {{ (Auth::user()->gender =='wanita')? 'checked' : ''}}>
                                                            <label class="custom-control-label weight-400" for="customRadio5">Female</label>
                                                        </div>
                                                        <div class="custom-control custom-radio mb-5">
                                                            <input type="radio" id="customRadio6" name="gender" value="other" class="custom-control-input" {{ (Auth::user()->gender =='other')? 'checked' : ''}}>
                                                            <label class="custom-control-label weight-400" for="customRadio6">Transgender</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="gender"></p></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone Number</label>
                                                    <input class="form-control form-control-lg" type="text" value="{{Auth::user()->whatsup_number}}" name="whatsup_number">
                                                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="whatsup_number"></p></div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox mb-5">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1-1" {{ (Auth::user()->agree_wa_notification =='y')? 'checked' : ''}} name="agree_wa_notification">
                                                        <label class="custom-control-label weight-400" for="customCheck1-1">I agree to receive notification whatsup</label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <input type="submit" class="btn btn-primary" value="Update Information">
                                                </div>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <!-- Setting Tab End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
<script src="{{ asset('src/plugins/sweetalert2/sweet-alert.init.js') }}"></script>
<script src="{{ asset('js/page/profile.js') }}"></script>
@endsection
