@php
use \App\Http\Controllers\commonController;
@endphp
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('home') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('/images/inexture-logo-icon.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('/images/inexture-logo-dark.svg') }}" alt="" height="45">
                    </span>
                </a>

                <a href="{{ route('home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('/images/inexture-logo-icon.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('/images/inexture-logo.svg') }}" alt="" height="45">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->profile_image)
                    <img class="preview-img rounded-circle header-profile-user" src="{{asset('storage/upload/user/images')}}/{{Auth::user()->profile_image}}" />
                    @else
                    <div class="avatar-xs">
                        <span class="avatar-title rounded-circle bg-primary text-white font-size-14">
                            {{ Auth::user()->profile_picture }}
                        </span>
                    </div>
                    @endif
                    <span class="d-none d-xl-inline-block ms-1 me-2" key="t-henry">{{ ucfirst(Auth::user()->first_name) }}</span>
                    <i class="fas fa-sort-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{route('user.profile')}}"><i class="fas fa-user-cog font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off font-size-16 align-middle me-1 "></i>
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
