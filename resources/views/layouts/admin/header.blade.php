<!--begin::Header-->
<div id="kt_header" style="" class="header">
    <!--begin::Container-->
    <div class="container-fluid d-flex">
        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center" style="width: 100%">
        </div>
        <div class="d-flex align-items-end ">
            <div class="d-flex align-items-center">

                <!--begin::Search-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <!--begin::Menu- wrapper-->
                    <div
                        class="clock-container btn btn-icon btn-active-light-primary position-relative w-lg-150px w-md-150px w-sm-400 bg-light h-30px h-md-40px ms-1 ms-lg-3"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                        <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
                    </div>

                </div>
                <!--end::Search-->
                <!--begin::User-->
                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
                         data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
                         data-kt-menu-flip="bottom">
                        <div class="symbol-label fs-2 fw-bold text-success">{{strtoupper(mb_substr('bijaya',
                                0, 1))}}</div>
                    </div>
                    <!--begin::Menu-->
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label fs-2 fw-bold text-success">
                                        {{strtoupper(mb_substr('bijaya', 0, 1))}}</div>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">Change
                                Password</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">Sign Out</a>
                        </div>
                    </div>
                </div>

                <!--end::User -->
            </div>
        </div>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Container-->
<!--end::Header-->
