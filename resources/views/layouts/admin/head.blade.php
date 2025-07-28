<!--begin::Head-->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>@yield('title') - {{config("app.name")}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="canonical" href="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:image:type" content="image/jpeg">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Global Stylesheets Bundle-->

    @yield('page-specific-styles')

    <style>
        .clock {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            font-size: 9px;
            letter-spacing: 4px;
        }

        .clock-container {
            cursor: default !important;
        }
    </style>
    @if (env("APP_ENV") == 'local')
        @vite('resources/js/app.js')
    @endif
</head>
<!--end::Head-->
