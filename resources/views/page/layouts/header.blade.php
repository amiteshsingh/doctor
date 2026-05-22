
    <!-- Topbar Start -->
    <div class="container-fluid py-2 border-bottom d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-decoration-none text-body px-3" href="mailto:rogisewa25@gmail.com">
                            <i class="bi bi-envelope me-2"></i>rogisewa25@gmail.com
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end">
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:8px;background:#000;color:#fff;border-radius:8px;padding:5px 14px;text-decoration:none;font-size:13px;font-weight:600;transition:opacity .2s;"
                       onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                            <path d="M3.18 23.76a2 2 0 001.94-.21l11.29-6.52-2.5-2.5-10.73 9.23zM.5 1.1A2 2 0 000 2.5v19a2 2 0 00.5 1.4l.07.07 10.64-10.64v-.25L.57 1.03.5 1.1zM20.32 10.37l-3.21-1.85-2.82 2.82 2.82 2.82 3.23-1.86a2 2 0 000-3.93zM5.12.45L16.41 6.97l-2.5 2.5L3.18.24A2 2 0 005.12.45z"/>
                        </svg>
                        <span>Get it on Google Play</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid sticky-top bg-white shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
                <a href="{{ route('/') }}" class="navbar-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 173px;">
                </a>

                {{-- Mobile: App button + Toggler --}}
                <div class="d-flex align-items-center gap-2 d-lg-none ms-auto">
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:5px;background:#000;color:#fff;border-radius:7px;padding:5px 10px;text-decoration:none;font-size:12px;font-weight:600;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="white">
                            <path d="M3.18 23.76a2 2 0 001.94-.21l11.29-6.52-2.5-2.5-10.73 9.23zM.5 1.1A2 2 0 000 2.5v19a2 2 0 00.5 1.4l.07.07 10.64-10.64v-.25L.57 1.03.5 1.1zM20.32 10.37l-3.21-1.85-2.82 2.82 2.82 2.82 3.23-1.86a2 2 0 000-3.93zM5.12.45L16.41 6.97l-2.5 2.5L3.18.24A2 2 0 005.12.45z"/>
                        </svg>
                        <span>Get App</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('/') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link {{ Request::is('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('doctors') }}" class="nav-item nav-link {{ Request::is('doctors') ? 'active' : '' }}">Doctor</a>
                        <a href="{{ route('hospitals') }}" class="nav-item nav-link {{ Request::is('hospitals') ? 'active' : '' }}">hospital</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle {{ Request::is('professional.doctors') ? 'active' : '' }}" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('professional.doctors') }}" class="dropdown-item {{ Request::is('professional-doctors') ? 'active' : '' }}">Professional Doctors</a>
                                <a href="{{ route('how-to-use') }}" class="dropdown-item {{ Request::is('how-to-use') ? 'active' : '' }}">How To Use</a>
                            </div>
                        </div>
                        <a href="{{ route('blog') }}" class="nav-item nav-link {{ Request::is('blog') ? 'active' : '' }}">Blog</a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link {{ Request::is('contact') ? 'active' : '' }}">Contact</a>

                        @if (isset(Auth::user()->role->role) && Auth::user()->role->role == 'admin')
                            <a class="nav-item nav-link" target="_blank" href="{{ route('admin.dashboard') }}">Click to Dashboard</a>
                        @elseif (isset(Auth::user()->role->role) && Auth::user()->role->role == 'doctor')
                            <a class="nav-item nav-link" target="_blank" href="{{ route('doctor.dashboard') }}">Click to Dashboard</a>
                        @elseif (isset(Auth::user()->role->role) && Auth::user()->role->role == 'user')
                            <a class="nav-item nav-link {{ Request::is('user/profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                                <i class="fa fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                        @else
                            <a class="nav-item nav-link" href="{{ route('choose.login') }}">Login</a>
                        @endif

                        <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                           target="_blank" rel="noopener"
                           class="nav-item nav-link d-lg-none"
                           style="color:#000;font-weight:600;">
                            <i class="fa fa-android me-1" style="color:#3ddc84;"></i> Download App
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

