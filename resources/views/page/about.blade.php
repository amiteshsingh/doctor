@extends('page.layouts.app')

@section('title', 'About RogiSewa – Online Doctor Booking App | Find Doctors & Hospitals in India')
@section('meta_description', 'RogiSewa is India\'s trusted online doctor booking platform. Search doctors by specialty or location, book appointments instantly, and download our free Android app.')

@section('content')

<style>
/* ============================================================
   ABOUT PAGE – ANIMATED STYLES
   ============================================================ */

/* ---------- Reveal Animations ---------- */
.reveal { opacity:0; transform:translateY(48px); transition:opacity .7s ease, transform .7s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
.reveal-left  { opacity:0; transform:translateX(-60px); transition:opacity .7s ease, transform .7s ease; }
.reveal-left.visible  { opacity:1; transform:translateX(0); }
.reveal-right { opacity:0; transform:translateX(60px);  transition:opacity .7s ease, transform .7s ease; }
.reveal-right.visible { opacity:1; transform:translateX(0); }

/* ---------- Hero Section ---------- */
.about-hero {
    background: linear-gradient(135deg, #0a6ebd 0%, #00b074 100%);
    position: relative;
    overflow: hidden;
    padding: 90px 0 70px;
}
.about-hero::before {
    content:'';
    position:absolute; inset:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-badge {
    display:inline-block;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(6px);
    border:1px solid rgba(255,255,255,.3);
    border-radius:50px;
    padding:6px 20px;
    font-size:13px;
    color:#fff;
    letter-spacing:1.5px;
    text-transform:uppercase;
    margin-bottom:18px;
    animation: fadeSlideDown .8s ease both;
}
.hero-title {
    font-size:clamp(2rem,5vw,3.2rem);
    font-weight:800;
    color:#fff;
    line-height:1.2;
    animation: fadeSlideDown 1s .1s ease both;
}
.hero-sub {
    font-size:1.1rem;
    color:rgba(255,255,255,.88);
    animation: fadeSlideDown 1s .2s ease both;
    max-width:560px;
    margin: 0 auto;
}
.hero-btns { animation: fadeSlideDown 1s .35s ease both; }
.hero-stat-card {
    background:rgba(255,255,255,.14);
    backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,.25);
    border-radius:16px;
    padding:22px 24px;
    text-align:center;
    animation: fadeSlideDown 1s .45s ease both;
    transition:transform .3s;
}
.hero-stat-card:hover { transform:translateY(-5px); }
.hero-stat-card .num {
    font-size:2.2rem;
    font-weight:800;
    color:#fff;
    line-height:1;
}
.hero-stat-card .lbl {
    font-size:12px;
    color:rgba(255,255,255,.8);
    margin-top:4px;
    letter-spacing:.5px;
}

/* Floating blobs */
.blob {
    position:absolute;
    border-radius:50%;
    filter:blur(60px);
    opacity:.18;
    animation: blobFloat 8s ease-in-out infinite;
}
.blob-1 { width:320px;height:320px;background:#fff;   top:-80px;  right:-60px; }
.blob-2 { width:200px;height:200px;background:#00ffc3; bottom:-40px; left:5%;  animation-delay:-3s; }

@keyframes blobFloat {
    0%,100%{ transform:translateY(0) scale(1); }
    50%    { transform:translateY(-24px) scale(1.04); }
}
@keyframes fadeSlideDown {
    from{ opacity:0; transform:translateY(-24px); }
    to  { opacity:1; transform:translateY(0); }
}

/* Pulse ring */
.pulse-ring {
    position:relative; display:inline-block;
}
.pulse-ring::after {
    content:'';
    position:absolute; inset:-10px;
    border-radius:50%;
    border:3px solid rgba(10,110,189,.25);
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%  { transform:scale(1);   opacity:1; }
    100%{ transform:scale(1.5); opacity:0; }
}

/* ---------- Section Labels ---------- */
.sec-label {
    display:inline-block;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    font-size:12px;
    font-weight:700;
    letter-spacing:2.5px;
    text-transform:uppercase;
    margin-bottom:10px;
}
.sec-title {
    font-size:clamp(1.7rem,3.5vw,2.4rem);
    font-weight:800;
    color:#0f172a;
    line-height:1.25;
}
.sec-divider {
    width:54px; height:4px;
    background:linear-gradient(90deg,#0a6ebd,#00b074);
    border-radius:4px;
    margin:14px auto 0;
}

/* ---------- Tabs ---------- */
.about-tab-btn {
    border:none;
    border-radius:50px;
    padding:8px 20px;
    font-weight:600;
    font-size:13.5px;
    background:#f1f5f9;
    color:#475569;
    transition:all .3s;
    margin:3px;
}
.about-tab-btn.active,
.about-tab-btn:hover {
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff;
    box-shadow:0 4px 14px rgba(10,110,189,.35);
    transform:translateY(-1px);
}
.tab-content-box {
    background:#fff;
    border-radius:16px;
    padding:28px;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
}
.tab-content-box p { color:#475569; line-height:1.75; }
.tab-content-box ul li { color:#475569; margin-bottom:6px; }

/* ---------- Feature Icons ---------- */
.feat-card {
    background:#fff;
    border-radius:18px;
    padding:28px 18px;
    text-align:center;
    box-shadow:0 4px 20px rgba(0,0,0,.07);
    transition:transform .35s, box-shadow .35s;
    height:100%;
}
.feat-card:hover { transform:translateY(-8px); box-shadow:0 12px 36px rgba(10,110,189,.18); }
.feat-icon {
    width:68px; height:68px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px;
    font-size:26px;
    background:linear-gradient(135deg,#e8f4fd,#d4f5fb);
    color:#0a6ebd;
    transition:transform .3s;
}
.feat-card:hover .feat-icon { transform:rotate(8deg) scale(1.12); }
.feat-card h6 { font-weight:700; color:#0f172a; margin-bottom:6px; }
.feat-card p  { font-size:13px; color:#64748b; margin:0; }

/* ---------- How It Works ---------- */
.step-wrap { position:relative; }
.step-wrap::before {
    content:'';
    position:absolute;
    top:44px; left:50%; transform:translateX(-50%);
    width:70%; height:3px;
    background:linear-gradient(90deg,#0a6ebd,#00b074);
    border-radius:4px;
    z-index:0;
}
@media(max-width:767px){ .step-wrap::before { display:none; } }
.step-card {
    background:#fff;
    border-radius:20px;
    padding:32px 24px;
    text-align:center;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    position:relative;
    z-index:1;
    transition:transform .35s, box-shadow .35s;
    height:100%;
}
.step-card:hover { transform:translateY(-8px); box-shadow:0 14px 40px rgba(10,110,189,.18); }
.step-num {
    width:52px; height:52px;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:20px;
    font-weight:800;
    margin:0 auto 16px;
    box-shadow:0 6px 20px rgba(10,110,189,.35);
    transition:transform .3s;
}
.step-card:hover .step-num { transform:scale(1.1) rotate(5deg); }
.step-icon-circle {
    width:58px; height:58px;
    background:linear-gradient(135deg,#e8f4fd,#d4f5fb);
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:24px; color:#0a6ebd;
    margin:0 auto 14px;
    transition:transform .3s;
}
.step-card:hover .step-icon-circle { transform:scale(1.12); }

/* ---------- Stats Counter ---------- */
.stats-section {
    background:linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    position:relative; overflow:hidden;
}
.stats-section::before {
    content:'';
    position:absolute; inset:0;
    background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 1.4l2.83 2.83 1.41-1.41L1.41 0H0v1.41zM38.59 40l-2.83-2.83 1.41-1.41L40 38.59V40h-1.41zM40 1.41l-2.83 2.83-1.41-1.41L38.59 0H40v1.41z'/%3E%3C/g%3E%3C/svg%3E");
}
.stat-box { text-align:center; padding:30px 16px; }
.stat-box .counter {
    font-size:3rem;
    font-weight:900;
    color:#fff;
    line-height:1;
    display:block;
}
.stat-box .stat-label {
    color:rgba(255,255,255,.85);
    font-size:13.5px;
    margin-top:6px;
    letter-spacing:.4px;
}
.stat-box .stat-icon { font-size:2rem; color:rgba(255,255,255,.4); margin-bottom:10px; }

/* ---------- App Download ---------- */
.app-section {
    background:linear-gradient(135deg,#e8f4fd 0%,#d4f5fb 100%);
    position:relative; overflow:hidden;
}
.app-section::before {
    content:'';
    position:absolute;
    width:400px; height:400px;
    background:radial-gradient(circle,rgba(10,110,189,.12),transparent 70%);
    top:-100px; right:-100px;
    border-radius:50%;
}
.app-mockup {
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    border-radius:30px;
    padding:40px 30px;
    text-align:center;
    box-shadow:0 20px 60px rgba(10,110,189,.35);
    position:relative;
    overflow:hidden;
    transition:transform .4s;
}
.app-mockup:hover { transform:translateY(-8px) rotate(1deg); }
.app-mockup::before {
    content:'';
    position:absolute;
    width:200px; height:200px;
    background:rgba(255,255,255,.07);
    border-radius:50%;
    top:-60px; right:-60px;
}
.app-feat-item {
    display:flex; align-items:flex-start; gap:12px;
    margin-bottom:16px;
}
.app-feat-icon {
    width:38px; height:38px; min-width:38px;
    background:linear-gradient(135deg,#e8f4fd,#d4f5fb);
    border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    color:#0a6ebd; font-size:15px;
}

/* ---------- Doctor Card ---------- */
.doc-carousel-card {
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 24px rgba(0,0,0,.09);
    transition:transform .35s, box-shadow .35s;
}
.doc-carousel-card:hover { transform:translateY(-8px); box-shadow:0 16px 48px rgba(10,110,189,.18); }

/* ---------- Language Tabs ---------- */
.lang-tab-pills { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; }

/* ---------- Mission / Vision ---------- */
.mv-card {
    border-radius:20px;
    padding:36px 30px;
    height:100%;
    position:relative;
    overflow:hidden;
    transition:transform .35s;
}
.mv-card:hover { transform:translateY(-6px); }
.mv-card::before {
    content:'';
    position:absolute;
    width:180px; height:180px;
    border-radius:50%;
    top:-50px; right:-50px;
    opacity:.1;
}
.mv-card-mission { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
.mv-card-mission::before { background:#0a6ebd; }
.mv-card-vision  { background:linear-gradient(135deg,#f0fff4,#d1fae5); }
.mv-card-vision::before  { background:#00b074; }
.mv-card-values  { background:linear-gradient(135deg,#fdf4ff,#ede9fe); }
.mv-card-values::before  { background:#7c3aed; }
.mv-icon {
    width:56px; height:56px;
    border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; margin-bottom:16px;
}

/* ---------- Team (doctors) carousel ---------- */
.team-item { padding:0 12px; }

/* ---------- Timeline ---------- */
.timeline { position:relative; padding-left:36px; }
.timeline::before {
    content:'';
    position:absolute;
    left:14px; top:0; bottom:0;
    width:3px;
    background:linear-gradient(180deg,#0a6ebd,#00b074);
    border-radius:4px;
}
.tl-item { position:relative; margin-bottom:32px; }
.tl-dot {
    position:absolute;
    left:-36px; top:4px;
    width:22px; height:22px;
    border-radius:50%;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    border:3px solid #fff;
    box-shadow:0 0 0 3px rgba(10,110,189,.25);
}
.tl-card {
    background:#fff;
    border-radius:14px;
    padding:20px 22px;
    box-shadow:0 3px 18px rgba(0,0,0,.07);
    border-left:4px solid #0a6ebd;
    transition:transform .3s;
}
.tl-card:hover { transform:translateX(6px); }
.tl-year {
    display:inline-block;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff;
    border-radius:50px;
    padding:2px 12px;
    font-size:12px;
    font-weight:700;
    margin-bottom:8px;
}

/* ---------- Testimonial / Why Us ---------- */
.why-item {
    display:flex; align-items:flex-start; gap:16px;
    margin-bottom:22px;
}
.why-icon {
    width:46px; height:46px; min-width:46px;
    border-radius:12px;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:18px;
    box-shadow:0 4px 14px rgba(10,110,189,.3);
    transition:transform .3s;
}
.why-item:hover .why-icon { transform:scale(1.12) rotate(-5deg); }

/* ---------- Content article ---------- */
.article-section h2 { font-size:1.45rem; font-weight:700; color:#0f172a; margin-bottom:12px; }
.article-section p  { color:#475569; line-height:1.75; }

/* ---------- Floating CTA ---------- */
.cta-block {
    background:linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border-radius:24px;
    padding:48px 40px;
    text-align:center;
    position:relative; overflow:hidden;
}
.cta-block::before {
    content:'';
    position:absolute;
    width:300px; height:300px;
    background:rgba(255,255,255,.07);
    border-radius:50%;
    bottom:-100px; right:-80px;
}
</style>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="about-hero text-center">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="container position-relative">
        <div class="hero-badge"><i class="fa fa-heartbeat me-2"></i> About RogiSewa</div>
        <h1 class="hero-title">India's Trusted<br>Healthcare Platform</h1>
        <p class="hero-sub mt-3 mb-4 mx-auto">
            Connecting patients with qualified doctors and hospitals across India —
            online, instant, 24/7.
        </p>
        <div class="hero-btns d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('doctors') }}" class="btn btn-light btn-lg px-4 fw-700" style="border-radius:50px;font-weight:700;">
                <i class="fa fa-search me-2"></i> Find a Doctor
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener"
               class="btn btn-outline-light btn-lg px-4" style="border-radius:50px;font-weight:700;">
                <i class="fab fa-google-play me-2"></i> Download App
            </a>
        </div>


    </div>
</section>


<!-- ============================================================
     ABOUT + TABS
     ============================================================ -->
<section class="py-5" style="background:#f8fafc;">
<div class="container">
    <div class="row align-items-center g-5">

        <!-- Image -->
        <div class="col-lg-5 reveal-left">
            <div style="position:relative;">
                <img src="{{ asset('img/about.jpg') }}" alt="About RogiSewa"
                     class="img-fluid rounded-4 shadow-lg w-100"
                     style="object-fit:cover;max-height:480px;">
                <!-- Floating badge -->
                <div style="position:absolute;bottom:-20px;left:24px;background:#fff;border-radius:16px;padding:14px 22px;box-shadow:0 8px 30px rgba(0,0,0,.12);display:flex;align-items:center;gap:12px;">
                    <div style="width:46px;height:46px;background:linear-gradient(135deg,#0a6ebd,#00b074);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;">
                        <i class="fa fa-shield-alt"></i>
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:18px;color:#0f172a;line-height:1;">Verified</div>
                        <div style="font-size:12px;color:#64748b;">Healthcare Professionals</div>
                    </div>
                </div>
                <!-- Decorative circle -->
                <div style="position:absolute;top:-18px;right:-18px;width:90px;height:90px;background:linear-gradient(135deg,#0a6ebd33,#00b07433);border-radius:50%;z-index:-1;"></div>
            </div>
        </div>

        <!-- Tabs content -->
        <div class="col-lg-7 reveal-right">
            <div class="sec-label">Who We Are</div>
            <h2 class="sec-title">India's Most Accessible<br>Doctor Booking Platform</h2>
            <div class="sec-divider mb-4" style="margin-left:0;"></div>

            <!-- Language pill buttons -->
            <div class="lang-tab-pills" id="langTabBtns">
                <button class="about-tab-btn active" data-lang="en" type="button">English</button>
                <button class="about-tab-btn" data-lang="hi" type="button">हिंदी</button>
                <button class="about-tab-btn" data-lang="ta" type="button">தமிழ்</button>
                <button class="about-tab-btn" data-lang="te" type="button">తెలుగు</button>
                <button class="about-tab-btn" data-lang="mr" type="button">मराठी</button>
                <button class="about-tab-btn" data-lang="bn" type="button">বাংলা</button>
            </div>

            <div class="tab-content-box" id="langTabContent">
                <!-- English -->
                <div class="lang-pane" id="lang-en" style="display:block;">
                    <p>Welcome to <strong>RogiSewa.com</strong> – India's trusted online doctor booking and healthcare discovery platform for patients and families across the country.</p>
                    <p>We make quality healthcare accessible to everyone. Search for the nearest <strong>doctors</strong> and <strong>hospitals</strong>, book appointments online in minutes, and manage your health from anywhere — all in one place.</p>
                    <ul class="mb-0">
                        <li>Search doctors by <strong>location, specialty, or disease</strong></li>
                        <li><strong>Book doctor appointments online</strong> — instantly, 24/7</li>
                        <li>Find verified hospitals and clinics near you</li>
                        <li>Download our <strong>free Android app</strong> for on-the-go access</li>
                        <li>Read health tips, disease guides, and wellness articles</li>
                    </ul>
                </div>
                <!-- Hindi -->
                <div class="lang-pane" id="lang-hi" style="display:none;">
                    <p><strong>RogiSewa.com</strong> पर आपका स्वागत है – भारत का भरोसेमंद ऑनलाइन डॉक्टर बुकिंग और हेल्थकेयर डिस्कवरी प्लेटफ़ॉर्म।</p>
                    <p>हम हर किसी तक गुणवत्तापूर्ण स्वास्थ्य सेवाएँ पहुँचाते हैं। नज़दीकी <strong>डॉक्टर</strong> और <strong>अस्पताल</strong> खोजें, ऑनलाइन अपॉइंटमेंट बुक करें।</p>
                    <ul class="mb-0">
                        <li><strong>स्थान, विशेषज्ञता या बीमारी</strong> के आधार पर डॉक्टर खोजें</li>
                        <li><strong>ऑनलाइन डॉक्टर अपॉइंटमेंट बुक करें</strong> — 24/7 उपलब्ध</li>
                        <li>नज़दीकी अस्पताल और क्लिनिक खोजें</li>
                        <li>हमारा <strong>मुफ़्त Android ऐप</strong> डाउनलोड करें</li>
                        <li>स्वास्थ्य टिप्स और बीमारी गाइड पढ़ें</li>
                    </ul>
                </div>
                <!-- Tamil -->
                <div class="lang-pane" id="lang-ta" style="display:none;">
                    <p><strong>RogiSewa.com</strong>க்கு உங்களை வரவேற்கிறோம் — இந்தியாவின் நம்பகமான ஆன்லைன் டாக்டர் புக்கிங் தளம்.</p>
                    <p>அருகிலுள்ள <strong>மருத்துவர்கள்</strong> மற்றும் <strong>மருத்துவமனைகளை</strong> தேடி, நிமிடங்களில் அப்பாயின்ட்மென்ட் பதிவு செய்யுங்கள்.</p>
                    <ul class="mb-0">
                        <li><strong>இடம், சிறப்பு துறை அல்லது நோய்</strong> அடிப்படையில் தேடலாம்</li>
                        <li><strong>ஆன்லைனில் அப்பாயின்ட்மென்ட்</strong> பதிவு செய்யலாம் — 24/7</li>
                        <li>இலவச <strong>Android ஆப்</strong> பதிவிறக்கம் செய்யலாம்</li>
                    </ul>
                </div>
                <!-- Telugu -->
                <div class="lang-pane" id="lang-te" style="display:none;">
                    <p><strong>RogiSewa.com</strong> కు స్వాగతం — భారతదేశంలో నమ్మకమైన ఆన్లైన్ డాక్టర్ బుకింగ్ వేదిక.</p>
                    <p>సమీపంలోని <strong>డాక్టర్లు</strong> మరియు <strong>ఆసుపత్రులను</strong> వెతికి, నిమిషాల్లో అపాయింట్మెంట్ బుక్ చేసుకోండి.</p>
                    <ul class="mb-0">
                        <li><strong>ప్రాంతం, నైపుణ్యం లేదా వ్యాధి</strong> ఆధారంగా వెతకవచ్చు</li>
                        <li><strong>ఆన్లైన్ అపాయింట్మెంట్</strong> బుక్ చేయవచ్చు — 24/7</li>
                        <li>ఉచిత <strong>Android యాప్</strong> డౌన్లోడ్ చేయవచ్చు</li>
                    </ul>
                </div>
                <!-- Marathi -->
                <div class="lang-pane" id="lang-mr" style="display:none;">
                    <p><strong>RogiSewa.com</strong> वर आपले स्वागत आहे — भारतातील विश्वासार्ह ऑनलाइन डॉक्टर बुकिंग प्लॅटफॉर्म.</p>
                    <p>जवळचे <strong>डॉक्टर</strong> आणि <strong>रुग्णालय</strong> शोधा, काही मिनिटांत ऑनलाइन अपॉइंटमेंट बुक करा.</p>
                    <ul class="mb-0">
                        <li><strong>स्थान, तज्ञता किंवा आजार</strong> यांच्या आधारे शोधा</li>
                        <li><strong>ऑनलाइन अपॉइंटमेंट बुक करा</strong> — 24/7</li>
                        <li>मोफत <strong>Android अॅप</strong> डाउनलोड करा</li>
                    </ul>
                </div>
                <!-- Bengali -->
                <div class="lang-pane" id="lang-bn" style="display:none;">
                    <p><strong>RogiSewa.com</strong> এ আপনাকে স্বাগতম — ভারতের বিশ্বস্ত অনলাইন ডাক্তার বুকিং প্ল্যাটফর্ম।</p>
                    <p>কাছের <strong>ডাক্তার</strong> ও <strong>হাসপাতাল</strong> খুঁজুন, মিনিটের মধ্যে অনলাইনে অ্যাপয়েন্টমেন্ট বুক করুন।</p>
                    <ul class="mb-0">
                        <li><strong>অবস্থান, বিশেষত্ব বা রোগ</strong> অনুযায়ী খুঁজুন</li>
                        <li><strong>অনলাইনে অ্যাপয়েন্টমেন্ট বুক করুন</strong> — ২৪/৭</li>
                        <li>বিনামূল্যে <strong>Android অ্যাপ</strong> ডাউনলোড করুন</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
</section>

<script>
// Language tab switcher
document.getElementById('langTabBtns').addEventListener('click', function(e) {
    var btn = e.target.closest('.about-tab-btn');
    if (!btn) return;

    var lang = btn.dataset.lang;

    // Toggle active class on buttons
    this.querySelectorAll('.about-tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    // Show/hide panes with fade
    document.querySelectorAll('#langTabContent .lang-pane').forEach(function(pane) {
        if (pane.id === 'lang-' + lang) {
            pane.style.display   = 'block';
            pane.style.opacity   = '0';
            pane.style.transform = 'translateY(8px)';
            pane.style.transition = 'opacity .28s ease, transform .28s ease';
            setTimeout(function() {
                pane.style.opacity   = '1';
                pane.style.transform = 'translateY(0)';
            }, 10);
        } else {
            pane.style.display = 'none';
        }
    });
});
</script>


<!-- ============================================================
     FEATURE CARDS
     ============================================================ -->
<section class="py-5" style="background:#fff;">
<div class="container">
    <div class="text-center mb-5 reveal">
        <div class="sec-label">What We Offer</div>
        <h2 class="sec-title">Everything You Need, In One Place</h2>
        <div class="sec-divider mx-auto"></div>
    </div>
    <div class="row g-4">
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.05s">
            <div class="feat-card">
                <div class="feat-icon"><i class="fa fa-user-md"></i></div>
                <h6>Qualified Doctors</h6>
                <p>Verified professionals across 50+ specializations</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.12s">
            <div class="feat-card">
                <div class="feat-icon"><i class="fa fa-calendar-check"></i></div>
                <h6>Online Booking</h6>
                <p>Instant appointment booking available 24/7</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.19s">
            <div class="feat-card">
                <div class="feat-icon"><i class="fa fa-mobile-alt"></i></div>
                <h6>Mobile App</h6>
                <p>Free Android app for health on the go</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.26s">
            <div class="feat-card">
                <div class="feat-icon"><i class="fa fa-hospital"></i></div>
                <h6>Hospitals</h6>
                <p>500+ listed hospitals and clinics across India</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.05s">
            <div class="feat-card">
                <div class="feat-icon" style="background:linear-gradient(135deg,#fdf4ff,#ede9fe);color:#7c3aed;"><i class="fa fa-bell"></i></div>
                <h6>Reminders</h6>
                <p>Appointment reminders via push notifications</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.12s">
            <div class="feat-card">
                <div class="feat-icon" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);color:#f97316;"><i class="fa fa-map-marker-alt"></i></div>
                <h6>Location Based</h6>
                <p>Find doctors and hospitals nearest to you</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.19s">
            <div class="feat-card">
                <div class="feat-icon" style="background:linear-gradient(135deg,#f0fff4,#d1fae5);color:#00b074;"><i class="fa fa-baby"></i></div>
                <h6>Pregnancy Tracker</h6>
                <p>Weekly updates and pregnancy health tips</p>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal" style="transition-delay:.26s">
            <div class="feat-card">
                <div class="feat-icon" style="background:linear-gradient(135deg,#fff1f2,#ffe4e6);color:#ef4444;"><i class="fa fa-heartbeat"></i></div>
                <h6>Health Tips</h6>
                <p>Curated blogs, disease guides and wellness articles</p>
            </div>
        </div>
    </div>
</div>
</section>




<!-- ============================================================
     MISSION / VISION / VALUES
     ============================================================ -->
<section class="py-5" style="background:#f8fafc;">
<div class="container">
    <div class="text-center mb-5 reveal">
        <div class="sec-label">Our Foundation</div>
        <h2 class="sec-title">Mission, Vision & Values</h2>
        <div class="sec-divider mx-auto"></div>
    </div>
    <div class="row g-4">
        <div class="col-md-4 reveal" style="transition-delay:.05s">
            <div class="mv-card mv-card-mission">
                <div class="mv-icon" style="background:linear-gradient(135deg,#0a6ebd,#60a5fa);color:#fff;">
                    <i class="fa fa-bullseye"></i>
                </div>
                <h5 style="font-weight:800;color:#0f172a;margin-bottom:10px;">Our Mission</h5>
                <p style="color:#475569;line-height:1.75;margin:0;">
                    To make quality healthcare <strong>accessible, transparent, and effortless</strong> for every patient in India — regardless of location or economic background.
                </p>
            </div>
        </div>
        <div class="col-md-4 reveal" style="transition-delay:.12s">
            <div class="mv-card mv-card-vision">
                <div class="mv-icon" style="background:linear-gradient(135deg,#00b074,#34d399);color:#fff;">
                    <i class="fa fa-eye"></i>
                </div>
                <h5 style="font-weight:800;color:#0f172a;margin-bottom:10px;">Our Vision</h5>
                <p style="color:#475569;line-height:1.75;margin:0;">
                    To be India's most trusted digital bridge between patients and healthcare providers, empowering <strong>millions of Indians</strong> to make timely health decisions.
                </p>
            </div>
        </div>
        <div class="col-md-4 reveal" style="transition-delay:.19s">
            <div class="mv-card mv-card-values">
                <div class="mv-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;">
                    <i class="fa fa-gem"></i>
                </div>
                <h5 style="font-weight:800;color:#0f172a;margin-bottom:10px;">Our Values</h5>
                <ul style="color:#475569;line-height:1.9;padding-left:18px;margin:0;">
                    <li><strong>Trust</strong> — Verified doctors and hospitals only</li>
                    <li><strong>Accessibility</strong> — Available in 6 languages</li>
                    <li><strong>Speed</strong> — Book in under 2 minutes</li>
                    <li><strong>Privacy</strong> — Patient data is always secure</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</section>


<!-- ============================================================
     HOW IT WORKS
     ============================================================ -->
<section class="py-5" style="background:#fff;">
<div class="container">
    <div class="text-center mb-5 reveal">
        <div class="sec-label">Simple Process</div>
        <h2 class="sec-title">Book a Doctor in 3 Easy Steps</h2>
        <div class="sec-divider mx-auto"></div>
    </div>
    <div class="step-wrap">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 reveal" style="transition-delay:.05s">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-icon-circle"><i class="fa fa-search"></i></div>
                    <h5 style="font-weight:700;color:#0f172a;">Search</h5>
                    <p style="color:#64748b;font-size:14.5px;">Search for doctors or hospitals by name, specialty, disease, or location across India.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:.14s">
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-icon-circle"><i class="fa fa-calendar-check"></i></div>
                    <h5 style="font-weight:700;color:#0f172a;">Book</h5>
                    <p style="color:#64748b;font-size:14.5px;">Select your preferred doctor, choose a time slot, and confirm your appointment — 24/7, no calls needed.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:.23s">
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-icon-circle"><i class="fa fa-stethoscope"></i></div>
                    <h5 style="font-weight:700;color:#0f172a;">Consult</h5>
                    <p style="color:#64748b;font-size:14.5px;">Visit the doctor at the scheduled time. Get reminders and manage your bookings from the RogiSewa app.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-5 reveal">
        <a href="{{ route('doctors') }}" class="btn btn-primary btn-lg px-5 py-3" style="border-radius:50px;font-weight:700;background:linear-gradient(135deg,#0a6ebd,#00b074);border:none;box-shadow:0 6px 24px rgba(10,110,189,.35);">
            <i class="fa fa-search me-2"></i> Find a Doctor Now
        </a>
    </div>
</div>
</section>


<!-- ============================================================
     WHY CHOOSE US
     ============================================================ -->
<section class="py-5" style="background:#f8fafc;">
<div class="container">
    <div class="row align-items-center g-5">
        <div class="col-lg-6 reveal-left">
            <div class="sec-label">Why RogiSewa?</div>
            <h2 class="sec-title">The Smarter Way to Access Healthcare</h2>
            <div class="sec-divider mb-4" style="margin-left:0;"></div>

            <div class="why-item">
                <div class="why-icon"><i class="fa fa-shield-alt"></i></div>
                <div>
                    <h6 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Verified & Trusted Doctors</h6>
                    <p style="color:#64748b;margin:0;font-size:14.5px;">Every doctor profile is reviewed and verified. Patients can rely on accurate information before booking.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-icon" style="background:linear-gradient(135deg,#00b074,#34d399);"><i class="fa fa-bolt"></i></div>
                <div>
                    <h6 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Instant Booking, No Waiting</h6>
                    <p style="color:#64748b;margin:0;font-size:14.5px;">Book appointments in under 2 minutes. No phone calls, no queues — just quick, easy scheduling online.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);"><i class="fa fa-globe"></i></div>
                <div>
                    <h6 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Multi-Language Support</h6>
                    <p style="color:#64748b;margin:0;font-size:14.5px;">Available in 6 Indian languages — Hindi, Tamil, Telugu, Marathi, Bengali, and English.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-icon" style="background:linear-gradient(135deg,#f97316,#fb923c);"><i class="fa fa-mobile-alt"></i></div>
                <div>
                    <h6 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Free Android App</h6>
                    <p style="color:#64748b;margin:0;font-size:14.5px;">Manage your health on the go with our free mobile app — appointments, reminders, and health tips in your pocket.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 reveal-right">
            <div class="timeline">
                <div class="tl-item reveal" style="transition-delay:.05s">
                    <div class="tl-dot"></div>
                    <div class="tl-card">
                        <span class="tl-year">2021</span>
                        <h6 style="font-weight:700;color:#0f172a;">Platform Founded</h6>
                        <p style="color:#64748b;font-size:14px;margin:0;">RogiSewa launched with a mission to simplify doctor discovery across India.</p>
                    </div>
                </div>
                <div class="tl-item reveal" style="transition-delay:.12s">
                    <div class="tl-dot"></div>
                    <div class="tl-card">
                        <span class="tl-year">2022</span>
                        <h6 style="font-weight:700;color:#0f172a;">Android App Launched</h6>
                        <p style="color:#64748b;font-size:14px;margin:0;">Free Android app released, giving patients mobile access to booking and health features.</p>
                    </div>
                </div>
                <div class="tl-item reveal" style="transition-delay:.19s">
                    <div class="tl-dot"></div>
                    <div class="tl-card">
                        <span class="tl-year">2023</span>
                        <h6 style="font-weight:700;color:#0f172a;">10,000+ Doctors Onboard</h6>
                        <p style="color:#64748b;font-size:14px;margin:0;">Reached a major milestone with verified doctors across all major Indian cities and towns.</p>
                    </div>
                </div>
                <div class="tl-item reveal" style="transition-delay:.26s">
                    <div class="tl-dot"></div>
                    <div class="tl-card">
                        <span class="tl-year">2024+</span>
                        <h6 style="font-weight:700;color:#0f172a;">Expanding Nationally</h6>
                        <p style="color:#64748b;font-size:14px;margin:0;">Now covering 28+ states with pregnancy tracking, vaccine reminders, and more health features.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>


<!-- ============================================================
     APP DOWNLOAD
     ============================================================ -->
<section class="app-section py-5">
<div class="container">
    <div class="row align-items-center g-5">

        <div class="col-lg-7 reveal-left">
            <div class="sec-label">Mobile App</div>
            <h2 class="sec-title">Book Doctors Anytime,<br>Anywhere</h2>
            <div class="sec-divider mb-4" style="margin-left:0;"></div>

            <div class="app-feat-item">
                <div class="app-feat-icon"><i class="fa fa-search"></i></div>
                <div>
                    <strong style="color:#0f172a;">Search by location, specialty, or disease</strong>
                    <p style="color:#64748b;font-size:14px;margin:0;">Find the right doctor instantly using smart filters.</p>
                </div>
            </div>
            <div class="app-feat-item">
                <div class="app-feat-icon"><i class="fa fa-calendar-check"></i></div>
                <div>
                    <strong style="color:#0f172a;">Instant appointment booking</strong>
                    <p style="color:#64748b;font-size:14px;margin:0;">Confirm bookings in under 2 minutes, no calls needed.</p>
                </div>
            </div>
            <div class="app-feat-item">
                <div class="app-feat-icon"><i class="fa fa-bell"></i></div>
                <div>
                    <strong style="color:#0f172a;">Reminders & notifications</strong>
                    <p style="color:#64748b;font-size:14px;margin:0;">Never miss an appointment with smart push reminders.</p>
                </div>
            </div>
            <div class="app-feat-item">
                <div class="app-feat-icon"><i class="fa fa-baby"></i></div>
                <div>
                    <strong style="color:#0f172a;">Pregnancy & vaccine tracker</strong>
                    <p style="color:#64748b;font-size:14px;margin:0;">Weekly pregnancy updates and child vaccination schedule.</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener"
                   style="display:inline-block;transition:transform .3s;"
                   onmouseover="this.style.transform='translateY(-4px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                         alt="Download RogiSewa on Google Play" style="height:52px;">
                </a>
            </div>
        </div>

        <div class="col-lg-5 text-center reveal-right">
            <div class="app-mockup">
                <div style="font-size:72px;line-height:1;">📱</div>
                <h4 style="color:#fff;font-weight:800;margin-top:16px;">RogiSewa App</h4>
                <p style="color:rgba(255,255,255,.85);margin-bottom:24px;font-size:15px;">
                    Search • Book • Consult<br>all from your smartphone
                </p>
                <div style="display:flex;justify-content:center;gap:20px;margin-bottom:20px;">
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:#fff;">Free</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.7);">to Download</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,.2);"></div>
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:#fff;">24/7</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.7);">Available</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,.2);"></div>
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:#fff;">4.5★</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.7);">Play Store</div>
                    </div>
                </div>
                <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener"
                   class="btn btn-light btn-lg px-4" style="border-radius:50px;font-weight:700;color:#0a6ebd;">
                    <i class="fab fa-google-play me-2"></i> Download Free
                </a>
            </div>
        </div>

    </div>
</div>
</section>


<!-- ============================================================
     OUR DOCTORS
     ============================================================ -->
<section class="py-5" style="background:#fff;">
<div class="container">
    <div class="text-center mb-5 reveal">
        <div class="sec-label">Healthcare Professionals</div>
        <h2 class="sec-title">Meet Our Doctors</h2>
        <div class="sec-divider mx-auto"></div>
    </div>

    <div class="owl-carousel team-carousel position-relative">
        @foreach($doctors as $doctor)
            @php $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name; @endphp
            <div class="team-item">
                <div class="doc-carousel-card">
                    <div style="position:relative;overflow:hidden;height:220px;">
                        <img class="w-100 h-100"
                             src="{{ $doctor->profile_pic ? asset('storage/upload/doctor/'.$doctor->profile_pic) : asset('storage/upload/doctor/user.jpg') }}"
                             style="object-fit:cover;transition:transform .5s;"
                             onmouseover="this.style.transform='scale(1.07)'"
                             onmouseout="this.style.transform='scale(1)'"
                             alt="{{ $practiceName }}">
                        <div style="position:absolute;bottom:0;left:0;right:0;height:80px;background:linear-gradient(transparent,rgba(0,0,0,.5));"></div>
                    </div>
                    <div style="padding:20px;">
                        <h6 style="font-weight:800;color:#0f172a;margin-bottom:4px;">
                            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}" style="color:inherit;text-decoration:none;">
                                {{ $practiceName }}
                            </a>
                        </h6>
                        <p style="color:#0a6ebd;font-size:13px;font-weight:600;margin-bottom:8px;">
                            {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                        </p>
                        <p style="color:#64748b;font-size:13px;margin-bottom:10px;">
                            {{ $doctor->educations->first()->degree ?? 'Experienced Healthcare Professional' }}
                        </p>
                        @php $location = $doctor->locations->first(); @endphp
                        @if($location)
                        <p style="color:#94a3b8;font-size:12px;margin-bottom:12px;">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                            {{ $location->city }}, {{ $location->state }}
                        </p>
                        @endif
                        <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}"
                           class="btn btn-sm w-100"
                           style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;border:none;border-radius:10px;font-weight:600;padding:8px;">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-4 reveal">
        <a href="{{ route('professional.doctors') }}" class="btn btn-outline-primary px-5"
           style="border-radius:50px;font-weight:700;border-width:2px;">
            View All Doctors <i class="fa fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>
</section>


<!-- ============================================================
     SEO CONTENT
     ============================================================ -->
<section class="py-5 article-section" style="background:#f8fafc;">
<div class="container">
    <div class="text-center mb-5 reveal">
        <div class="sec-label">Learn More</div>
        <h2 class="sec-title">Healthcare Made Simple</h2>
        <div class="sec-divider mx-auto"></div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 reveal" style="transition-delay:.05s">
            <div style="background:#fff;border-radius:18px;padding:28px;box-shadow:0 4px 20px rgba(0,0,0,.07);height:100%;border-top:4px solid #0a6ebd;">
                <h2>How to Book a Doctor Appointment Online</h2>
                <p>With <strong>RogiSewa</strong>, patients across India can search for qualified doctors, view their availability, and confirm appointments in just a few clicks — without waiting in long queues or making phone calls.</p>
                <p>Our platform supports booking for general physicians, specialists, dentists, dermatologists, cardiologists, orthopedic surgeons, and many more.</p>
            </div>
        </div>
        <div class="col-md-6 reveal" style="transition-delay:.12s">
            <div style="background:#fff;border-radius:18px;padding:28px;box-shadow:0 4px 20px rgba(0,0,0,.07);height:100%;border-top:4px solid #00b074;">
                <h2>How to Choose the Right Doctor</h2>
                <p>RogiSewa helps simplify this by allowing users to compare doctors based on specialization, city, and healthcare needs. By accessing verified information, patients can confidently choose the most suitable healthcare professional.</p>
                <p>Consider factors such as specialization, experience, location, and availability before making a decision.</p>
            </div>
        </div>
        <div class="col-md-6 reveal" style="transition-delay:.05s">
            <div style="background:#fff;border-radius:18px;padding:28px;box-shadow:0 4px 20px rgba(0,0,0,.07);height:100%;border-top:4px solid #7c3aed;">
                <h2>Benefits of Online Doctor Booking</h2>
                <p>Online doctor search and booking platforms have transformed the way patients access healthcare. With RogiSewa, users can save time, compare options, book appointments 24/7, and make informed decisions.</p>
                <p>Online healthcare discovery also improves accessibility for patients in remote or unfamiliar locations across India.</p>
            </div>
        </div>
        <div class="col-md-6 reveal" style="transition-delay:.12s">
            <div style="background:#fff;border-radius:18px;padding:28px;box-shadow:0 4px 20px rgba(0,0,0,.07);height:100%;border-top:4px solid #f97316;">
                <h2>Healthcare in India – Digital Solutions</h2>
                <p>Digital platforms like RogiSewa address challenges such as limited access to verified information, uneven distribution of medical facilities, and lack of healthcare awareness.</p>
                <p>By making doctor and hospital information easily accessible along with online booking and a mobile app, RogiSewa supports better healthcare awareness for millions of Indians.</p>
            </div>
        </div>
    </div>
</div>
</section>


<!-- ============================================================
     FINAL CTA
     ============================================================ -->
<section class="py-5" style="background:#fff;">
<div class="container reveal">
    <div class="cta-block">
        <h2 style="color:#fff;font-weight:800;font-size:clamp(1.6rem,4vw,2.4rem);margin-bottom:12px;">
            Your Health, Your Choice
        </h2>
        <p style="color:rgba(255,255,255,.88);max-width:540px;margin:0 auto 28px;font-size:16px;">
            Join millions of patients who trust RogiSewa to find the right doctor at the right time.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('doctors') }}" class="btn btn-light btn-lg px-5"
               style="border-radius:50px;font-weight:700;color:#0a6ebd;">
                <i class="fa fa-search me-2"></i> Find a Doctor
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener"
               class="btn btn-outline-light btn-lg px-5" style="border-radius:50px;font-weight:700;">
                <i class="fab fa-google-play me-2"></i> Download App
            </a>
        </div>
    </div>
</div>
</section>


<!-- ============================================================
     SCRIPTS — Scroll Reveal + Counter
     ============================================================ -->
<script>
(function () {

    /* ---------- Scroll Reveal ---------- */
    const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    const observer  = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => observer.observe(el));

    /* ---------- Counter Animation ---------- */
    function animateCounter(el) {
        const target   = parseInt(el.dataset.target, 10);
        const duration = 2000;
        const step     = Math.ceil(target / (duration / 16));
        let current    = 0;
        const tick = () => {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString('en-IN');
            if (current < target) requestAnimationFrame(tick);
        };
        tick();
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animateCounter(e.target);
                counterObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter, .counter-anim').forEach(el => counterObserver.observe(el));

})();
</script>

@endsection
