
    <!-- Topbar Start -->
    <div class="container-fluid py-2 border-bottom d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-decoration-none text-body pe-3" href=""><i class="bi bi-telephone me-2"></i>+012 345 6789</a>
                        <span class="text-body">|</span>
                        <a class="text-decoration-none text-body px-3" href=""><i class="bi bi-envelope me-2"></i>info@example.com</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-body px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-body px-2" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="text-body ps-2" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                       
                        @if (isset(Auth::user()->role->role) && Auth::user()->role->role == 'admin')
                            <a class="text-body ps-2" target="_blank" href="{{ route('admin.dashboard') }}">
                            Click to Dashboard
                            </a>
                        @elseif (isset(Auth::user()->role->role) && Auth::user()->role->role ==  'doctor')
                            <a class="text-body ps-2" target="_blank" href="{{ route('doctor.dashboard') }}">
                            Click to Dashboard
                            </a>
                        @else
                            <a class="text-body ps-2" target="_blank" href="{{ route('login') }}">
                                Login
                            </a>
                        @endif


                    </div>
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
                    <!-- <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-clinic-medical me-2"></i>RogiSewa</h1> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('/') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link {{ Request::is('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('doctors') }}" class="nav-item nav-link {{ Request::is('doctor') ? 'active' : '' }}">Doctor</a>
                        <a href="{{ route('hospitals') }}" class="nav-item nav-link {{ Request::is('hospital') ? 'active' : '' }}">Hostpital</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle {{ Request::is('professional.doctors') ? 'active' : '' }}" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('professional.doctors') }}" class="dropdown-item {{ Request::is('professional.doctors') ? 'active' : '' }}">Professional Doctors</a>
                                
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

