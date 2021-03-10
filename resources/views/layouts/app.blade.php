<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{'TagihBoss'}}</title>
    @yield('css')
    <!-- Site favicon -->
    <link rel="apple-touch-icon" href="{{asset('ficwhite.png')}}">
    <link rel="icon" type="image/png" href="{{asset('ficwhite.png')}}">
    <link rel="icon" type="image/png" href="{{asset('ficwhite.png')}}">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/core.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/icon-font.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/style.min.css')}}">
</head>
<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo"><h1><strong>Tagih</strong>Boss</h1></div>
            <div class='loader-progress' id="progress_div">
                <div class='bar' id='bar1'></div>
            </div>
            <div class='percent' id='percent1'>0%</div>
            <div class="loading-text">
                Loading...
            </div>
        </div>
    </div>
    <div class="pre-loader" id="spinner" style="background: #ffffff85 !important;">
        <div class="pre-loader-box">
            <div class="spinner-grow" role="status">
              <span class="sr-only">Loading...</span>
          </div>
        </div>
    </div>
    @include('layouts.utility.navbar')
    @include('layouts.utility.aside')
    <!-- <div class="menu-rendered" url="{{url('get-menu')}}"></div> -->
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            @yield('content')
            <div class="footer-wrap pd-20 mb-20 card-box">
                 © Copyright {{date('Y')}} & system created. By <a href="https://kreditmandiri.co.id/" target="_blank">ITMAN & Collections Team PT. BPR Kredit Mandiri Indonesia</a> All Rights Reserved
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg-notification-show-detail-data" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow: auto !important;">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="rendered-data-bd-example-modal-lg-notification-show-detail-data"></div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendors/scripts/core.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <!-- <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script> -->
    <script type="text/javascript" src="{{asset('js/application.js')}}"></script>
    @yield('js')
</body>
</html>
