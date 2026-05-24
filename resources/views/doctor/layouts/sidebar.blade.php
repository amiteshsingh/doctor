<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    @php
                        $myDoc = \App\Models\Doctor::where('added_by', Auth::id())->first();
                        $profileComplete = $myDoc && \Illuminate\Support\Facades\DB::table('doctor_locations')->where('doctor_id', $myDoc->id)->exists();
                        $myDoctorId = $myDoc ? $myDoc->id : null;
                        $disabledStyle = 'opacity:.4;cursor:not-allowed;pointer-events:none;filter:grayscale(1);';
                        $disabledTitle = 'Pehle apna doctor profile complete karein';
                        $__mem = \App\Models\UserDoctorRoleMembership::where('user_id', Auth::id())->first();
                    @endphp
                    <ul>
                        <li class="menu-title">Main</li>

                        <li class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('doctor.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>

                        {{-- Profile incomplete banner --}}
                        @if(!$profileComplete)
                        <li style="padding:8px 15px;">
                            <a href="{{ $myDoctorId ? url('doctor/mydoctor/add?id='.$myDoctorId) : url('doctor/mydoctor/add') }}"
                               style="display:block;background:linear-gradient(135deg,#ff6b6b,#feca57);color:#fff;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;text-decoration:none;text-align:center;">
                                <i class="fa fa-exclamation-circle"></i> Complete Profile
                            </a>
                        </li>
                        @endif

                        <li class="{{ request()->routeIs('doctor.myhospital') || request()->routeIs('doctor.myhospital.*') ? 'active' : '' }}"
                            @if(!$profileComplete) title="{{ $disabledTitle }}" @endif>
                            <a href="{{ $profileComplete ? route('doctor.myhospital') : 'javascript:void(0)' }}"
                               @if(!$profileComplete) style="{{ $disabledStyle }}" @endif>
                                <i class="fa fa-hospital-o"></i> <span>Hospitals List</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('doctor.mydoctor') || request()->routeIs('doctor.mydoctor.*') ? 'active' : '' }}">
                            <a href="{{ route('doctor.mydoctor') }}"><i class="fa fa-user-md"></i> <span>Doctors List</span></a>
                        </li>

                        {{-- Appointments (was submenu, now main menu) --}}
                        @if(!$profileComplete)
                        <li title="{{ $disabledTitle }}">
                            <a href="javascript:void(0);" style="{{ $disabledStyle }}">
                                <i class="fa fa-calendar"></i> <span>Appointments</span>
                            </a>
                        </li>
                        @elseif($__mem && $__mem->invoice_permission)
                        <li class="{{ request()->routeIs('prescription-invoice.*') ? 'active' : '' }}">
                            <a href="{{ route('prescription-invoice.index') }}">
                                <i class="fa fa-calendar"></i> <span>Appointments</span>
                            </a>
                        </li>
                        @else
                        <li title="🔒 Admin se permission lein ya Membership khareedein.">
                            <a href="javascript:void(0);" style="opacity:.45;cursor:not-allowed;pointer-events:none;filter:grayscale(1);">
                                <i class="fa fa-calendar"></i> <span><i class="fa fa-lock" style="font-size:10px;margin-right:3px;"></i>Appointments</span>
                            </a>
                        </li>
                        @endif

                        {{-- Invoice Settings (was submenu, now main menu) --}}
                        @if(!$profileComplete)
                        <li title="{{ $disabledTitle }}">
                            <a href="javascript:void(0);" style="{{ $disabledStyle }}">
                                <i class="fa fa-file-text-o"></i> <span>Invoice Settings</span>
                            </a>
                        </li>
                        @elseif($__mem && $__mem->invoice_permission)
                        <li class="{{ request()->routeIs('invoice-master.*') ? 'active' : '' }}">
                            <a href="{{ route('invoice-master.index') }}">
                                <i class="fa fa-file-text-o"></i> <span>Invoice Settings</span>
                            </a>
                        </li>
                        @else
                        <li title="🔒 Admin se permission lein ya Membership khareedein.">
                            <a href="javascript:void(0);" style="opacity:.45;cursor:not-allowed;pointer-events:none;filter:grayscale(1);">
                                <i class="fa fa-file-text-o"></i> <span><i class="fa fa-lock" style="font-size:10px;margin-right:3px;"></i>Invoice Settings</span>
                            </a>
                        </li>
                        @endif

                        <li class="{{ request()->routeIs('doctor.medicine.*') ? 'active' : '' }}"
                            @if(!$profileComplete) title="{{ $disabledTitle }}" @endif>
                            <a href="{{ $profileComplete ? route('doctor.medicine.index') : 'javascript:void(0)' }}"
                               @if(!$profileComplete) style="{{ $disabledStyle }}" @endif>
                                <i class="fa fa-pills"></i> <span>Medicine</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('doctor.staff.index') ? 'active' : '' }}"
                            @if(!$profileComplete) title="{{ $disabledTitle }}" @endif>
                            <a href="{{ $profileComplete ? route('doctor.staff.index') : 'javascript:void(0)' }}"
                               @if(!$profileComplete) style="{{ $disabledStyle }}" @endif>
                                <i class="fa fa-users"></i> <span>Staff</span>
                            </a>
                        </li>

                        @if(!$profileComplete)
                        <li title="{{ $disabledTitle }}">
                            <a href="javascript:void(0);" style="{{ $disabledStyle }}">
                                <i class="fa fa-calendar-check-o"></i> <span>Attendance</span>
                            </a>
                        </li>
                        @elseif($__mem && $__mem->attendance_permission)
                        <li class="{{ request()->routeIs('doctor.staff.attendance') || request()->routeIs('doctor.staff.attendance.*') ? 'active' : '' }}">
                            <a href="{{ route('doctor.staff.attendance') }}"><i class="fa fa-calendar-check-o"></i> <span>Attendance</span></a>
                        </li>
                        @else
                        <li title="🔒 Admin se permission lein ya Membership khareedein.">
                            <a href="javascript:void(0);" style="opacity:.45;cursor:not-allowed;pointer-events:none;filter:grayscale(1);">
                                <i class="fa fa-calendar-check-o"></i> <span><i class="fa fa-lock" style="font-size:10px;margin-right:3px;"></i>Attendance</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
