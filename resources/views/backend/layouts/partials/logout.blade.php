<ul class="notification-area">
    <li class="dropdown">
        <i class="ti-bell dropdown-toggle" data-toggle="dropdown">
            <span id="get_dashboard_notification_URL" class="d-none">{{url('api/get-dashboard-notifications')}}</span>
            <span class="total-notification">0</span>
        </i>
        <div class="dropdown-menu bell-notify-box notify-box">
            <span class="notify-title">You have <span class="total-notification">0</span> new notifications <a href="#">view all</a></span>
            <div class="nofity-list"></div>
        </div>
    </li>
</ul>
<div class="pull-right">
    <nav>
        <li class="hov">
            <i class="fa fa-cog"></i>
            <span class="cursor-pointer">Setting</span>
            <i class="fa fa-caret-down"></i>
            <ul class="setting-menu">
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
    </nav>
</div>
