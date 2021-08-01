@include('layouts.default.header')
<body id="addFullMenuClass" class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
    <div class="page-wrapper">
        @include('layouts.default.topNavbar')
        <div class="clearfix"> </div>
        <div class="loader">
            <center>
                <img class="loading-image" src="{{asset('public/image/preloader/126.gif')}}" alt="loading..">
            </center>
        </div>
        <div class="page-container">
            @include('layouts.default.sidebar')
            <div class="page-content-wrapper">
                <div class="page-content">
                    @yield('data_count')
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        @include('layouts.default.footer')
    </div>

    <div class="quick-nav-overlay"></div>
    @include('layouts.default.footerScript')
</body>
</html>