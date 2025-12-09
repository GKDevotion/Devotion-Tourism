@php
    $usr = Auth::guard('admin')->user();
    // $adminMenuArr = Cache::remember('adminMenuArr', 60, function () {
    //     return getAdminSideMenu();
    // });
    $adminMenuArr = getAdminSideMenu();
 @endphp
<nav class="navbar navbar-expand-lg navbar-light bg-theme">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item px-3 d-none">
                    <a class="dropdown text-white" href="{{ route( 'admin.dashboard.index' ) }}">
                        <i class="ti-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @foreach ($adminMenuArr as $menu)
                    @if( !$menu->childArr->isEmpty() || $menu->class_name == "/")
                        <li class="hov nav-item text-white px-3">
                            <i class="{{$menu->icon}}"></i>
                            <span class="cursor-pointer text-white">{{$menu->name}}</span>
                            <i class="fa fa-caret-down"></i>
                            <ul class="setting-menu text-theme">
                                @foreach ($menu->childArr as $cmenu)
                                    {{-- @if ( true || $usr->can($cmenu->group_name.'.view') ) --}}
                                    @if( fetchSinglePermission( $usr, $cmenu->class_name, 'view') )
                                        <li class="">
                                            <a class="dropdown-item {{ Route::is('admin.'.$cmenu->group_name.'.index') || Route::is('admin.'.$cmenu->group_name.'.edit') ? 'active' : '' }}" href="{{ route('admin.'.$cmenu->group_name.'.index') }}">
                                                <i class="{{$cmenu->icon}}"></i>
                                                <span>{{$cmenu->name}}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item px-3 {{$menu->group_name}}">
                            <a class="dropdown text-white {{ Route::is('admin.'.$menu->group_name.'.index') || Route::is('admin.'.$menu->group_name.'.edit') ? 'active' : '' }}" href="{{ route('admin.'.$menu->group_name.'.index') }}">
                                <i class="{{$menu->icon}}"></i>
                                <span>{{$menu->name}}</span>
                            </a>
                        </li>
                    @endif
                @endforeach

                <li class="hov nav-item text-white">
                    <i class="fa fa-desktop"></i>
                    <span class="cursor-pointer text-white">System</span>
                    <i class="fa fa-caret-down"></i>
                    <ul class="setting-menu text-theme">
                        <li class="cursor-pointer clear-cache">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> Clear Cache
                        </li>
                        <li class="cursor-pointer d-none">
                            <i class="fa fa-lock" style="margin-right: 5px;" aria-hidden="true"></i> Lock Screen
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.change-password') }}">
                                <i class="fa fa-key" aria-hidden="true"></i> Change Pass
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.logout.submit') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out
                            </a>
                            <form id="admin-logout-form" action="{{ route('admin.logout.submit') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
