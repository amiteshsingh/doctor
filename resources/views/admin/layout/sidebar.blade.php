<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'hospitals', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'hospitals', 'view'))
                        <li class="{{ request()->routeIs('admin.hospital') || request()->routeIs('admin.hospital.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.hospital') }}"><i class="fa fa-hospital-o"></i> <span>Hospitals</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'doctors', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'doctors', 'view'))
                        <li class="{{ request()->routeIs('admin.doctor') || request()->routeIs('admin.doctor.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.doctor') }}"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'specializations', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'specializations', 'view'))
                        <li class="{{ request()->routeIs('admin.specialization') || request()->routeIs('admin.specialization.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.specialization') }}"><i class="fa fa-hospital-o"></i> <span>Specialization</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'users', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'users', 'view'))
                        <li class="{{ request()->routeIs('admin.user') || request()->routeIs('admin.user.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.user') }}"><i class="fa fa-user"></i> <span>Users (Doctors)</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'app_users', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'app_users', 'view'))
                        <li class="{{ request()->routeIs('admin.app-users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.app-users.index') }}"><i class="fa fa-users"></i> <span>App Users</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'blogs', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'blogs', 'view'))
                        <li class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.blog.index') }}"><i class="fa fa-newspaper-o"></i> <span>Blogs</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'notifications', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'notifications', 'view'))
                        <li class="{{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.notification.index') }}"><i class="fa fa-bell"></i> <span>User Notification</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'doctor_notifications', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'doctor_notifications', 'view'))
                        <li class="{{ request()->routeIs('admin.doctor.notification.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.doctor.notification.index') }}"><i class="fa fa-bell"></i> <span>Doctor Notification</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'payment_settings', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'payment_settings', 'view'))
                        <li class="{{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.payment.settings') }}"><i class="fa fa-credit-card"></i> <span>Payment Settings</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'support_tickets', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'support_tickets', 'view'))
                        <li class="{{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.support.index') }}"><i class="fa fa-headphones"></i> <span>Support Tickets</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'pregnancy_tracker', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'pregnancy_tracker', 'view'))
                        <li class="{{ request()->routeIs('admin.pregnancy.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pregnancy.index') }}"><i class="fa fa-heart"></i> <span>Pregnancy Tracker</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'banners', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'banners', 'view'))
                        <li class="{{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.banner.index') }}"><i class="fa fa-image"></i> <span>Banners</span></a>
                        </li>
                        @endif
                        @if(\App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'testimonials', 'access') && \App\Support\AdminModules::allows((int) Auth::id(), $adminModulePermissions ?? [], 'testimonials', 'view'))
                        <li class="{{ request()->routeIs('admin.testimonial.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.testimonial.index') }}"><i class="fa fa-comments"></i> <span>Testimonials</span></a>
                        </li>
                        @endif
                        @if((int) Auth::id() === 1)
                        <li class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.permissions.index') }}"><i class="fa fa-lock"></i> <span>Admin Permissions</span></a>
                        </li>
                        @endif
                        <li class="{{ request()->routeIs('admin.edit-profile') ? 'active' : '' }}">
                            <a href="{{ route('admin.edit-profile') }}"><i class="fa fa-user-circle"></i> <span>Edit Profile</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>