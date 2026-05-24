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
