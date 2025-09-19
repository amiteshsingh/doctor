
@extends('page.layouts.app')

@section('title', 'RogiSewa - About')

@section('content')
    <!-- About Start -->
 
    <div class="container-fluid py-5">
        <div class="container">

         <div class="text-center mb-5">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">About Us</h5>
                <h2 class="mt-3">Best Medical Care for You and Your Family</h2>
            </div>

            <div class="row gx-5">
                <!-- Image Section -->
                <div class="col-lg-4 mb-4 mb-lg-0" style="min-height: 500px;">
                    <div class="position-relative h-50">
                        <img class="position-absolute w-100 h-100 rounded" src="img/about.jpg" style="object-fit: cover;" alt="About Us">
                    </div>
                </div>

                <!-- Content Section -->
                <div class="col-lg-8">
                  

                    <!-- About Us Tabs (Bootstrap 4) -->
                    <!-- About Us Tabs (Bootstrap 5) -->
                    <div class="container">
                   
                    <ul class="nav nav-tabs" id="aboutTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button" role="tab" aria-controls="en" aria-selected="true">
                            English
                        </button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hi-tab" data-bs-toggle="tab" data-bs-target="#hi" type="button" role="tab" aria-controls="hi" aria-selected="false">
                            हिंदी
                        </button>
                        </li>
                    </ul>

                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="aboutTabsContent">
                        <!-- English -->
                        <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <p>
                            Welcome to <strong>RogiSewa.com</strong> – your trusted partner in finding the best medical care for yourself and your family.
                        </p>
                        <p>
                            Our purpose is simple yet meaningful – to make quality healthcare easily accessible for everyone. Through our platform, any user can quickly search for the nearest <strong>doctors</strong> and <strong>hospitals</strong>, explore their specializations, and connect with the right healthcare professional for their needs.
                        </p>
                        <p>We understand that when it comes to health, every second counts. That’s why we provide a user-friendly platform where you can:</p>
                        <ul class="mb-3">
                            <li>Search for doctors and hospitals by <strong>location, specialty, or disease</strong></li>
                            <li>Find verified and reliable information about medical experts</li>
                            <li>Choose the right healthcare professional for your patient or family member</li>
                        </ul>
                        <p>
                            At <strong>RogiSewa.com</strong>, our mission is to bridge the gap between patients and healthcare providers. Whether you are looking for routine check-ups, specialized treatments, or emergency care – we are here to help you make informed decisions and ensure your loved ones receive the best medical care at the right time.
                        </p>
                        <p>Your health, your choice – and we are here to make it simpler.</p>
                        </div>

                        <!-- Hindi -->
                        <div class="tab-pane fade" id="hi" role="tabpanel" aria-labelledby="hi-tab">
                        <p>
                            <strong>RogiSewa.com</strong> पर आपका स्वागत है – आपके और आपके परिवार के लिए बेहतरीन स्वास्थ्य सेवाओं की खोज का भरोसेमंद साथी।
                        </p>
                        <p>
                            हमारा उद्देश्य सरल है – हर किसी तक गुणवत्तापूर्ण स्वास्थ्य सेवाएँ आसानी से पहुँचाना। इस प्लेटफ़ॉर्म के माध्यम से कोई भी यूज़र अपने नज़दीकी <strong>डॉक्टर</strong> और <strong>अस्पताल</strong> खोज सकता है, उनकी विशेषज्ञता देख सकता है और अपनी ज़रूरत के अनुसार सही डॉक्टर से जुड़ सकता है।
                        </p>
                        <p>हम जानते हैं कि स्वास्थ्य के मामलों में हर पल मायने रखता है। इसलिए हमने ऐसा प्लेटफ़ॉर्म बनाया है जहाँ आप:</p>
                        <ul class="mb-3">
                            <li><strong>स्थान, विशेषज्ञता या बीमारी</strong> के आधार पर डॉक्टर और अस्पताल खोज सकते हैं</li>
                            <li>चिकित्सा विशेषज्ञों की विश्वसनीय और प्रमाणित जानकारी प्राप्त कर सकते हैं</li>
                            <li>अपने या अपने प्रियजनों के लिए सही स्वास्थ्य सेवाओं का चुनाव कर सकते हैं</li>
                        </ul>
                        <p>
                            <strong>RogiSewa.com</strong> का मिशन है – मरीज और स्वास्थ्य सेवा प्रदाताओं के बीच की दूरी को कम करना। चाहे आपको सामान्य जाँच, विशेष इलाज या आपातकालीन देखभाल की ज़रूरत हो – हम आपके निर्णय को आसान बनाते हैं ताकि आपके प्रियजनों को समय पर सर्वोत्तम चिकित्सा सेवाएँ मिल सकें।
                        </p>
                        <p>आपका स्वास्थ्य, आपका निर्णय – और हम आपके साथ हैं।</p>
                        </div>
                    </div>
                    </div>


                    <!-- Features Row -->
                    <div class="row g-3 pt-3" >
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-user-md text-primary mb-3"></i>
                                <h6 class="mb-0">Qualified<small class="d-block text-primary">Doctors</small></h6>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-procedures text-primary mb-3"></i>
                                <h6 class="mb-0">Emergency<small class="d-block text-primary">Services</small></h6>
                            </div>
                        </div>
                        <!-- <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-microscope text-primary mb-3"></i>
                                <h6 class="mb-0">Accurate<small class="d-block text-primary">Testing</small></h6>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-ambulance text-primary mb-3"></i>
                                <h6 class="mb-0">Free<small class="d-block text-primary">Ambulance</small></h6>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
  

    <!-- About End -->




   <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Doctors</h5>
                <h2 class="display-7">Qualified Healthcare Professionals</h2>
            </div>

            <div class="owl-carousel team-carousel position-relative">
                @foreach($doctors as $doctor)
                    @php
                        $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
                    @endphp
                    <div class="team-item">
                        <div class="row g-0 bg-light rounded overflow-hidden">
                            <div class="col-12 col-sm-5 h-100">
                                <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">
                                <img class="img-fluid h-100" 
                                    src="{{ $doctor->profile_pic ?asset('storage/upload/doctor/'.$doctor->profile_pic) : asset('storage/upload/doctor/user.jpg') }}" 
                                    style="object-fit: cover;">
                                </a>
                            </div>
                            <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                <div class="mt-auto p-4">
                                    <h3> <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">{{ $practiceName }}</a></h3>
                                    <h6 class="fw-normal fst-italic text-primary mb-2">
                                        {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                                    </h6>
                                    <p class="mb-2">
                                        {{ $doctor->educations->first()->degree ?? 'Experienced Healthcare Professional' }}
                                    </p>

                                    @php
                                        $location = $doctor->locations->first();
                                    @endphp
                                    @if($location)
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            {{ $location->address }},
                                            {{ $location->city }},
                                            {{ $location->state }} - {{ $location->zip_code }}
                                        </p>
                                    @else
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            Address not available
                                        </p>
                                    @endif
                                </div>

                                <div class="d-flex mt-auto border-top p-4">
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="{{ $doctor->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="{{ $doctor->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="{{ $doctor->linkedin ?? '#' }}"><i class="fab fa-linkedin-in"></i></a>
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
    <!-- Team End -->

    @endsection