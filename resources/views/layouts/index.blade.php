@include('common.head_portion')

<body data-sidebar="light" class style>
    @include('common.toaster')
    @if(\Illuminate\Support\Facades\Route::current()->getName() === 'user-dashboard')
    {{-- @auth()
    @include("firework-show.FireSnow")
    @endauth --}}
    @endif
    <div id="pageLoader" style="display: none;">
        <span class="mr-1 spinner-border text-primary"></span>
    </div>
    {{-- <div --}}
        {{-- class="layout-wrapper @if(\Illuminate\Support\Facades\Route::current()->getName() === 'user-dashboard') fire-work-container @endif"> --}}
        @include('common.header')
        @include('common.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-wrapper">
                    <!-- <h1>Hello Inexture !!</h1> -->
                    @yield('breadcrumbs')
                    @yield('content')
                    <!-- <div class="dashboard-top-boxes"><div class="row"><div class="col-lg-6"><div class="overflow-hidden card"><div class="bg-soft-info"><div class="row"><div class="col-7"><div class="text-black p-3"><h4 class="text-black">Leaves</h4></div></div><div class="align-self-end col-5"><img src="/static/media/leave-img.43b59e59.png" alt="" class="img-fluid"></div></div></div><div class="pt-0 card-body"><div class="row"><div class="col-sm-4"><div class="avatar-md profile-user-wid mb-2"><img src="/static/media/user2-160x160.c4da3e62.jpg" alt="" class="img-thumbnail rounded-circle"></div><h5 class="font-size-15 text-truncate">Hardipsinh Gohil</h5><p class="text-muted mb-0 text-truncate">Software Developer</p></div><div class="col-sm-8"><div class="pt-4"><div class="row"><div class="col-6"><h5 class="font-size-15">0</h5><p class="text-muted mb-0">Total Leave</p></div><div class="col-6"><h5 class="font-size-15">0</h5><p class="text-muted mb-0">This Month Leave</p></div></div><div class="text-right mt-4"><a class="btn btn-primary waves-effect waves-light btn-sm" href="/leave/my-leave">More Info<i class="mdi mdi-arrow-right ml-1"></i></a></div></div></div></div></div></div></div><div class="col-lg-6"><div class="overflow-hidden card"><div class="bg-soft-success"><div class="row"><div class="col-7"><div class="text-black p-3"><h4 class="text-black">Holidays - 2021</h4></div></div><div class="align-self-end col-5"><img src="/static/media/holiday.4f0cef69.png" alt="" class="img-fluid"></div></div></div><div class="pt-0 card-body"><div class="row"><div class="col-sm-12"><div class="pt-4"><div class="row"><div class="col-4"><h5 class="font-size-15">12</h5><p class="text-muted mb-0">Total Holidays</p></div><div class="col-4"><h5 class="font-size-15">1</h5><p class="text-muted mb-0">This Month Holidays</p></div><div class="col-4"><h5 class="font-size-15">2</h5><p class="text-muted mb-0">Next Month Holidays</p></div></div><div class="text-right mt-4"><a class="btn btn-primary waves-effect waves-light btn-sm" href="">More Info<i class="mdi mdi-arrow-right ml-1"></i></a></div></div></div></div></div></div></div></div></div> -->
                </div>
            </div>
            @include('common.footer')
        </div>
    </div>
    @include('common.script_portion')
    @stack('scripts')
    @if (Auth::user())
    <script>
        $(".select2").select2({
            width: "100%",
        });
        $(document).ajaxError(function(event, jqxhr, settings, exception) {
            if (exception == 'Unauthorized') {
                window.location = '/login';
            }
        });

        // disable datatables error prompt
        $.fn.dataTable.ext.errMode = 'none';
    </script>
    @endif
</body>

</html>
