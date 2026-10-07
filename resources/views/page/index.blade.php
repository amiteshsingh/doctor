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

<!-- ── HERO ── -->
<div class="container-fluid bg-primary py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row justify-content-start">
            <div class="col-lg-8 text-center text-lg-start">
                <h5 class="d-inline-block text-white text-uppercase border-bottom border-5"
                    style="border-color:rgba(256,256,256,.3)!important;">
                    Welcome To RogiSewa
                </h5>
                <h1 class="display-1 text-white mb-md-4">
                    Find Trusted Doctors & Hospitals Near You
                </h1>
                <p class="text-white mb-4" style="font-size:16px;opacity:.9;">
                    Search verified doctors by specialization, compare clinics, and book appointments — all in one place. Serving patients across India.
                </p>
                <div class="pt-2">
                    <a href="{{ url('doctors') }}" class="btn btn-light rounded-pill py-md-3 px-md-5 mx-2">
                        <i class="fa fa-search me-2"></i> Find Doctor
                    </a>
                    <a href="{{ url('hospitals') }}" class="btn btn-outline-light rounded-pill py-md-3 px-md-5 mx-2">
                        <i class="fa fa-hospital-o me-2"></i> Find Hospital
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── ROGISEWA APP SECTION ── -->
<div class="container-fluid py-5" style="background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);">
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
<!-- ── STATS BAR ── -->
<div class="container mb-5">
    <div class="row text-center g-4">
        <div class="col-6 col-md-3">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <i class="fa fa-user-md fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold text-primary mb-0">{{ $totalDoctors }}+</h3>
                <p class="text-muted mb-0" style="font-size:13px;">Verified Doctors</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <i class="fa fa-hospital-o fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold text-primary mb-0">{{ $totalHospitals }}+</h3>
                <p class="text-muted mb-0" style="font-size:13px;">Hospitals &amp; Clinics</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <i class="fa fa-stethoscope fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold text-primary mb-0">{{ $totalSpecializations }}+</h3>
                <p class="text-muted mb-0" style="font-size:13px;">Specializations</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <i class="fa fa-users fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold text-primary mb-0">Free</h3>
                <p class="text-muted mb-0" style="font-size:13px;">Prescription Invoice</p>
            </div>
        </div>
    </div>
</div>

<!-- ── DOCTORS CAROUSEL ── -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Doctors</h5>
            <h2 class="display-7">Meet Our Qualified Healthcare Professionals</h2>
            <p class="text-muted mt-3">
                Browse experienced doctors from various medical specialties. Each profile includes qualifications, location, and specialization details to help you choose the right doctor.
            </p>
        </div>

        <div class="owl-carousel team-carousel position-relative">
            @foreach($doctors as $doctor)
                @php
                    $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
                @endphp
                <div class="team-item">
                    <div class="row g-0 bg-light rounded overflow-hidden">
                        <div class="col-12 col-sm-5 h-100">
                            <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}">
                                <img class="img-fluid h-100"
                                     src="{{ $doctor->profile_pic ? asset('storage/upload/doctor/'.$doctor->profile_pic) : asset('storage/upload/doctor/user.jpg') }}"
                                     alt="Dr. {{ $practiceName }} - {{ $doctor->specializations->first()->specialization->name ?? 'Doctor' }} in India"
                                     style="object-fit:cover;">
                            </a>
                        </div>
                        <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                            <div class="mt-auto p-4">
                                <h3>{{ $practiceName }}</h3>
                                <h6 class="fw-normal fst-italic text-primary mb-2">
                                    {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                                </h6>
                                <p>{{ $doctor->educations->first()->degree ?? 'Experienced Healthcare Professional' }}</p>
                                @php $location = $doctor->locations->first(); @endphp
                                <p class="text-muted small">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    {{ $location ? $location->city.', '.$location->state : 'India' }}
                                </p>
                                <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}"
                                   class="btn btn-primary btn-sm rounded-pill">View Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('professional.doctors') }}" class="btn btn-light btn-sm">
                View All Doctors <i class="fa fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- ── HOW IT WORKS ── -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Simple Steps</h5>
        <h2 class="display-7">How RogiSewa Works</h2>
    </div>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;font-size:22px;">
                    <i class="fa fa-search"></i>
                </div>
                <div class="badge bg-primary rounded-pill mb-2">Step 1</div>
                <h5 class="fw-bold">Search</h5>
                <p class="text-muted" style="font-size:14px;">Search doctors by name, specialization, or city. Filter results to find the most relevant healthcare professional for your needs.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;font-size:22px;">
                    <i class="fa fa-user-md"></i>
                </div>
                <div class="badge bg-primary rounded-pill mb-2">Step 2</div>
                <h5 class="fw-bold">Compare Profiles</h5>
                <p class="text-muted" style="font-size:14px;">View detailed doctor profiles including qualifications, experience, clinic location, and available specializations.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;font-size:22px;">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
                <div class="badge bg-primary rounded-pill mb-2">Step 3</div>
                <h5 class="fw-bold">Book Appointment</h5>
                <p class="text-muted" style="font-size:14px;">Contact the doctor directly or book an appointment through the platform. Get the care you need without unnecessary delays.</p>
            </div>
        </div>
    </div>
</div>

<!-- ── WHY ROGISEWA ── -->
<div class="container-fluid bg-primary py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Why Choose Us</h5>
                <h2 class="text-white mt-3 mb-4">Why Patients Trust RogiSewa</h2>
                <p class="text-white">
                    RogiSewa was built to solve a real problem — patients in India often struggle to find the right doctor quickly. We provide a structured, transparent, and easy-to-use platform that puts verified healthcare information at your fingertips.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">Verified doctor &amp; hospital profiles</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">Search by specialization &amp; location</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">Free prescription invoice generation</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">Transparent healthcare information</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">No hidden fees or charges</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center gap-2 text-white"><i class="fa fa-check-circle text-warning"></i><span style="font-size:13.5px;">Available across India</span></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white rounded-4 p-4 shadow">
                    <h5 class="fw-bold text-primary mb-4">Search Doctors by Specialization</h5>
                    <div class="row g-2">
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> General Medicine</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Cardiology</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Pediatrics</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Orthopedics</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Gynecology</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Dermatology</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Neurology</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> ENT</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Ophthalmology</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Dentistry</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Psychiatry</a></div>
                        <div class="col-6"><a href="{{ url('doctors') }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:#f0f7ff;color:#0a6ebd;font-size:13px;font-weight:600;"><i class="fa fa-stethoscope" style="font-size:11px;"></i> Urology</a></div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url('doctors') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                            View All Specializations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── HEALTH TIPS ── -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Health Tips</h5>
        <h2 class="display-7">Important Healthcare Tips for Patients</h2>
        <p class="text-muted mt-2">Simple but effective health practices that every patient should know.</p>
    </div>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(239,68,68,.1);">
                        <i class="fa fa-heartbeat" style="color:#ef4444;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Regular Health Checkups</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Schedule routine health checkups at least once a year. Early detection of health conditions significantly improves treatment outcomes and reduces long-term medical costs.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(10,110,189,.1);">
                        <i class="fa fa-user-md" style="color:#0a6ebd;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Choose the Right Specialist</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Always consult a specialist relevant to your health concern. A cardiologist for heart issues, an orthopedic for bone problems — the right specialist ensures accurate diagnosis.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(0,176,116,.1);">
                        <i class="fa fa-file-text-o" style="color:#00b074;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Maintain Medical Records</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Keep a record of your prescriptions, test reports, and medical history. Organized health records help doctors provide better and faster treatment during consultations.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(245,158,11,.1);">
                        <i class="fa fa-map-marker" style="color:#f59e0b;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Find Nearby Healthcare</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Choosing a doctor or hospital close to your location ensures timely access to care, easier follow-up visits, and faster emergency response when needed.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(124,58,237,.1);">
                        <i class="fa fa-shield" style="color:#7c3aed;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Verify Doctor Credentials</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Always check a doctor qualifications, registration, and specialization before booking an appointment. Verified credentials ensure you receive safe and professional medical care.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100 d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(240,147,251,.1);">
                        <i class="fa fa-comments" style="color:#f093fb;font-size:18px;"></i>
                    </div>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Communicate Openly</h6>
                    <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">Share your complete medical history, current medications, and symptoms clearly with your doctor. Open communication leads to more accurate diagnosis and effective treatment plans.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ── FOR DOCTORS CTA ── -->
<div class="container-fluid bg-primary py-5">
    <div class="container text-center">
        <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">For Doctors</h5>
        <h2 class="text-white mt-3 mb-3">Are You a Doctor or Hospital?</h2>
        <p class="text-white mb-4" style="max-width:600px;margin:0 auto 24px;">
            Register your clinic or hospital on RogiSewa and connect with thousands of patients across India. Get a verified profile, manage prescriptions digitally, and grow your practice online.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('register') }}" class="btn btn-light rounded-pill py-2 px-5 fw-bold">
                <i class="fa fa-user-plus me-2"></i> Register as Doctor
            </a>
            <a href="{{ url('contact') }}" class="btn btn-outline-light rounded-pill py-2 px-5">
                <i class="fa fa-envelope me-2"></i> Contact Us
            </a>
        </div>
        <div class="mt-4 text-white" style="font-size:13px;opacity:.85;">
            ✅ Free prescription invoice &nbsp;|&nbsp; ✅ Doctor profile listing &nbsp;|&nbsp; ✅ Hospital management &nbsp;|&nbsp; ✅ Staff attendance tracking
        </div>
    </div>
</div>

<!-- ── DISCLAIMER ── -->
<div class="container py-4">
    <div class="p-4 rounded-4" style="background:#f8fbff;border:1px solid #e2e8f0;">
        <h6 class="fw-bold text-primary mb-2"><i class="fa fa-info-circle me-2"></i>Important Disclaimer</h6>
        <p class="text-muted mb-0" style="font-size:13px;line-height:1.7;">
            RogiSewa is a healthcare information and discovery platform. We do not provide medical advice, diagnosis, or treatment. The information displayed on this platform is for informational purposes only. Always consult a qualified and registered healthcare professional for medical advice, diagnosis, and treatment. In case of a medical emergency, please contact your nearest hospital or call emergency services immediately.
        </p>
    </div>
</div>

<!-- Registration Popup Modal -->
<div class="modal fade" id="registrationPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bold">
                    📢 Doctor & Hospital Registration / डॉक्टर व हॉस्पिटल रजिस्ट्रेशन
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    <b>RogiSewa.com</b> पर अपने क्लिनिक या हॉस्पिटल को रजिस्टर करें और पूरे <b>भारत</b> में मरीजों तक आसानी से पहुँचें।
                </p>
                <p class="mb-3">
                    Register your clinic or hospital on <b>RogiSewa.com</b> and connect with patients across <b>India</b>.
                </p>
                <ul class="list-unstyled">
                    <li>✅ पूरे भारत में अपने क्लिनिक और हॉस्पिटल की पहचान बनाएँ</li>
                    <li>✅ मरीज सीधे आपसे संपर्क कर सकेंगे</li>
                    <li>✅ अपनी विशेषज्ञता और मेडिकल सेवाओं का प्रचार करें</li>
                    <li>✅ <b>Prescription Invoice Generation सेवा बिल्कुल FREE है</b></li>
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="{{ url('register') }}" class="btn btn-success fw-bold">
                    👉 Register Now / अभी रजिस्टर करें
                </a>
                <button type="button" class="btn btn-danger fw-bold" data-bs-dismiss="modal">
                    ❌ Close / बंद करें
                </button>
            </div>
        </div>
    </div>
</div>

@endsection