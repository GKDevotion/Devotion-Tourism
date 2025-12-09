 <!-- sidebar menu area start -->
 @php
    $usr = Auth::guard('admin')->user();
    $adminMenuArr = Cache::remember('adminMenuArr', 60, function () {
        return getAdminSideMenu();
    });
 @endphp

<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{route('admin.dashboard.index')}}">
                <!-- <h2 class="text-white">Admin</h2> -->
                 <img src="{{url(getConfigurationfield('APP_LOGO'))}}" />
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    @foreach ($adminMenuArr as $menu)

                        @if( $menu->childArr->isEmpty() || $menu->class_name != "/")
                            @if ( $usr->can( $menu->group_name.'.view'))
                                <li class="{{ Route::is( 'admin.dashboard.index' ) ? 'active' : '' }}">
                                    <a href="{{ route( 'admin.dashboard.index' ) }}">
                                        <i class="ti-dashboard"></i>
                                        <span>{{$menu->name}}</span>
                                    </a>
                                </li>
                            @endif
                        @elseif( !$menu->childArr->isEmpty() || $menu->class_name == "/")

                            @php
                                $parentArr = [];

                                foreach ($menu->childArr as $cmenu){
                                    $parents = [
                                        'admin.'.$cmenu->group_name.'.index',
                                        'admin.'.$cmenu->group_name.'.view',
                                        'admin.'.$cmenu->group_name.'.edit',
                                        'admin.'.$cmenu->group_name.'.create'
                                    ];

                                    $parentArr[] = $parents;
                                }

                                $isActive = "";
                                if( in_array( Route::currentRouteName(), $parentArr ) ){
                                    $isActive = "active";
                                }
                            @endphp

                            <li class="{{$menu->slug}}">
                                <a href="javascript:void(0)" aria-expanded="true">
                                    <i class="{{$menu->icon}}"></i>
                                    <span> {{$menu->name}} </span>
                                </a>

                                <ul class="collapse {{Route::currentRouteName()}}">
                                    <?php
                                    $count = 0;
                                    ?>
                                    @foreach ($menu->childArr as $cmenu)
                                         @if (true || $usr->can($cmenu->group_name.'.view'))
                                            <li class="{{ Route::is('admin.'.$cmenu->group_name.'.index') || Route::is('admin.'.$cmenu->group_name.'.edit') ? 'active' : '' }}">
                                                <a href="{{ route('admin.'.$cmenu->group_name.'.index') }}">
                                                    <i class="{{$cmenu->icon}}"></i>
                                                    <span>{{$cmenu->name}}</span>
                                                </a>
                                            </li>
                                            <?php
                                                $count++;
                                            ?>
                                        @endif
                                    @endforeach

                                    <?php
                                        if( $count == 0 ){
                                            echo '<script>$(".'.$menu->slug.'").remove();</script>';
                                        }
                                    ?>
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->

<script>
    $(document).ready(function() {
        // Find the active <li>
        var $activeLi = $("li.active");

        if ($activeLi.length) {
            // Get the parent <ul> and add a class
            var $parentUl = $activeLi.closest("ul");
            $parentUl.addClass("in");

            // Get the parent of <ul> (if exists) and add class
            var $parentElement = $parentUl.parent();
            if ($parentElement.length) {
                $parentElement.addClass("active");
            }
        }
    });
</script>
