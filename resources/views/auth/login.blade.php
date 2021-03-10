@extends('layouts.auth')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/switchery/switchery.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/sweetalert2/sweetalert2.css') }}">
@endsection
@section('content')
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <img src="vendors/images/login-page-img.png" alt="">
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h2 class="text-center text-primary">{{ __('Login') }}</h2>
                    </div>
                    <form action="{{ url('sign-in') }}" method="post" id="form-login">
                        <div class="input-group custom">
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Username" required autocomplete="off" autofocus name="email">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>
                        <div class="input-group custom">
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="**********" name="password">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>
                        <div class="row pb-30">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Remember</label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                            <div class="col-6">
                                <div class="forgot-password">
                                    <a href="{{ route('password.request') }}">Forgot Password</a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                                </div>
                                <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
                                <div class="input-group mb-0">
                                    <button type="button" class="btn btn-outline-primary btn-lg btn-block auth-from-micro">
                                        Sync To Micro BPR Online
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
<script src="{{ asset('src/plugins/sweetalert2/sweet-alert.init.js') }}"></script>
<script type="text/javascript">
    $("#form-login").submit(function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const data ={
            param:'auth',
            post:{
                'email':$('input[name=email]').val(),
                'password':$('input[name=password]').val()
            }
        };
        $.post($(this).attr('action'),data,function(response){
            var res=JSON.parse(response);
            swal({
                    text: res['msg'],
                    type: res['status'],
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Confirmed',
                }).then(function () {
                window.location.href = res['url'];
            })
        })
        e.preventDefault();
    })
    $(".auth-from-micro").click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const data ={
            param:'auth-micro',
            post:{
                'email':$('input[name=email]').val(),
                'password':$('input[name=password]').val()
            }
        };
        $.post($(this).attr('action'),data,function(response){
            console.log(response);
            var res=JSON.parse(response);
            swal({
                    text: res['msg'],
                    type: res['status'],
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Confirmed',
                }).then(function () {
                window.location.href = res['url'];
            })
        })
    })
</script>
@endsection