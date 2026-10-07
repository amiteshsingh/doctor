@extends('page.layouts.app')

@section('title', 'RogiSewa - Find Doctors & Hospitals Near You | Book Appointments Online')

@section('content')

{{-- Schema Markup for SEO --}}
@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MedicalOrganization",
  "name": "RogiSewa",
  "url": "https://rogisewa.com",
  "description": "RogiSewa helps patients find verified doctors and hospitals across India. Search by specialization, city, and book appointments easily.",
  "areaServed": "India",
  "serviceType": "Healthcare Discovery Platform"
}
</script>
@endverbatim

<style>
/* ── HERO ANIMATED ── */
.rs-hero {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #0a2463 0%, #1565c0 40%, #0d47a1 70%, #1a237e 100%);
    overflow: hidden;
    display: flex;
    align-items: center;
}
.rs-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.rs-hero .floating-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    animation: floatUp 8s ease-in-out infinite;
}
.rs-hero .floating-circle:nth-child(1) { width:300px;height:300px;top:-80px;right:-60px;animation-delay:0s; }
.rs-hero .floating-circle:nth-child(2) { width:200px;height:200px;bottom:10%;left:-50px;animation-delay:2s; }
.rs-hero .floating-circle:nth-child(3) { width:150px;height:150px;top:30%;right:15%;animation-delay:4s; }
.rs-hero .floating-circle:nth-child(4) { width:80px;height:80px;top:60%;right:30%;animation-delay:1s; }
@keyframes floatUp {
    0%,100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-30px) scale(1.05); }
}
.rs-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 8px 20px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 20px;
    animation: fadeInDown 0.8s ease both;
}
.rs-hero h1 {
    font-size: clamp(2rem, 5vw, 3.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    animation: fadeInUp 0.9s ease 0.2s both;
}
.rs-hero h1 span { color: #64b5f6; }
.rs-hero p {
    color: rgba(255,255,255,0.85);
    font-size: 17px;
    animation: fadeInUp 0.9s ease 0.4s both;
}
.rs-hero-btns { animation: fadeInUp 0.9s ease 0.6s both; }
.rs-hero-btns .btn-hero-primary {
    background: #fff;
    color: #1565c0;
    border: none;
    border-radius: 50px;
    padding: 14px 36px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}
.rs-hero-btns .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(0,0,0,0.3); }
.rs-hero-btns .btn-hero-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.6);
    border-radius: 50px;
    padding: 14px 36px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s;
}
.rs-hero-btns .btn-hero-outline:hover { background: rgba(255,255,255,0.15); transform: translateY(-3px); }

/* Pulse ring on hero icon */
.hero-icon-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    animation: fadeInRight 1s ease 0.3s both;
}
.hero-icon-wrap .pulse-ring {
    position: absolute;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    animation: pulseRing 2.5s ease-out infinite;
}
.hero-icon-wrap .pulse-ring:nth-child(2) { animation-delay: 0.8s; }
.hero-icon-wrap .pulse-ring:nth-child(3) { animation-delay: 1.6s; }
@keyframes pulseRing {
    0% { width:80px;height:80px;opacity:1; }
    100% { width:200px;height:200px;opacity:0; }
}
.hero-card-float {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 16px;
    padding: 14px 20px;
    color: #fff;
    animation: floatCard 4s ease-in-out infinite;
}
.hero-card-float:nth-child(2) { animation-delay: 1.5s; }
@keyframes floatCard {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* ── STATS ANIMATED ── */
.rs-stats-bar {
    background: #fff;
    box-shadow: 0 -4px 30px rgba(0,0,0,0.08);
    position: relative;
    z-index: 10;
}
.rs-stat-item {
    padding: 30px 20px;
    text-align: center;
    border-right: 1px solid #f0f0f0;
    transition: all 0.3s;
}
.rs-stat-item:last-child { border-right: none; }
.rs-stat-item:hover { background: #f8fbff; }
.rs-stat-item .stat-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 10px;
}
.rs-stat-item h3 {
    font-size: 2rem;
    font-weight: 800;
    color: #1565c0;
    margin: 0;
}
.rs-stat-item p { color: #666; font-size: 13px; margin: 0; }

/* ── SECTION ANIMATIONS ── */
.rs-section-tag {
    display: inline-block;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1565c0;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 6px 18px;
    border-radius: 50px;
    margin-bottom: 12px;
}
.rs-section-title {
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1.3;
}

/* ── HOW IT WORKS ── */
.rs-step-card {
    background: #fff;
    border-radius: 20px;
    padding: 36px 28px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}
.rs-step-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1565c0, #42a5f5);
    transform: scaleX(0);
    transition: transform 0.4s;
}
.rs-step-card:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(21,101,192,0.15); }
.rs-step-card:hover::before { transform: scaleX(1); }
.rs-step-icon {
    width: 72px; height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #1565c0, #42a5f5);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 8px 20px rgba(21,101,192,0.3);
    transition: transform 0.3s;
}
.rs-step-card:hover .rs-step-icon { transform: rotate(10deg) scale(1.1); }
.rs-step-num {
    position: absolute;
    top: 16px; right: 20px;
    font-size: 60px;
    font-weight: 900;
    color: rgba(21,101,192,0.06);
    line-height: 1;
}

/* ── FEATURE CARDS ── */
.rs-feature-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    transition: all 0.3s;
    border: 1px solid #f0f4ff;
    display: flex;
    gap: 18px;
    align-items: flex-start;
}
.rs-feature-card:hover { transform: translateY(-6px); box-shadow: 0 15px 40px rgba(21,101,192,0.12); border-color: #bbdefb; }
.rs-feature-icon {
    width: 50px; height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* ── DOCTOR CARDS ── */
.rs-doctor-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    transition: all 0.4s;
}
.rs-doctor-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.12); }
.rs-doctor-card img { transition: transform 0.5s; }
.rs-doctor-card:hover img { transform: scale(1.05); }

/* ── APP SECTION ── */
.rs-app-section {
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    position: relative;
    overflow: hidden;
}
.rs-app-section::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(100,181,246,0.1) 0%, transparent 70%);
    top: -100px; right: -100px;
    animation: floatUp 10s ease-in-out infinite;
}
.rs-app-card {
    background: rgba(255,255,255,0.07);
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 28px;
    transition: all 0.3s;
}
.rs-app-card:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); transform: translateY(-5px); }

/* ── CTA SECTION ── */
.rs-cta {
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    position: relative;
    overflow: hidden;
}
.rs-cta::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    bottom: -150px; right: -100px;
}

/* ── SCROLL ANIMATIONS ── */
.rs-animate {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.rs-animate.rs-visible {
    opacity: 1;
    transform: translateY(0);
}
.rs-animate-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.rs-animate-left.rs-visible { opacity: 1; transform: translateX(0); }
.rs-animate-right {
    opacity: 0;
    transform: translateX(40px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.rs-animate-right.rs-visible { opacity: 1; transform: translateX(0); }

/* Stagger delays */
.rs-delay-1 { transition-delay: 0.1s; }
.rs-delay-2 { transition-delay: 0.2s; }
.rs-delay-3 { transition-delay: 0.3s; }
.rs-delay-4 { transition-delay: 0.4s; }

@keyframes fadeInDown {
    from { opacity:0; transform:translateY(-20px); }
    to { opacity:1; transform:translateY(0); }
}
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(30px); }
    to { opacity:1; transform:translateY(0); }
}
@keyframes fadeInRight {
    from { opacity:0; transform:translateX(40px); }
    to { opacity:1; transform:translateX(0); }
}

/* Typing cursor */
.rs-typed::after {
    content: '|';
    animation: blink 0.8s infinite;
    color: #64b5f6;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }

/* Gradient text */
.rs-gradient-text {
    background: linear-gradient(135deg, #64b5f6, #e1f5fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Specialization pill hover */
.rs-spec-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: #f0f7ff;
    border-radius: 12px;
    color: #1565c0;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s;
    border: 1px solid transparent;
}
.rs-spec-pill:hover {
    background: #1565c0;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(21,101,192,0.25);
}

/* Scrolling ticker */
.rs-ticker-wrap { overflow: hidden; background: #1565c0; padding: 10px 0; }
.rs-ticker {
    display: flex;
    gap: 60px;
    animation: ticker 25s linear infinite;
    white-space: nowrap;
}
.rs-ticker span { color: #fff; font-size: 13px; font-weight: 600; }
.rs-ticker span i { margin-right: 6px; color: #90caf9; }
@keyframes ticker {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Hero features auto-scroll */
.hero-features-scroll {
    animation: featScroll 18s linear infinite;
}
.hero-features-scroll:hover { animation-play-state: paused; }
@keyframes featScroll {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-50%); }
}
</style>

<!-- ── HERO ── -->
<section class="rs-hero">
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>

    <div class="container py-5" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="rs-hero-badge">
                    <span style="width:8px;height:8px;background:#4caf50;border-radius:50%;display:inline-block;animation:blink 1s infinite;"></span>
                    India's Trusted Healthcare Platform
                </div>
                <h1>Find <span class="rs-gradient-text">Trusted Doctors</span><br>&amp; Hospitals Near You</h1>
                <p class="my-4">Search verified doctors by specialization, compare clinics, and book appointments — all in one place. Serving patients across India.</p>
                <div class="rs-hero-btns d-flex flex-wrap gap-3">
                    <a href="{{ url('doctors') }}" class="btn-hero-primary">
                        <i class="fa fa-search me-2"></i> Find Doctor
                    </a>
                    <a href="{{ url('hospitals') }}" class="btn-hero-outline">
                        <i class="fa fa-hospital-o me-2"></i> Find Hospital
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4" style="animation:fadeInUp 0.9s ease 0.8s both;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-check-circle" style="color:#4caf50;font-size:18px;"></i>
                        <span style="color:rgba(255,255,255,.85);font-size:14px;">Verified Profiles</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-check-circle" style="color:#4caf50;font-size:18px;"></i>
                        <span style="color:rgba(255,255,255,.85);font-size:14px;">Free Prescription</span>
                    </div>

                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex flex-column gap-3">
                <!-- Stats row -->
                <div class="d-flex gap-3">
                    <div class="hero-card-float" style="flex:1;">
                        <div style="font-size:11px;color:rgba(255,255,255,.6);margin-bottom:4px;">DOCTORS</div>
                        <div style="font-size:22px;font-weight:800;color:#fff;">{{ $totalDoctors }}+</div>
                        <div style="font-size:12px;color:rgba(255,255,255,.7);">Verified Experts</div>
                    </div>
                    <div class="hero-card-float" style="flex:1;">
                        <div style="font-size:11px;color:rgba(255,255,255,.6);margin-bottom:4px;">HOSPITALS</div>
                        <div style="font-size:22px;font-weight:800;color:#fff;">{{ $totalHospitals }}+</div>
                        <div style="font-size:12px;color:rgba(255,255,255,.7);">Across India</div>
                    </div>
                </div>

                <!-- App features scrolling card -->
                <div class="hero-card-float w-100" style="padding:16px 18px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fa fa-mobile" style="color:#4caf50;font-size:18px;"></i>
                        <span style="color:#fff;font-weight:700;font-size:14px;">RogiSewa App Features</span>
                        <span style="margin-left:auto;background:#4caf50;color:#fff;border-radius:20px;padding:2px 10px;font-size:10px;font-weight:700;">LIVE</span>
                    </div>
                    <!-- Scrolling features list -->
                    <div style="height:180px;overflow:hidden;position:relative;">
                        <div class="hero-features-scroll">
                            @foreach([
                                ['📚','#1565c0','Disease Library','55+ बीमारियां, लक्षण व इलाज'],
                                ['🧠','#6a1b9a','BrainFit','दिमाग तेज़ करें'],
                                ['🔥','#bf360c','Calorie Burn','वजन घटाने में मदद'],
                                ['💉','#2e7d32','Vaccine Tracker','टीकाकरण रिकॉर्ड'],
                                ['🤱','#ad1457','Pregnancy Tracker','गर्भावस्था देखभाल'],
                                ['🌸','#c62828','Period Tracker','मासिक धर्म ट्रैक'],
                                ['👶','#e65100','Baby Growth','बच्चे का विकास'],
                                ['💊','#00695c','Medicine Info','दवाई रिमाइंडर'],
                                ['🥗','#558b2f','Food AI','AI डाइट प्लान'],
                                ['🤖','#37474f','Sehat AI','AI स्वास्थ्य सलाह'],
                                ['❤️','#b71c1c','Body Guide','शरीर की जानकारी'],
                                ['💧','#0277bd','Water Reminder','पानी पीने की याद'],
                                ['👁️','#4a148c','Eye Test','आँखों की देखभाल'],
                                ['👨‍⚕️','#1565c0','Find Doctors','नज़दीकी डॉक्टर खोजें'],
                                ['🏥','#00838f','Find Hospitals','हॉस्पिटल खोजें'],
                                ['📅','#4facfe','Appointments','अपॉइंटमेंट बुक करें'],
                                ['📋','#f59e0b','Prescription','प्रिस्क्रिप्शन इनवॉइस'],
                                ['👥','#a18cd1','Staff Tracking','स्टाफ अटेंडेंस'],
                            ] as $feat)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:32px;height:32px;background:{{ $feat[1] }};border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">{{ $feat[0] }}</div>
                                <div>
                                    <div style="color:#fff;font-size:12px;font-weight:700;line-height:1.2;">{{ $feat[2] }}</div>
                                    <div style="color:rgba(255,255,255,.5);font-size:10px;">{{ $feat[3] }}</div>
                                </div>
                                <i class="fa fa-check-circle ms-auto" style="color:#4caf50;font-size:12px;"></i>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Download buttons -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank"
                           style="flex:1;background:#4caf50;color:#fff;border-radius:8px;padding:7px 10px;font-size:11px;font-weight:700;text-decoration:none;text-align:center;">
                            <i class="fa fa-android me-1"></i> Patient App
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                           style="flex:1;background:#1565c0;color:#fff;border-radius:8px;padding:7px 10px;font-size:11px;font-weight:700;text-decoration:none;text-align:center;">
                            <i class="fa fa-android me-1"></i> Doctor App
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── TICKER ── -->
<div class="rs-ticker-wrap">
    <div class="rs-ticker">
        <span><i class="fa fa-user-md"></i> Find Verified Doctors</span>
        <span><i class="fa fa-hospital-o"></i> Trusted Hospitals</span>
        <span><i class="fa fa-calendar-check-o"></i> Easy Appointment Booking</span>
        <span><i class="fa fa-file-text"></i> Free Prescription Invoice</span>
        <span><i class="fa fa-mobile"></i> Download RogiSewa App</span>
        <span><i class="fa fa-shield"></i> 100% Verified Profiles</span>
        <!-- duplicate for seamless loop -->
        <span><i class="fa fa-user-md"></i> Find Verified Doctors</span>
        <span><i class="fa fa-hospital-o"></i> Trusted Hospitals</span>
        <span><i class="fa fa-calendar-check-o"></i> Easy Appointment Booking</span>
        <span><i class="fa fa-file-text"></i> Free Prescription Invoice</span>
        <span><i class="fa fa-mobile"></i> Download RogiSewa App</span>
        <span><i class="fa fa-shield"></i> 100% Verified Profiles</span>
    </div>
</div>

<!-- ── STATS BAR ── -->
<div class="rs-stats-bar">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3 rs-stat-item rs-animate rs-delay-1">
                <div class="stat-icon" style="background:#e3f2fd;"><i class="fa fa-user-md" style="color:#1565c0;"></i></div>
                <h3 class="rs-counter" data-target="{{ $totalDoctors }}">0</h3>
                <p>Verified Doctors</p>
            </div>
            <div class="col-6 col-md-3 rs-stat-item rs-animate rs-delay-2">
                <div class="stat-icon" style="background:#e8f5e9;"><i class="fa fa-hospital-o" style="color:#2e7d32;"></i></div>
                <h3 class="rs-counter" data-target="{{ $totalHospitals }}">0</h3>
                <p>Hospitals &amp; Clinics</p>
            </div>
            <div class="col-6 col-md-3 rs-stat-item rs-animate rs-delay-3">
                <div class="stat-icon" style="background:#fff3e0;"><i class="fa fa-stethoscope" style="color:#e65100;"></i></div>
                <h3 class="rs-counter" data-target="{{ $totalSpecializations }}">0</h3>
                <p>Specializations</p>
            </div>
            <div class="col-6 col-md-3 rs-stat-item rs-animate rs-delay-4">
                <div class="stat-icon" style="background:#fce4ec;"><i class="fa fa-file-text" style="color:#c62828;"></i></div>
                <h3>Free</h3>
                <p>Prescription Invoice</p>
            </div>
        </div>
    </div>
</div>

<!-- ── HOW IT WORKS ── -->
<section class="py-5" style="background:#f8fbff;">
    <div class="container">
        <div class="text-center mb-5 rs-animate">
            <div class="rs-section-tag">Simple Steps</div>
            <h2 class="rs-section-title">How RogiSewa Works</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4 rs-animate rs-delay-1">
                <div class="rs-step-card">
                    <div class="rs-step-num">1</div>
                    <div class="rs-step-icon"><i class="fa fa-search"></i></div>
                    <h5 class="fw-bold mb-2">Search</h5>
                    <p class="text-muted" style="font-size:14px;">Search doctors by name, specialization, or city. Filter results to find the right healthcare professional.</p>
                </div>
            </div>
            <div class="col-md-4 rs-animate rs-delay-2">
                <div class="rs-step-card">
                    <div class="rs-step-num">2</div>
                    <div class="rs-step-icon"><i class="fa fa-user-md"></i></div>
                    <h5 class="fw-bold mb-2">Compare Profiles</h5>
                    <p class="text-muted" style="font-size:14px;">View detailed doctor profiles including qualifications, experience, clinic location, and specializations.</p>
                </div>
            </div>
            <div class="col-md-4 rs-animate rs-delay-3">
                <div class="rs-step-card">
                    <div class="rs-step-num">3</div>
                    <div class="rs-step-icon"><i class="fa fa-calendar-check-o"></i></div>
                    <h5 class="fw-bold mb-2">Book Appointment</h5>
                    <p class="text-muted" style="font-size:14px;">Contact the doctor directly or book an appointment through the platform. Get care without delays.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── DOCTORS CAROUSEL ── -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5 rs-animate">
            <div class="rs-section-tag">Our Doctors</div>
            <h2 class="rs-section-title">Meet Our Qualified Healthcare Professionals</h2>
        </div>
        <div class="owl-carousel team-carousel position-relative">
            @foreach($doctors as $doctor)
                @php $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name; @endphp
                <div class="rs-doctor-card">
                    <div style="height:280px;overflow:hidden;background:#f0f4f8;display:flex;align-items:center;justify-content:center;">
                        <img class="w-100 h-100"
                             src="{{ $doctor->profile_pic ? asset('storage/upload/doctor/'.$doctor->profile_pic) : asset('storage/upload/doctor/user.jpg') }}"
                             alt="Dr. {{ $practiceName }}"
                             style="object-fit:contain;object-position:center;image-rendering:auto;">
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold mb-1">{{ $practiceName }}</h6>
                        <p class="text-primary mb-1" style="font-size:13px;">{{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}</p>
                        <p class="text-muted mb-2" style="font-size:12px;">{{ $doctor->educations->first()->degree ?? 'Healthcare Professional' }}</p>
                        @php $location = $doctor->locations->first(); @endphp
                        <p class="text-muted mb-3" style="font-size:12px;">
                            <i class="fa fa-map-marker text-primary me-1"></i>
                            {{ $location ? $location->city.', '.$location->state : 'India' }}
                        </p>
                        <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}"
                           class="btn btn-primary btn-sm rounded-pill w-100">View Profile</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4 rs-animate">
            <a href="{{ route('professional.doctors') }}" class="btn btn-outline-primary rounded-pill px-5">
                View All Doctors <i class="fa fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ── ROGISEWA APP SECTION ── -->
<div class="rs-app-section py-5">
    <div class="container">

        <!-- Section Heading -->
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-4 py-2 mb-3" style="background:rgba(255,255,255,.15);color:#fff;font-size:13px;letter-spacing:1px;">📱 MOBILE APP</span>
            <h2 class="text-white fw-bold" style="font-size:2rem;">RogiSewa — Ab Haath Mein!</h2>
            <p class="text-white mt-2" style="opacity:.75;max-width:600px;margin:0 auto;font-size:15px;">
                Separate app for Doctors, separate app for Patients — both available on Google Play.
            </p>
        </div>

        <div class="row g-4">

            <!-- Doctor App Card -->
            <div class="col-lg-6">
                <div class="rounded-4 p-4 h-100" style="background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.15);">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,#00b074,#38f9d7);font-size:26px;">
                            <i class="fa fa-user-md text-white"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size:18px;">RogiSewa Doctor App</div>
                            <div style="color:rgba(255,255,255,.6);font-size:13px;">Doctors & Clinics ke liye</div>
                        </div>
                        <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                           class="ms-auto btn btn-sm fw-bold"
                           style="background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border:none;border-radius:10px;white-space:nowrap;">
                            <i class="fa fa-android me-1"></i> Download
                        </a>
                    </div>

                    <!-- Features -->
                    <div class="row g-2 mb-4">
                        @foreach([
                            ['icon'=>'fa-id-card',         'color'=>'#667eea', 'en'=>'Doctor Profile Listing',       'hi'=>'डॉक्टर प्रोफ़ाइल लिस्टिंग'],
                            ['icon'=>'fa-hospital-o',      'color'=>'#f5576c', 'en'=>'Hospital / Clinic Management',  'hi'=>'हॉस्पिटल / क्लिनिक मैनेजमेंट'],
                            ['icon'=>'fa-calendar-check-o','color'=>'#4facfe', 'en'=>'Appointment Management',       'hi'=>'अपॉइंटमेंट मैनेजमेंट'],
                            ['icon'=>'fa-file-text',       'color'=>'#f59e0b', 'en'=>'Prescription Invoice (Free)',   'hi'=>'प्रिस्क्रिप्शन इनवॉइस (फ्री)'],
                            ['icon'=>'fa-medkit',          'color'=>'#00b074', 'en'=>'Medicine Management',          'hi'=>'दवाई मैनेजमेंट'],
                            ['icon'=>'fa-users',           'color'=>'#a18cd1', 'en'=>'Staff & Attendance Tracking',  'hi'=>'स्टाफ व अटेंडेंस ट्रैकिंग'],
                        ] as $f)
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 rounded-3 px-3 py-2" style="background:rgba(255,255,255,.06);">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:34px;height:34px;background:{{ $f['color'] }}22;">
                                    <i class="fa {{ $f['icon'] }}" style="color:{{ $f['color'] }};font-size:14px;"></i>
                                </div>
                                <div style="flex:1;">
                                    <div class="text-white" style="font-size:13px;font-weight:600;">{{ $f['en'] }}</div>
                                    <div style="color:rgba(255,255,255,.5);font-size:11px;">{{ $f['hi'] }}</div>
                                </div>
                                <i class="fa fa-check-circle" style="color:#00b074;font-size:14px;"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- How to Use Steps -->
                    <div class="rounded-3 p-3 mb-3" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);">
                        <div class="text-white fw-bold mb-3" style="font-size:13px;">📋 How to Use / कैसे उपयोग करें</div>
                        @foreach([
                            ['en'=>'Download & install from Google Play',       'hi'=>'Google Play से डाउनलोड करें'],
                            ['en'=>'Login with your registered email',           'hi'=>'अपने ईमेल से लॉगिन करें'],
                            ['en'=>'Complete your Doctor Profile',               'hi'=>'डॉक्टर प्रोफ़ाइल पूरी करें'],
                            ['en'=>'Add your Hospital / Clinic details',         'hi'=>'हॉस्पिटल / क्लिनिक जोड़ें'],
                            ['en'=>'Manage appointments & create invoices',      'hi'=>'अपॉइंटमेंट व इनवॉइस मैनेज करें'],
                        ] as $i => $step)
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                  style="width:22px;height:22px;background:#00b074;color:#fff;font-size:10px;">{{ $i+1 }}</span>
                            <div>
                                <div class="text-white" style="font-size:12px;">{{ $step['en'] }}</div>
                                <div style="color:rgba(255,255,255,.5);font-size:11px;">{{ $step['hi'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                       class="btn w-100 fw-bold py-2"
                       style="background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border:none;border-radius:12px;">
                        <i class="fa fa-android me-2" style="font-size:16px;"></i>
                        Download Doctor App on Google Play
                    </a>
                </div>
            </div>

            <!-- Patient App Card -->
            <div class="col-lg-6">
                <div class="rounded-4 p-4 h-100" style="background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.15);">

                    <!-- Header -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,#1a73e8,#0d47a1);font-size:26px;">
                            <i class="fa fa-heartbeat text-white"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size:18px;">RogiSewa Patient App</div>
                            <div style="color:rgba(255,255,255,.6);font-size:12px;">आपके और आपके परिवार की सेहत का भरोसेमंद साथी &mdash; Health Made Easy</div>
                        </div>
                        <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank"
                           class="ms-auto btn btn-sm fw-bold"
                           style="background:linear-gradient(135deg,#1a73e8,#0d47a1);color:#fff;border:none;border-radius:10px;white-space:nowrap;">
                            <i class="fa fa-android me-1"></i> Download
                        </a>
                    </div>

                    <!-- Tagline -->
                    <div class="text-center rounded-3 py-2 mb-3" style="background:rgba(26,115,232,.2);border:1px solid rgba(26,115,232,.4);">
                        <span style="color:#90caf9;font-size:12px;font-weight:600;">स्वस्थ रहें &nbsp;|&nbsp; सुरक्षित रहें &nbsp;|&nbsp; हमेशा जुड़े रहें</span>
                    </div>

                    <!-- App ki mukhya visheshataen heading -->
                    <div class="text-white fw-bold mb-2" style="font-size:13px;letter-spacing:.5px;">📱 App की मुख्य विशेषताएँ / Key Features</div>

                    <!-- Features Grid -->
                    <div class="row g-2 mb-3">
                        @php
                        $patientFeatures = [
                            ['emoji'=>'📚', 'bg'=>'#1565c0', 'en'=>'Disease Library',    'hi'=>'55+ बीमारियां • लक्षण • इलाज और डॉक्टर की जानकारी'],
                            ['emoji'=>'🧠', 'bg'=>'#6a1b9a', 'en'=>'BrainFit',           'hi'=>'दिमाग को तेज़ और एकाग्र बनाएं'],
                            ['emoji'=>'🔥', 'bg'=>'#bf360c', 'en'=>'Calorie Burn',       'hi'=>'वजन घटाने में मददगार'],
                            ['emoji'=>'💉', 'bg'=>'#2e7d32', 'en'=>'Vaccine Tracker',    'hi'=>'टीकाकरण का पूरा रिकॉर्ड रखें'],
                            ['emoji'=>'🤱', 'bg'=>'#ad1457', 'en'=>'Pregnancy',          'hi'=>'गर्भावस्था की जानकारी और देखभाल'],
                            ['emoji'=>'🌸', 'bg'=>'#c62828', 'en'=>'Period Tracker',     'hi'=>'मासिक धर्म का सही ट्रैक रखें'],
                            ['emoji'=>'👶', 'bg'=>'#e65100', 'en'=>'Baby Growth',        'hi'=>'बच्चे के विकास पर नज़र रखें'],
                            ['emoji'=>'💊', 'bg'=>'#00695c', 'en'=>'Medicine',           'hi'=>'दवाइयों की जानकारी और रिमाइंडर'],
                            ['emoji'=>'🥗', 'bg'=>'#558b2f', 'en'=>'Food AI',            'hi'=>'AI से पर्सनलाइज़्ड डाइट प्लान'],
                            ['emoji'=>'🚫', 'bg'=>'#4527a0', 'en'=>'Food Combos',        'hi'=>'हेल्दी फूड कॉम्बिनेशन रिसिपी'],
                            ['emoji'=>'❤️',  'bg'=>'#b71c1c', 'en'=>'Body Guide',         'hi'=>'शरीर के हर अंग की जानकारी'],
                            ['emoji'=>'💧', 'bg'=>'#0277bd', 'en'=>'Water Reminder',     'hi'=>'रोज़ पानी पीने की याद दिलाए'],
                            ['emoji'=>'🤖', 'bg'=>'#37474f', 'en'=>'Sehat AI',           'hi'=>'AI से पाएं व्यक्तिगत स्वास्थ्य सलाह'],
                            ['emoji'=>'👁️', 'bg'=>'#4a148c', 'en'=>'Eye Test',     'hi'=>'आँखों की देखभाल और टेस्ट जानकारी'],
                            ['emoji'=>'👨‍⚕️', 'bg'=>'#1565c0', 'en'=>'Find Doctors',  'hi'=>'नज़दीकी डॉक्टर खोजें व अपॉइंटमेंट बुक करें'],
                        ];
                        @endphp

                        @foreach($patientFeatures as $f)
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 rounded-3 px-2 py-2" style="background:rgba(255,255,255,.06);">
                                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:30px;height:30px;background:{{ $f['bg'] }};font-size:14px;">
                                    {{ $f['emoji'] }}
                                </div>
                                <div style="min-width:0;">
                                    <div class="text-white fw-bold" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $f['en'] }}</div>
                                    <div style="color:rgba(255,255,255,.45);font-size:10px;line-height:1.3;">{{ $f['hi'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- More tag -->
                        <div class="col-12">
                            <div class="text-center rounded-3 py-2" style="background:rgba(255,215,0,.12);border:1px dashed rgba(255,215,0,.4);">
                                <span style="color:#ffd700;font-size:12px;font-weight:700;">✨ और भी बहुत कुछ… सभी एक ही एप में! / And much more in one app!</span>
                            </div>
                        </div>
                    </div>

                    <!-- Why choose -->
                    <div class="row g-2 mb-3">
                        @foreach([
                            ['🛡️', 'सटीक और भरोसेमंद जानकारी / Accurate & Trusted Info'],
                            ['👨‍👩‍👧‍👦', 'पूरे परिवार के लिए उपयोगी / Useful for Whole Family'],
                            ['⏱️', 'आसान और तेज़ एक्सेस / Easy & Fast Access'],
                            ['📱', 'कहीं भी, कभी भी उपयोग करें / Use Anytime, Anywhere'],
                        ] as $w)
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 rounded-3 px-2 py-2" style="background:rgba(26,115,232,.12);">
                                <span style="font-size:16px;">{{ $w[0] }}</span>
                                <span style="color:rgba(255,255,255,.8);font-size:10px;line-height:1.3;">{{ $w[1] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank"
                       class="btn w-100 fw-bold py-2"
                       style="background:linear-gradient(135deg,#1a73e8,#0d47a1);color:#fff;border:none;border-radius:12px;font-size:14px;">
                        <i class="fa fa-android me-2" style="font-size:16px;"></i>
                        अभी डाउनलोड करें / Download Patient App
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact / Help Bar -->
        <div class="rounded-4 p-4 mt-4 text-center" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);">
            <div class="text-white fw-bold mb-3" style="font-size:15px;">📞 Need Help? / मदद चाहिए?</div>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="tel:+918002229525"
                   style="background:#00b074;color:#fff;border-radius:10px;padding:10px 22px;font-weight:700;font-size:13px;text-decoration:none;">
                    <i class="fa fa-phone me-2"></i>+91 8002229525
                </a>
                <a href="https://wa.me/918002229525" target="_blank"
                   style="background:#25d366;color:#fff;border-radius:10px;padding:10px 22px;font-weight:700;font-size:13px;text-decoration:none;">
                    <i class="fa fa-whatsapp me-2"></i>WhatsApp
                </a>
                <a href="mailto:rogisewa25@gmail.com"
                   style="background:#667eea;color:#fff;border-radius:10px;padding:10px 22px;font-weight:700;font-size:13px;text-decoration:none;">
                    <i class="fa fa-envelope me-2"></i>rogisewa25@gmail.com
                </a>
            </div>
        </div>

    </div>
</div>

<!-- ── WHY ROGISEWA ── -->
<section class="py-5" style="background:#f8fbff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 rs-animate-left">
                <div class="rs-section-tag">Why Choose Us</div>
                <h2 class="rs-section-title mb-4">Why Patients Trust RogiSewa</h2>
                <p class="text-muted mb-4">RogiSewa was built to solve a real problem — patients in India often struggle to find the right doctor quickly. We provide a structured, transparent, and easy-to-use platform.</p>
                <div class="row g-3">
                    @foreach([
                        ['fa-check-circle','#1565c0','Verified doctor & hospital profiles'],
                        ['fa-search','#2e7d32','Search by specialization & location'],
                        ['fa-file-text','#e65100','Free prescription invoice generation'],
                        ['fa-shield','#7c3aed','Transparent healthcare information'],
                        ['fa-ban','#c62828','No hidden fees or charges'],
                        ['fa-map-marker','#0277bd','Available across India'],
                    ] as $item)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa {{ $item[0] }}" style="color:{{ $item[1] }};font-size:16px;"></i>
                            <span style="font-size:13.5px;color:#444;">{{ $item[2] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 rs-animate-right">
                <div class="bg-white rounded-4 p-4 shadow-sm">
                    <h5 class="fw-bold text-primary mb-4">Search Doctors by Specialization</h5>
                    <div class="row g-2">
                        @foreach(['General Medicine','Cardiology','Pediatrics','Orthopedics','Gynecology','Dermatology','Neurology','ENT','Ophthalmology','Dentistry','Psychiatry','Urology'] as $spec)
                        <div class="col-6">
                            <a href="{{ url('doctors') }}" class="rs-spec-pill">
                                <i class="fa fa-stethoscope" style="font-size:11px;"></i> {{ $spec }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url('doctors') }}" class="btn btn-primary btn-sm rounded-pill px-4">View All Specializations</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── HEALTH TIPS ── -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5 rs-animate">
            <div class="rs-section-tag">Health Tips</div>
            <h2 class="rs-section-title">Important Healthcare Tips</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['fa-heartbeat','#ef4444','rgba(239,68,68,.1)','Regular Health Checkups','Schedule routine health checkups at least once a year. Early detection significantly improves treatment outcomes.'],
                ['fa-user-md','#0a6ebd','rgba(10,110,189,.1)','Choose the Right Specialist','Always consult a specialist relevant to your health concern. The right specialist ensures accurate diagnosis.'],
                ['fa-file-text-o','#00b074','rgba(0,176,116,.1)','Maintain Medical Records','Keep a record of your prescriptions, test reports, and medical history for better treatment.'],
                ['fa-map-marker','#f59e0b','rgba(245,158,11,.1)','Find Nearby Healthcare','Choosing a nearby doctor ensures timely access to care and faster emergency response.'],
                ['fa-shield','#7c3aed','rgba(124,58,237,.1)','Verify Doctor Credentials','Always check a doctor\'s qualifications and registration before booking an appointment.'],
                ['fa-comments','#f093fb','rgba(240,147,251,.1)','Communicate Openly','Share your complete medical history and symptoms clearly with your doctor.'],
            ] as $i => $tip)
            <div class="col-md-4 rs-animate rs-delay-{{ ($i%3)+1 }}">
                <div class="rs-feature-card h-100">
                    <div class="rs-feature-icon" style="background:{{ $tip[2] }};">
                        <i class="fa {{ $tip[0] }}" style="color:{{ $tip[1] }};"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-2">{{ $tip[3] }}</h6>
                        <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">{{ $tip[4] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── APP FEATURES DETAIL SECTION ── -->
<section class="py-5" style="background:linear-gradient(135deg,#f8fbff,#e3f2fd);">
    <div class="container">
        <div class="text-center mb-5 rs-animate">
            <div class="rs-section-tag">RogiSewa App</div>
            <h2 class="rs-section-title">Sab Kuch Ek App Mein!</h2>
            <p class="text-muted mt-2" style="max-width:600px;margin:0 auto;">Patient app aur Doctor app — dono mein powerful features jo aapki health aur clinic ko manage karte hain.</p>
        </div>

        <!-- Patient App Features -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-3 mb-4 rs-animate">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,#1a73e8,#0d47a1);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-heartbeat text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" style="color:#0d47a1;">Patient App Features</h4>
                    <p class="text-muted mb-0" style="font-size:13px;">आपके और आपके परिवार की सेहत का भरोसेमंद साथी</p>
                </div>
                <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank"
                   class="ms-auto btn fw-bold"
                   style="background:linear-gradient(135deg,#1a73e8,#0d47a1);color:#fff;border-radius:12px;font-size:13px;">
                    <i class="fa fa-android me-1"></i> Download
                </a>
            </div>
            <div class="row g-3">
                @foreach([
                    ['📚','#1565c0','rgba(21,101,192,.08)','Disease Library','55+ बीमारियां — लक्षण, कारण, इलाज और संबंधित डॉक्टर की पूरी जानकारी एक जगह।'],
                    ['🧠','#6a1b9a','rgba(106,27,154,.08)','BrainFit','दिमाग को तेज़ और एकाग्र बनाने के लिए मानसिक व्यायाम और टिप्स।'],
                    ['🔥','#bf360c','rgba(191,54,12,.08)','Calorie Burn','वजन घटाने में मदद — कैलोरी ट्रैक करें और फिट रहें।'],
                    ['💉','#2e7d32','rgba(46,125,50,.08)','Vaccine Tracker','बच्चों और बड़ों के टीकाकरण का पूरा रिकॉर्ड रखें।'],
                    ['🤱','#ad1457','rgba(173,20,87,.08)','Pregnancy Tracker','गर्भावस्था की हर जानकारी — हफ्तेवार अपडेट और देखभाल के टिप्स।'],
                    ['🌸','#c62828','rgba(198,40,40,.08)','Period Tracker','मासिक धर्म का सही ट्रैक रखें और अगले cycle की जानकारी पाएं।'],
                    ['👶','#e65100','rgba(230,81,0,.08)','Baby Growth','बच्चे के विकास पर नज़र रखें — वजन, लंबाई और माइलस्टोन।'],
                    ['💊','#00695c','rgba(0,105,92,.08)','Medicine Info','दवाइयों की जानकारी, उपयोग और रिमाइंडर सेट करें।'],
                    ['🥗','#558b2f','rgba(85,139,47,.08)','Food AI','AI से पर्सनलाइज़्ड डाइट प्लान — आपकी सेहत के अनुसार।'],
                    ['🚫','#4527a0','rgba(69,39,160,.08)','Food Combos','हेल्दी फूड कॉम्बिनेशन और रेसिपी जो सेहत के लिए फायदेमंद हों।'],
                    ['❤️','#b71c1c','rgba(183,28,28,.08)','Body Guide','शरीर के हर अंग की विस्तृत जानकारी — कार्य, देखभाल और समस्याएं।'],
                    ['💧','#0277bd','rgba(2,119,189,.08)','Water Reminder','रोज़ पर्याप्त पानी पीने की याद दिलाए — हाइड्रेटेड रहें।'],
                    ['🤖','#37474f','rgba(55,71,79,.08)','Sehat AI','AI से पाएं व्यक्तिगत स्वास्थ्य सलाह — 24/7 उपलब्ध।'],
                    ['👁️','#4a148c','rgba(74,20,140,.08)','Eye Test','आँखों की देखभाल, टेस्ट जानकारी और नेत्र स्वास्थ्य टिप्स।'],
                    ['👨‍⚕️','#1565c0','rgba(21,101,192,.08)','Find Doctors','नज़दीकी वेरिफाइड डॉक्टर खोजें और अपॉइंटमेंट बुक करें।'],
                    ['🏥','#00838f','rgba(0,131,143,.08)','Find Hospitals','नज़दीकी हॉस्पिटल और क्लिनिक खोजें — विशेषज्ञता के अनुसार।'],
                ] as $i => $f)
                <div class="col-md-4 col-6 rs-animate rs-delay-{{ ($i%3)+1 }}">
                    <div style="background:#fff;border-radius:14px;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid #e8f0fe;display:flex;gap:12px;align-items:flex-start;transition:all .3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(21,101,192,.12)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'">
                        <div style="width:42px;height:42px;background:{{ $f[2] }};border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">{{ $f[0] }}</div>
                        <div>
                            <div style="font-weight:700;font-size:13px;color:#1a1a2e;margin-bottom:3px;">{{ $f[3] }}</div>
                            <div style="font-size:11px;color:#666;line-height:1.4;">{{ $f[4] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Doctor App Features -->
        <div>
            <div class="d-flex align-items-center gap-3 mb-4 rs-animate">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,#00b074,#38f9d7);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-user-md text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" style="color:#00695c;">Doctor App Features</h4>
                    <p class="text-muted mb-0" style="font-size:13px;">Doctors & Clinics ke liye — manage everything digitally</p>
                </div>
                <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                   class="ms-auto btn fw-bold"
                   style="background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border-radius:12px;font-size:13px;">
                    <i class="fa fa-android me-1"></i> Download
                </a>
            </div>
            <div class="row g-3">
                @foreach([
                    ['🏥','#667eea','rgba(102,126,234,.08)','Doctor Profile Listing','अपनी डॉक्टर प्रोफ़ाइल बनाएं और पूरे भारत में मरीजों तक पहुँचें।'],
                    ['🏥','#f5576c','rgba(245,87,108,.08)','Hospital / Clinic Management','अपने हॉस्पिटल या क्लिनिक की पूरी जानकारी मैनेज करें।'],
                    ['📅','#4facfe','rgba(79,172,254,.08)','Appointment Management','मरीजों के अपॉइंटमेंट को आसानी से मैनेज और ट्रैक करें।'],
                    ['📝','#f59e0b','rgba(245,158,11,.08)','Prescription Invoice (Free)','डिजिटल प्रिस्क्रिप्शन इनवॉइस बनाएं — बिल्कुल मुफ्त।'],
                    ['💊','#00b074','rgba(0,176,116,.08)','Medicine Management','अपनी क्लिनिक की दवाइयों का पूरा रिकॉर्ड रखें।'],
                    ['👥','#a18cd1','rgba(161,140,209,.08)','Staff Management','स्टाफ की जानकारी, रोल और परमिशन मैनेज करें।'],
                    ['📊','#38f9d7','rgba(56,249,215,.08)','Attendance Tracking','स्टाफ की रोज़ाना अटेंडेंस ट्रैक करें और रिपोर्ट देखें।'],
                    ['🔔','#ff6b6b','rgba(255,107,107,.08)','Booking Reminders','मरीजों को अपॉइंटमेंट रिमाइंडर भेजें — auto notifications।'],
                    ['📈','#667eea','rgba(102,126,234,.08)','Analytics & Reports','क्लिनिक की performance देखें — bookings, revenue और trends।'],
                ] as $i => $f)
                <div class="col-md-4 col-6 rs-animate rs-delay-{{ ($i%3)+1 }}">
                    <div style="background:#fff;border-radius:14px;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid #e0f7f0;display:flex;gap:12px;align-items:flex-start;transition:all .3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(0,176,116,.12)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'">
                        <div style="width:42px;height:42px;background:{{ $f[2] }};border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">{{ $f[0] }}</div>
                        <div>
                            <div style="font-weight:700;font-size:13px;color:#1a1a2e;margin-bottom:3px;">{{ $f[3] }}</div>
                            <div style="font-size:11px;color:#666;line-height:1.4;">{{ $f[4] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ── FOR DOCTORS CTA ── -->
<section class="rs-cta py-5">
    <div class="container text-center" style="position:relative;z-index:1;">
        <div class="rs-section-tag" style="background:rgba(255,255,255,.2);color:#fff;">For Doctors</div>
        <h2 class="text-white mt-3 mb-3" style="font-size:2rem;font-weight:800;">Are You a Doctor or Hospital?</h2>
        <p class="text-white mb-4" style="max-width:600px;margin:0 auto 24px;opacity:.9;">
            Register your clinic on RogiSewa and connect with thousands of patients across India. Get a verified profile, manage prescriptions digitally, and grow your practice.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('register') }}" class="btn btn-light rounded-pill py-3 px-5 fw-bold" style="font-size:15px;">
                <i class="fa fa-user-plus me-2"></i> Register as Doctor
            </a>
            <a href="{{ url('contact') }}" class="btn btn-outline-light rounded-pill py-3 px-5" style="font-size:15px;">
                <i class="fa fa-envelope me-2"></i> Contact Us
            </a>
        </div>
        <div class="mt-4 text-white" style="font-size:13px;opacity:.8;">
            ✅ Free prescription invoice &nbsp;|&nbsp; ✅ Doctor profile listing &nbsp;|&nbsp; ✅ Hospital management &nbsp;|&nbsp; ✅ Staff attendance tracking
        </div>
    </div>
</section>

<!-- ── DISCLAIMER ── -->
<div class="container py-4">
    <div class="p-4 rounded-4" style="background:#f8fbff;border:1px solid #e2e8f0;">
        <h6 class="fw-bold text-primary mb-2"><i class="fa fa-info-circle me-2"></i>Important Disclaimer</h6>
        <p class="text-muted mb-0" style="font-size:13px;line-height:1.7;">RogiSewa is a healthcare information and discovery platform. We do not provide medical advice, diagnosis, or treatment. Always consult a qualified healthcare professional. In case of emergency, contact your nearest hospital immediately.</p>
    </div>
</div>

<!-- Registration Popup Modal -->
<div class="modal fade" id="registrationPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bold">📢 Doctor & Hospital Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><b>RogiSewa.com</b> पर अपने क्लिनिक या हॉस्पिटल को रजिस्टर करें और पूरे <b>भारत</b> में मरीजों तक आसानी से पहुँचें।</p>
                <ul class="list-unstyled">
                    <li>✅ पूरे भारत में अपने क्लिनिक और हॉस्पिटल की पहचान बनाएँ</li>
                    <li>✅ मरीज सीधे आपसे संपर्क कर सकेंगे</li>
                    <li>✅ <b>Prescription Invoice Generation सेवा बिल्कुल FREE है</b></li>
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="{{ url('register') }}" class="btn btn-success fw-bold">👉 Register Now</a>
                <button type="button" class="btn btn-danger fw-bold" data-bs-dismiss="modal">❌ Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll Animations
const rsObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('rs-visible'); } });
}, { threshold: 0.1 });
document.querySelectorAll('.rs-animate, .rs-animate-left, .rs-animate-right').forEach(el => rsObserver.observe(el));

// Counter Animation
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting && !e.target.dataset.counted) {
            e.target.dataset.counted = true;
            const target = +e.target.dataset.target;
            const duration = 1800;
            const step = target / (duration / 16);
            let current = 0;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) { current = target; clearInterval(timer); }
                e.target.textContent = Math.floor(current) + '+';
            }, 16);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.rs-counter').forEach(el => counterObserver.observe(el));

// Hero features infinite scroll (JS duplicate)
const scroller = document.querySelector('.hero-features-scroll');
if (scroller) {
    scroller.innerHTML += scroller.innerHTML;
}

// Registration popup after 5s
// setTimeout(() => {
//     if (typeof bootstrap !== 'undefined') {
//         new bootstrap.Modal(document.getElementById('registrationPopup')).show();
//     }
// }, 5000);
</script>

@endsection