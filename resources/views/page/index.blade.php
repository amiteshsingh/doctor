@extends('page.layouts.app')

@section('title', 'RogiSewa - Home')

@section('content')

<!-- Hero Start -->
<div class="container-fluid bg-primary py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row justify-content-start">
            <div class="col-lg-8 text-center text-lg-start">
                <h5 class="d-inline-block text-white text-uppercase border-bottom border-5"
                    style="border-color: rgba(256,256,256,.3)!important;">
                    Welcome To RogiSewa
                </h5>
                <h1 class="display-1 text-white mb-md-4">
                    Best Healthcare Solution In Your City
                </h1>
                <div class="pt-2">
                    <a href="{{ url('doctors') }}" class="btn btn-light rounded-pill py-md-3 px-md-5 mx-2">
                        Find Doctor
                    </a>
                    <a href="{{ url('hospitals') }}" class="btn btn-light rounded-pill py-md-3 px-md-5 mx-2">
                        Find Hospital
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->

<!-- Doctors Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">
                Our Doctors
            </h5>
            <h2 class="display-7">Qualified Healthcare Professionals</h2>
            <p class="text-muted mt-3">
                RogiSewa connects patients with experienced doctors from various medical specialties,
                helping them access reliable healthcare services with ease.
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
                                     alt="{{ $practiceName }}" style="object-fit:cover;">
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
                                    {{ $location ? $location->city.', '.$location->state : 'Location not available' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('professional.doctors') }}" class="btn btn-light btn-sm">
                View More <i class="fa fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>
<!-- Doctors End -->

<!-- Informational Section -->
<div class="container-fluid bg-primary py-5">
    <div class="container py-5">
        <div class="text-center mx-auto" style="max-width:800px;">
            <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">
                Find a Doctor
            </h5>
            <h2 class="text-white mb-4">
                Trusted Healthcare Information & Medical Discovery Platform
            </h2>

            <p class="text-white">
                RogiSewa is designed to simplify the process of finding reliable healthcare services.
                Patients can explore doctors, hospitals, and clinics based on location, specialization,
                and healthcare needs.
            </p>

            <p class="text-white">
                We understand that timely medical care is essential. Our platform ensures that patients
                can access accurate and structured healthcare information to make informed decisions.
            </p>

            <p class="text-white">
                RogiSewa focuses on improving healthcare awareness and accessibility by connecting
                patients with verified healthcare professionals across India.
            </p>
        </div>
    </div>
</div>

<!-- Extra Content Section -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <h3>Why Healthcare Awareness Matters</h3>
            <p>
                Healthcare awareness helps individuals recognize symptoms early and seek timely medical
                attention. Understanding healthcare options enables patients to choose the right doctor
                or hospital and avoid unnecessary delays in treatment.
            </p>
        </div>
        <div class="col-md-6 mb-4">
            <h3>How RogiSewa Supports Patients</h3>
            <p>
                RogiSewa provides organized healthcare data that helps patients compare doctors and
                healthcare facilities. Our platform promotes transparency, convenience, and informed
                healthcare decisions.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Our Vision for Better Healthcare Access</h3>
            <p>
                Our vision is to create a trusted healthcare discovery ecosystem where patients can
                easily find medical professionals and healthcare services. RogiSewa aims to reduce
                healthcare information gaps and improve patient outcomes through awareness and access.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>How to Choose the Right Doctor</h3>
            <p> Choosing the right doctor is one of the most important steps toward better health. Patients should always consider the doctor’s specialization, years of experience, and qualifications before booking an appointment. A doctor who specializes in your specific health concern can provide more accurate diagnosis and effective treatment.

Location and availability are also important factors. Selecting a doctor near your home or workplace helps in easy follow-ups and timely medical care. Patient reviews and ratings can give valuable insight into the doctor’s approach, behavior, and treatment success.

RogiSewa makes this process simple by allowing patients to search and compare doctors based on specialization, location, and experience. Our platform helps patients make confident healthcare decisions without confusion or unnecessary delays.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Benefits of Online Doctor Search</h3>
            <p>
               Online doctor search platforms have transformed the way patients access healthcare services. Instead of visiting multiple clinics or relying on limited local information, patients can explore a wide range of doctors and hospitals from a single platform.

One of the biggest advantages is time savings. Patients can search, compare, and choose healthcare professionals without long waiting hours. Online platforms also improve transparency by providing verified doctor profiles, clinic details, and healthcare information.

RogiSewa helps patients discover trusted doctors and hospitals across India. By offering structured and reliable healthcare data, we ensure that patients receive the right medical support at the right time.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Healthcare in India – Challenges & Solutions</h3>
            <p>
              India’s healthcare system faces several challenges, including lack of awareness, uneven access to medical facilities, and difficulty in finding trusted healthcare professionals. Many patients struggle to identify the right doctor or hospital for their medical needs.

Digital healthcare platforms play a key role in bridging this gap. By organizing healthcare information and improving accessibility, online platforms empower patients to make informed choices. Easy access to healthcare data reduces delays in treatment and improves overall health outcomes.

RogiSewa aims to address these challenges by creating a transparent and reliable healthcare discovery platform. Our goal is to support patients with accurate information and connect them with verified medical professionals across India.
            </p>
        </div>
    </div>

</div>





<!-- ================= Registration Popup Modal ================= -->

<div class="modal fade" id="registrationPopup" tabindex="-1"
     aria-labelledby="registrationPopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 shadow">

            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bold" id="registrationPopupLabel">
                    📢 Doctor & Hospital Registration / डॉक्टर व हॉस्पिटल रजिस्ट्रेशन
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    <b>RogiSewa.com</b> पर अपने क्लिनिक या हॉस्पिटल को रजिस्टर करें और
                    पूरे <b>भारत</b> में मरीजों तक आसानी से पहुँचें।
                </p>

                <p class="mb-3">
                    Register your clinic or hospital on <b>RogiSewa.com</b> and connect with
                    patients across <b>India</b>. Our platform helps healthcare professionals
                    build trust, visibility, and patient reach.
                </p>

                <ul class="list-unstyled">
                    <li>✅ पूरे भारत में अपने क्लिनिक और हॉस्पिटल की पहचान बनाएँ</li>
                    <li>✅ मरीज सीधे आपसे संपर्क कर सकेंगे</li>
                    <li>✅ अपनी विशेषज्ञता और मेडिकल सेवाओं का प्रचार करें</li>
                    <li>✅ <b>Prescription Invoice Generation सेवा बिल्कुल FREE है</b></li>
                </ul>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <a href="https://rogisewa.com/register"
                   class="btn btn-success fw-bold">
                    👉 Register Now / अभी रजिस्टर करें
                </a>
                <button type="button" class="btn btn-danger fw-bold"
                        data-bs-dismiss="modal">
                    ❌ Close / बंद करें
                </button>
            </div>

        </div>
    </div>
</div>




<script>

// document.addEventListener("DOMContentLoaded", function () {
//     setTimeout(function () {
//         var myModal = new bootstrap.Modal(
//             document.getElementById('registrationPopup')
//         );
//         myModal.show();
//     }, 2000); // 2 seconds delay
// });

</script>


@endsection
