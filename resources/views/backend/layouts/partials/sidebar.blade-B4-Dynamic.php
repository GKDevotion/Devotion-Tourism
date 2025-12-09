 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard.index') }}">
                <h2 class="text-white">Admin</h2>
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                        <li class="{{ Route::is('admin.dashboard.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard.index') }}">
                                <i class="ti-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i>
                                <span>
                                    User Managements
                                </span>
                            </a>

                            <?php
                            $userMGT = [
                                'admin.admins.index',
                                'admin.admins.edit',
                                'admin.users.index',
                                'admin.users.edit',
                                'admin.customers.index',
                                'admin.customers.edit',
                                'admin.employees.index',
                                'admin.employees.edit',
                            ];
                            ?>
                            <ul class="collapse {{ in_array( Route::currentRouteName(), $userMGT ) ? 'in' : '' }}">
                            {{-- <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}"> --}}
                                @if ($usr->can('admin.view'))
                                    <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.admins.index') }}">Admins</a>
                                    </li>
                                @endif

                                @if ($usr->can('user.view'))
                                    <li class="{{ Route::is('admin.users.index')  || Route::is('admin.users.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.users.index') }}">Users</a>
                                    </li>
                                @endif

                                @if ($usr->can('customer.view'))
                                    <li class="{{ Route::is('admin.customers.index')  || Route::is('admin.customers.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.customers.index') }}">Customers</a>
                                    </li>
                                @endif

                                @if ($usr->can('employee.view'))
                                    <li class="{{ Route::is('admin.employees.index')  || Route::is('admin.employees.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.employees.index') }}">Employees</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if ($usr->can('inquiry.view'))
                        <li class="{{ Route::is('admin.inquiry.index')  || Route::is('admin.inquiry.edit') ? 'active' : '' }}">
                            <a href="{{ route('admin.inquiry.index') }}">
                                <i class="fa fa-question"></i>
                                <span>Inquiry</span>
                            </a>
                        </li>
                    @endif

                    @if ($usr->can('server-records.view') || $usr->can('device-records.view'))

                        <?php
                        $dgMGT = [
                            'admin.server-records.index',
                            'admin.server-records.edit',
                            'admin.device-records.index',
                            'admin.device-records.edit',
                        ]
                        ?>
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                DG Management
                            </span></a>
                            <ul class="collapse {{ in_array( Route::currentRouteName(), $dgMGT ) ? 'in' : '' }}">
                                @if ($usr->can('server-records.view') )
                                    <li class="{{ Route::is('admin.server-records.index')  || Route::is('admin.server-records.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.server-records.index') }}">
                                            <i class="fa fa-file"></i>
                                            <span>Server Records</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($usr->can('device-records.view') )
                                    <li class="{{ Route::is('admin.device-records.index')  || Route::is('admin.device-records.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.device-records.index') }}">
                                            <i class="fa fa-file"></i>
                                            <span>Device Records</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($usr->can('corporate-emails.view') )
                                    <li class="{{ Route::is('admin.corporate-emails.index')  || Route::is('admin.corporate-emails.edit') ? 'active' : '' }}">
                                        <a href="{{ route('admin.corporate-emails.index') }}">
                                            <i class="fa fa-file"></i>
                                            <span>Corporate Emails</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- Continent Management -->
                    <?php
                    $continentMGT = [
                        'admin.religion.index',
                        'admin.religion.edit',
                        'admin.continent.index',
                        'admin.continent.edit',
                        'admin.countrie.index',
                        'admin.countrie.edit',
                        'admin.state.index',
                        'admin.state.edit',
                        'admin.city.index',
                        'admin.city.edit',
                    ]
                    ?>
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                            Continent Management
                        </span></a>
                        <ul class="collapse {{ in_array( Route::currentRouteName(), $continentMGT ) ? 'in' : '' }}">
                            @if ($usr->can('religion.view') )
                                <li class="{{ Route::is('admin.religions.index')  || Route::is('admin.religions.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.religions.index') }}">
                                        <i class="fa fa-file"></i>
                                        <span>Religions</span>
                                    </a>
                                </li>
                            @endif

                            @if ($usr->can('continent.view') )
                                <li class="{{ Route::is('admin.continents.index')  || Route::is('admin.continents.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.continents.index') }}">
                                        <i class="fa fa-file"></i>
                                        <span>Continents</span>
                                    </a>
                                </li>
                            @endif

                            @if ($usr->can('country.view') )
                                <li class="{{ Route::is('admin.countries.index')  || Route::is('admin.countries.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.countries.index') }}">
                                        <i class="fa fa-file"></i>
                                        <span>Countries</span>
                                    </a>
                                </li>
                            @endif

                            @if ($usr->can('state.view') )
                                <li class="{{ Route::is('admin.states.index')  || Route::is('admin.states.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.states.index') }}">
                                        <i class="fa fa-file"></i>
                                        <span>States</span>
                                    </a>
                                </li>
                            @endif

                            @if ($usr->can('city.view') )
                                <li class="{{ Route::is('admin.cities.index')  || Route::is('admin.cities.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.cities.index') }}">
                                        <i class="fa fa-file"></i>
                                        <span>Cities</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                Settings
                            </span></a>
                            <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                                @if ($usr->can('role.view'))
                                    <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                                @endif
                                @if ($usr->can('permission.view'))
                                    <li class="{{ Route::is('admin.permissions.index')  || Route::is('admin.permissions.edit') ? 'active' : '' }}"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
