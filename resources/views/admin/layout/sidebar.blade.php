<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.hospital') || request()->routeIs('admin.hospital.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.hospital') }}"><i class="fa fa-hospital-o"></i> <span>Hospitals</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.doctor') || request()->routeIs('admin.doctor.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.doctor') }}"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.specialization') || request()->routeIs('admin.specialization.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.specialization') }}"><i class="fa fa-hospital-o"></i> <span>Specialization</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.user') || request()->routeIs('admin.user.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.user') }}"><i class="fa fa-user"></i> <span>Users (Doctors)</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.app-users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.app-users.index') }}"><i class="fa fa-users"></i> <span>App Users</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.blog.index') }}"><i class="fa fa-newspaper-o"></i> <span>Blogs</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.notification.index') }}"><i class="fa fa-bell"></i> <span>User Notification</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.doctor.notification.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.doctor.notification.index') }}"><i class="fa fa-bell"></i> <span>Doctor Notification</span></a>
                        </li>
                        <li class="{{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.payment.settings') }}"><i class="fa fa-credit-card"></i> <span>Payment Settings</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>