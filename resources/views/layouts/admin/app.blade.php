<!DOCTYPE html>
<html lang="en">
@include('layouts.admin.head')
<!--begin::Body-->
<body id="kt_body"
      class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
      style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            @include('layouts.admin.sidebar')
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper" style="padding-top: 35px;">
            <!--begin::header-->
                @include('layouts.admin.header')
                <!--end::header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    {{-- @include('layouts.admin.breadcrumb') --}}
                    @yield('breadcrumb')

                    <div class="post" id="kt_post">
                        @yield('content')
                    </div>
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-bold me-1">{{ date('Y') }}©</span>
                            <a href="#" target="_blank" class="text-gray-800 text-hover-primary">Aura Property</a>
                        </div>
                        <!--end::Copyright-->
                        <!--begin::Menu-->
                        <div class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
                            {{ config('app.name') }}
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>


        </div>
        <!--end::Wrapper-->
    </div>
<!--end::Page-->
<!--end::Root-->
{{-- .aside-dark .menu .menu-item .menu-section --}}
@include('layouts.admin.footer')
