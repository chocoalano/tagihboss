<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/core.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/icon-font.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/style.css')}}">
    </head>
    <body>
        <div class="error-page d-flex align-items-center flex-wrap justify-content-center pd-20">
            <div class="pd-10">
                <div class="error-page-wrap text-center">
                    <h1> @yield('code') </h1>
                    <h3>Error: @yield('code')</h3>
                    <p>@yield('message')</p>
                    <div class="pt-20 mx-auto max-width-200">
                        <a href="{{url('/home')}}" class="btn btn-primary btn-block btn-lg">Back To Home</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
