@extends('page.layouts.app')

@section('title', 'About RogiSewa – Online Doctor Booking App | Find Doctors & Hospitals in India')
@section('meta_description', 'RogiSewa is India\'s trusted online doctor booking platform. Search doctors by specialty or location, book appointments instantly, and download our free Android app.')

@section('content')

<style>
.custom-tabs .nav-link {
    border-radius: 30px;
    padding: 8px 18px;
    margin: 5px;
    font-weight: 500;
    color: #333;
    background: #f1f3f5;
    border: none;
    transition: all 0.3s ease;
}
.custom-tabs .nav-link:hover { background: #13C5DD; transform: translateY(-2px); }
.step-icon {
    width: 64px; height: 64px;
    background: #e8f4fd; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px; font-size: 26px; color: #0d6efd;
}
.app-badge img { height: 46px; }
</style>

<!-- ===== ABOUT SECTION ===== -->
<div class="container-fluid py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">About Us</h5>
            <h1 class="mt-3 h2">India's Trusted Online Doctor Booking Platform</h1>
        </div>

        <div class="row gx-5">
            <div class="col-lg-4 mb-4 mb-lg-0" style="min-height:380px;">
                <div class="position-relative h-100" style="min-height:300px;">
                    <img class="position-absolute w-100 h-100 rounded" src="img/about.jpg"
                         style="object-fit:cover;" alt="RogiSewa – Online Doctor Booking India">
                </div>
            </div>

            <div class="col-lg-8">
                <ul class="nav nav-pills custom-tabs justify-content-center mb-4" id="aboutTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#en" type="button">English</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hi" type="button">हिंदी</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ta" type="button">தமிழ்</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#te" type="button">తెలుగు</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mr" type="button">मराठी</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bn" type="button">বাংলা</button>
                    </li>
                </ul>

                <div class="tab-content p-3 border border-top-0 rounded-bottom">

                    <!-- English -->
                    <div class="tab-pane fade show active" id="en">
                        <p>Welcome to <strong>RogiSewa.com</strong> – India's trusted online doctor booking and healthcare discovery platform for patients and families across the country.</p>
                        <p>We make quality healthcare accessible to everyone. Search for the nearest <strong>doctors</strong> and <strong>hospitals</strong>, book appointments online in minutes, and manage your health from anywhere — all in one place.</p>
                        <ul class="mb-3">
                            <li>Search doctors by <strong>location, specialty, or disease</strong></li>
                            <li><strong>Book doctor appointments online</strong> — instantly, 24/7</li>
                            <li>Find verified hospitals and clinics near you</li>
                            <li>Download our <strong>free Android app</strong> for on-the-go access</li>
                            <li>Read health tips, disease guides, and wellness articles</li>
                        </ul>
                        <p>Whether you need a routine check-up, specialist consultation, or emergency care — <strong>RogiSewa</strong> helps you find the right doctor at the right time. Our platform is available on web and mobile, making healthcare discovery simple, fast, and reliable.</p>
                        <p><strong>Your health, your choice</strong> — and we are here to make it simpler.</p>
                    </div>

                    <!-- Hindi -->
                    <div class="tab-pane fade" id="hi">
                        <p><strong>RogiSewa.com</strong> पर आपका स्वागत है – भारत का भरोसेमंद ऑनलाइन डॉक्टर बुकिंग और हेल्थकेयर डिस्कवरी प्लेटफ़ॉर्म।</p>
                        <p>हम हर किसी तक गुणवत्तापूर्ण स्वास्थ्य सेवाएँ पहुँचाते हैं। नज़दीकी <strong>डॉक्टर</strong> और <strong>अस्पताल</strong> खोजें, ऑनलाइन अपॉइंटमेंट बुक करें और अपने स्वास्थ्य को कहीं से भी मैनेज करें।</p>
                        <ul class="mb-3">
                            <li><strong>स्थान, विशेषज्ञता या बीमारी</strong> के आधार पर डॉक्टर खोजें</li>
                            <li><strong>ऑनलाइन डॉक्टर अपॉइंटमेंट बुक करें</strong> — 24/7 उपलब्ध</li>
                            <li>नज़दीकी अस्पताल और क्लिनिक खोजें</li>
                            <li>हमारा <strong>मुफ़्त Android ऐप</strong> डाउनलोड करें</li>
                            <li>स्वास्थ्य टिप्स और बीमारी गाइड पढ़ें</li>
                        </ul>
                        <p>चाहे सामान्य जाँच हो, विशेषज्ञ परामर्श हो या आपातकालीन देखभाल — <strong>RogiSewa</strong> सही समय पर सही डॉक्टर खोजने में मदद करता है।</p>
                        <p><strong>आपका स्वास्थ्य, आपका निर्णय</strong> – और हम आपके साथ हैं।</p>
                    </div>

                    <!-- Tamil -->
                    <div class="tab-pane fade" id="ta">
                        <p><strong>RogiSewa.com</strong>க்கு உங்களை வரவேற்கிறோம் — இந்தியாவின் நம்பகமான ஆன்லைன் டாக்டர் புக்கிங் மற்றும் சுகாதார தளம்.</p>
                        <p>அருகிலுள்ள <strong>மருத்துவர்கள்</strong> மற்றும் <strong>மருத்துவமனைகளை</strong> தேடி, நிமிடங்களில் ஆன்லைனில் அப்பாயின்ட்மென்ட் பதிவு செய்யுங்கள்.</p>
                        <ul class="mb-3">
                            <li><strong>இடம், சிறப்பு துறை அல்லது நோய்</strong> அடிப்படையில் தேடலாம்</li>
                            <li><strong>ஆன்லைனில் டாக்டர் அப்பாயின்ட்மென்ட்</strong> பதிவு செய்யலாம் — 24/7</li>
                            <li>இலவச <strong>Android ஆப்</strong> பதிவிறக்கம் செய்யலாம்</li>
                            <li>சுகாதார குறிப்புகள் மற்றும் நோய் வழிகாட்டிகளை படிக்கலாம்</li>
                        </ul>
                        <p><strong>உங்கள் சுகாதாரம், உங்கள் முடிவு</strong> — நாங்கள் உங்கள் பக்கத்தில் இருக்கிறோம்.</p>
                    </div>

                    <!-- Telugu -->
                    <div class="tab-pane fade" id="te">
                        <p><strong>RogiSewa.com</strong> కు స్వాగతం — భారతదేశంలో నమ్మకమైన ఆన్లైన్ డాక్టర్ బుకింగ్ మరియు ఆరోగ్య సేవా వేదిక.</p>
                        <p>సమీపంలోని <strong>డాక్టర్లు</strong> మరియు <strong>ఆసుపత్రులను</strong> వెతికి, నిమిషాల్లో ఆన్లైన్లో అపాయింట్మెంట్ బుక్ చేసుకోండి.</p>
                        <ul class="mb-3">
                            <li><strong>ప్రాంతం, నైపుణ్యం లేదా వ్యాధి</strong> ఆధారంగా వెతకవచ్చు</li>
                            <li><strong>ఆన్లైన్ డాక్టర్ అపాయింట్మెంట్</strong> బుక్ చేయవచ్చు — 24/7</li>
                            <li>ఉచిత <strong>Android యాప్</strong> డౌన్లోడ్ చేయవచ్చు</li>
                            <li>ఆరోగ్య చిట్కాలు మరియు వ్యాధి గైడ్లు చదవండి</li>
                        </ul>
                        <p><strong>మీ ఆరోగ్యం, మీ నిర్ణయం</strong> — మేము మీ వెంట ఉన్నాము.</p>
                    </div>

                    <!-- Marathi -->
                    <div class="tab-pane fade" id="mr">
                        <p><strong>RogiSewa.com</strong> वर आपले स्वागत आहे — भारतातील विश्वासार्ह ऑनलाइन डॉक्टर बुकिंग आणि हेल्थकेअर प्लॅटफॉर्म.</p>
                        <p>जवळचे <strong>डॉक्टर</strong> आणि <strong>रुग्णालय</strong> शोधा, काही मिनिटांत ऑनलाइन अपॉइंटमेंट बुक करा.</p>
                        <ul class="mb-3">
                            <li><strong>स्थान, तज्ञता किंवा आजार</strong> यांच्या आधारे शोधा</li>
                            <li><strong>ऑनलाइन डॉक्टर अपॉइंटमेंट बुक करा</strong> — 24/7</li>
                            <li>मोफत <strong>Android अॅप</strong> डाउनलोड करा</li>
                            <li>आरोग्य टिप्स आणि रोग मार्गदर्शिका वाचा</li>
                        </ul>
                        <p><strong>आपले आरोग्य, आपला निर्णय</strong> — आम्ही तुमच्यासोबत आहोत.</p>
                    </div>

                    <!-- Bengali -->
                    <div class="tab-pane fade" id="bn">
                        <p><strong>RogiSewa.com</strong> এ আপনাকে স্বাগতম — ভারতের বিশ্বস্ত অনলাইন ডাক্তার বুকিং ও স্বাস্থ্যসেবা প্ল্যাটফর্ম।</p>
                        <p>কাছের <strong>ডাক্তার</strong> ও <strong>হাসপাতাল</strong> খুঁজুন, মিনিটের মধ্যে অনলাইনে অ্যাপয়েন্টমেন্ট বুক করুন।</p>
                        <ul class="mb-3">
                            <li><strong>অবস্থান, বিশেষত্ব বা রোগ</strong> অনুযায়ী খুঁজুন</li>
                            <li><strong>অনলাইনে ডাক্তার অ্যাপয়েন্টমেন্ট বুক করুন</strong> — ২৪/৭</li>
                            <li>বিনামূল্যে <strong>Android অ্যাপ</strong> ডাউনলোড করুন</li>
                            <li>স্বাস্থ্য টিপস ও রোগ গাইড পড়ুন</li>
                        </ul>
                        <p><strong>আপনার স্বাস্থ্য, আপনার সিদ্ধান্ত</strong> — আমরা আপনার পাশে আছি।</p>
                    </div>

                </div>

                <!-- Feature Icons -->
                <div class="row g-3 pt-4">
                    <div class="col-6 col-sm-3">
                        <div class="bg-light text-center rounded-circle py-4">
                            <i class="fa fa-3x fa-user-md text-primary mb-3"></i>
                            <h6 class="mb-0">Qualified<small class="d-block text-primary">Doctors</small></h6>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="bg-light text-center rounded-circle py-4">
                            <i class="fa fa-3x fa-calendar-check text-primary mb-3"></i>
                            <h6 class="mb-0">Online<small class="d-block text-primary">Booking</small></h6>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="bg-light text-center rounded-circle py-4">
                            <i class="fa fa-3x fa-mobile-alt text-primary mb-3"></i>
                            <h6 class="mb-0">Mobile<small class="d-block text-primary">App</small></h6>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="bg-light text-center rounded-circle py-4">
                            <i class="fa fa-3x fa-procedures text-primary mb-3"></i>
                            <h6 class="mb-0">Emergency<small class="d-block text-primary">Services</small></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===== ABOUT SECTION END ===== -->


<!-- ===== HOW IT WORKS ===== -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">How It Works</h5>
            <h2 class="mt-3">Book a Doctor Appointment Online in 3 Easy Steps</h2>
        </div>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="step-icon"><i class="fa fa-search"></i></div>
                <h5>1. Search</h5>
                <p>Search for doctors or hospitals by name, specialty, disease, or location across India. Filter by city, PIN code, or gender.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-icon"><i class="fa fa-calendar-check"></i></div>
                <h5>2. Book</h5>
                <p>Select your preferred doctor, choose an available time slot, and confirm your appointment online — instantly, 24/7, no phone calls needed.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-icon"><i class="fa fa-stethoscope"></i></div>
                <h5>3. Consult</h5>
                <p>Visit the doctor at the scheduled time. Get appointment reminders and manage your bookings from the RogiSewa app.</p>
            </div>
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('doctors') }}" class="btn btn-primary px-4 py-2">
                <i class="fa fa-search me-2"></i> Find a Doctor Now
            </a>
        </div>
    </div>
</div>
<!-- ===== HOW IT WORKS END ===== -->


<!-- ===== APP DOWNLOAD ===== -->
<div class="container-fluid py-5" style="background: linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Mobile App</h5>
                <h2 class="mt-3">RogiSewa App – Book Doctors Anytime, Anywhere</h2>
                <p class="mt-3">Download the <strong>RogiSewa Android app</strong> and get instant access to thousands of doctors and hospitals across India. Book appointments, get reminders, and manage your health — all from your smartphone.</p>
                <ul class="mb-4">
                    <li>Free to download and use</li>
                    <li>Search doctors by location, specialty, or disease</li>
                    <li>Instant online appointment booking</li>
                    <li>Appointment reminders &amp; notifications</li>
                    <li>Works on all Android devices</li>
                </ul>
                <div class="app-badge">
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener noreferrer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                             alt="Download RogiSewa on Google Play Store">
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-white rounded shadow-sm">
                    <i class="fa fa-4x fa-mobile-alt text-primary mb-3 d-block"></i>
                    <h5>Available on Android</h5>
                    <p class="text-muted mb-3">Search, book, and manage doctor appointments from your phone.</p>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm">
                        <i class="fab fa-google-play me-1"></i> Download Free App
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===== APP DOWNLOAD END ===== -->


<!-- ===== OUR DOCTORS ===== -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:500px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Doctors</h5>
            <h2 class="display-7">Qualified Healthcare Professionals</h2>
        </div>

        <div class="owl-carousel team-carousel position-relative">
            @foreach($doctors as $doctor)
                @php $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name; @endphp
                <div class="team-item">
                    <div class="row g-0 bg-light rounded overflow-hidden">
                        <div class="col-12 col-sm-5 h-100">
                            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">
                                <img class="img-fluid h-100"
                                     src="{{ $doctor->profile_pic ? asset('storage/upload/doctor/'.$doctor->profile_pic) : asset('storage/upload/doctor/user.jpg') }}"
                                     style="object-fit:cover;" alt="{{ $practiceName }}">
                            </a>
                        </div>
                        <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                            <div class="mt-auto p-4">
                                <h3><a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">{{ $practiceName }}</a></h3>
                                <h6 class="fw-normal fst-italic text-primary mb-2">
                                    {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                                </h6>
                                <p class="mb-2">{{ $doctor->educations->first()->degree ?? 'Experienced Healthcare Professional' }}</p>
                                @php $location = $doctor->locations->first(); @endphp
                                @if($location)
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        {{ $location->address }}, {{ $location->city }}, {{ $location->state }} - {{ $location->zip_code }}
                                    </p>
                                @else
                                    <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt text-primary me-2"></i> Address not available</p>
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
<!-- ===== OUR DOCTORS END ===== -->


<!-- ===== ADSENSE-FRIENDLY CONTENT ===== -->
<div class="container-fluid py-5">
    <div class="container">

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-3">How to Book a Doctor Appointment Online with RogiSewa</h2>
                <p>Booking a doctor appointment online has never been easier. With <strong>RogiSewa</strong>, patients across India can search for qualified doctors, view their availability, and confirm appointments in just a few clicks — without waiting in long queues or making phone calls.</p>
                <p>Our platform supports appointment booking for general physicians, specialists, dentists, dermatologists, cardiologists, orthopedic surgeons, and many more. Whether you are in a metro city or a smaller town, RogiSewa connects you with verified healthcare professionals near you.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-3">How to Choose the Right Doctor</h2>
                <p>Choosing the right doctor is an important step toward effective medical treatment. Patients should consider factors such as the doctor's specialization, experience, location, and availability before making a decision.</p>
                <p>RogiSewa helps simplify this process by allowing users to compare doctors based on specialization, city, and healthcare needs. By accessing structured and verified information, patients can confidently choose the most suitable healthcare professional for themselves or their family members.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-3">Benefits of Online Doctor Search and Appointment Booking</h2>
                <p>Online doctor search and booking platforms have transformed the way patients access healthcare. Instead of relying on word-of-mouth or outdated sources, patients can now explore multiple healthcare options from a single platform.</p>
                <p>With RogiSewa, users can save time, compare doctors and hospitals, book appointments 24/7, and make informed healthcare decisions. Online healthcare discovery also improves accessibility for patients living in remote or unfamiliar locations across India.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-3">Healthcare in India – Challenges &amp; Digital Solutions</h2>
                <p>India's healthcare system faces challenges such as limited access to verified information, uneven distribution of medical facilities, and lack of healthcare awareness in many regions.</p>
                <p>Digital healthcare platforms like RogiSewa aim to address these challenges by organizing healthcare data and improving transparency. By making doctor and hospital information easily accessible — along with online appointment booking and a mobile app — RogiSewa supports better healthcare awareness and timely medical decisions for millions of Indians.</p>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12">
                <h2 class="mb-3">Why Download the RogiSewa App?</h2>
                <p>The <strong>RogiSewa mobile app</strong> brings the full power of our healthcare platform to your smartphone. With the app, you can search for doctors, book appointments, receive reminders, and access your booking history — all from one place, anytime and anywhere.</p>
                <p>The app is completely free to download and is available for Android devices on the Google Play Store. Whether you are at home, at work, or travelling, the RogiSewa app ensures you always have access to quality healthcare at your fingertips.</p>
                <div class="mt-3">
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <i class="fab fa-google-play me-2"></i> Download RogiSewa App – Free
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- ===== ADSENSE-FRIENDLY CONTENT END ===== -->

@endsection
