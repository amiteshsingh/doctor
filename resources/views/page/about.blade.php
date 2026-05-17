@extends('page.layouts.app')

@section('title', 'About RogiSewa - Online Doctor Booking App | Find Doctors & Hospitals in India')
@section('meta_description', 'RogiSewa is India trusted healthcare discovery platform. Book doctor appointments online, find hospitals near you, and download our free Android app.')

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

.custom-tabs .nav-link:hover {
    background: #13C5DD;
    transform: translateY(-2px);
}

/* .custom-tabs .nav-link.active {
    background: linear-gradient(45deg, #0d6efd, #0a58ca);
    color: #fff;
    box-shadow: 0 4px 10px rgba(13,110,253,0.3);
} */
</style>
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
                   
                   <ul class="nav nav-pills custom-tabs justify-content-center mb-4" id="aboutTabs" role="tablist">

    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button">
            English
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="hi-tab" data-bs-toggle="tab" data-bs-target="#hi" type="button">
            हिंदी
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="ta-tab" data-bs-toggle="tab" data-bs-target="#ta" type="button">
            தமிழ்
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="te-tab" data-bs-toggle="tab" data-bs-target="#te" type="button">
            తెలుగు
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="mr-tab" data-bs-toggle="tab" data-bs-target="#mr" type="button">
            मराठी
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="bn-tab" data-bs-toggle="tab" data-bs-target="#bn" type="button">
            বাংলা
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

                        <div class="tab-pane fade" id="ta">
                            <p><strong>RogiSewa.com</strong>க்கு உங்களை வரவேற்கிறோம் — உங்கள் மற்றும் உங்கள் குடும்பத்தின் சிறந்த சுகாதார சேவைகளை கண்டுபிடிக்க நம்பகமான துணை.</p>

                            <p>எங்களின் நோக்கம் எளிதானது — தரமான சுகாதார சேவைகளை அனைவருக்கும் எளிதாகக் கிடைக்கச் செய்வது. இந்த தளத்தின் மூலம் பயனர்கள் அருகிலுள்ள <strong>மருத்துவர்கள்</strong> மற்றும் <strong>மருத்துவமனைகள்</strong> தேடி, அவர்களின் சிறப்பு துறைகளை அறிந்து, தங்களின் தேவைக்கு ஏற்ற மருத்துவரைத் தேர்ந்தெடுக்கலாம்.</p>

                            <p>சுகாதார விஷயங்களில் ஒவ்வொரு நொடியும் முக்கியம் என்பதை நாங்கள் அறிவோம். அதனால் நீங்கள்:</p>
                            <ul class="mb-3">
                            <li><strong>இடம், சிறப்பு துறை அல்லது நோய்</strong> அடிப்படையில் மருத்துவர்கள் மற்றும் மருத்துவமனைகளைத் தேடலாம்</li>
                            <li>நம்பகமான மற்றும் சான்றளிக்கப்பட்ட மருத்துவ நிபுணர் தகவல்களைப் பெறலாம்</li>
                            <li>உங்கள் அல்லது உங்கள் அன்பினர்களுக்கான சரியான சிகிச்சையைத் தேர்வு செய்யலாம்</li>
                            </ul>

                            <p><strong>RogiSewa.com</strong> இன் இலக்கு — நோயாளிகளுக்கும் சுகாதார சேவை வழங்குநர்களுக்கும் இடையிலான தூரத்தை குறைப்பது. சாதாரண பரிசோதனை, சிறப்பு சிகிச்சை அல்லது அவசர சேவைகள் எதுவாக இருந்தாலும் — சரியான முடிவெடுக்க நாங்கள் உங்களுடன் இருக்கிறோம்.</p>

                            <p>உங்கள் சுகாதாரம், உங்கள் முடிவு — நாங்கள் உங்கள் பக்கத்தில் இருக்கிறோம்.</p>
                        </div>

                        <div class="tab-pane fade" id="te">
                            <p><strong>RogiSewa.com</strong> కు స్వాగతం — మీకు మరియు మీ కుటుంబానికి ఉత్తమ ఆరోగ్య సేవలను కనుగొనే నమ్మకమైన భాగస్వామి.</p>

                            <p>మా లక్ష్యం సులభం — నాణ్యమైన ఆరోగ్య సేవలను ప్రతి ఒక్కరికీ సులభంగా అందించడం. ఈ వేదిక ద్వారా వినియోగదారులు తమకు సమీపంలోని <strong>డాక్టర్లు</strong> మరియు <strong>ఆసుపత్రులను</strong> వెతికి, వారి నైపుణ్యాలను తెలుసుకుని, అవసరానికి తగిన డాక్టర్‌ను ఎంచుకోవచ్చు.</p>

                            <p>ఆరోగ్య విషయాల్లో ప్రతి క్షణం విలువైనదని మాకు తెలుసు. అందుకే మీరు:</p>
                            <ul class="mb-3">
                            <li><strong>ప్రాంతం, నైపుణ్యం లేదా వ్యాధి</strong> ఆధారంగా డాక్టర్లు మరియు ఆసుపత్రులను వెతకవచ్చు</li>
                            <li>నమ్మదగిన మరియు ధృవీకరించబడిన వైద్య నిపుణుల సమాచారం పొందవచ్చు</li>
                            <li>మీరు లేదా మీ ప్రియమైనవారికి సరైన చికిత్సను ఎంచుకోవచ్చు</li>
                            </ul>

                            <p><strong>RogiSewa.com</strong> యొక్క లక్ష్యం — రోగులు మరియు ఆరోగ్య సేవాప్రదాతల మధ్య దూరాన్ని తగ్గించడం. సాధారణ పరీక్షలైనా, ప్రత్యేక చికిత్సలైనా లేదా అత్యవసర సేవలైనా — సరైన నిర్ణయం తీసుకునేందుకు మేము మీతో ఉన్నాము.</p>

                            <p>మీ ఆరోగ్యం, మీ నిర్ణయం — మేము మీ వెంట ఉన్నాము.</p>
                        </div>

                        <div class="tab-pane fade" id="mr">
                            <p><strong>RogiSewa.com</strong> वर आपले स्वागत आहे — आपल्या आणि आपल्या कुटुंबासाठी उत्तम आरोग्य सेवा शोधण्याचा विश्वासार्ह साथीदार.</p>

                            <p>आमचे उद्दिष्ट सोपे आहे — दर्जेदार आरोग्य सेवा प्रत्येकापर्यंत सहज पोहोचवणे. या प्लॅटफॉर्मद्वारे कोणीही आपल्या जवळचे <strong>डॉक्टर</strong> आणि <strong>रुग्णालय</strong> शोधू शकतो, त्यांची तज्ञता पाहू शकतो आणि आपल्या गरजेनुसार योग्य डॉक्टर निवडू शकतो.</p>

                            <p>आरोग्याच्या बाबतीत प्रत्येक क्षण महत्त्वाचा असतो. म्हणून आपण:</p>
                            <ul class="mb-3">
                            <li><strong>स्थान, तज्ञता किंवा आजार</strong> यांच्या आधारे डॉक्टर आणि रुग्णालय शोधू शकता</li>
                            <li>विश्वसनीय आणि प्रमाणित वैद्यकीय तज्ञांची माहिती मिळवू शकता</li>
                            <li>आपल्या किंवा आपल्या प्रियजनांसाठी योग्य उपचार निवडू शकता</li>
                            </ul>

                            <p><strong>RogiSewa.com</strong> चे ध्येय — रुग्ण आणि आरोग्य सेवा पुरवणाऱ्यांमधील अंतर कमी करणे. सामान्य तपासणी असो, विशेष उपचार असो किंवा आपत्कालीन सेवा असो — योग्य निर्णय घेण्यासाठी आम्ही तुमच्यासोबत आहोत.</p>

                            <p>आपले आरोग्य, आपला निर्णय — आणि आम्ही तुमच्या सोबत आहोत.</p>
                        </div>

                        <div class="tab-pane fade" id="bn">
                            <p><strong>RogiSewa.com</strong> এ আপনাকে স্বাগতম — আপনার এবং আপনার পরিবারের জন্য সেরা স্বাস্থ্য পরিষেবা খুঁজে পাওয়ার বিশ্বস্ত সঙ্গী।</p>

                            <p>আমাদের উদ্দেশ্য সহজ — সবার জন্য মানসম্মত স্বাস্থ্য পরিষেবাকে সহজলভ্য করা। এই প্ল্যাটফর্মের মাধ্যমে যে কেউ কাছাকাছি <strong>ডাক্তার</strong> এবং <strong>হাসপাতাল</strong> খুঁজে নিতে পারেন, তাঁদের বিশেষত্ব দেখতে পারেন এবং নিজের প্রয়োজন অনুযায়ী সঠিক চিকিৎসকের সাথে যোগাযোগ করতে পারেন।</p>

                            <p>স্বাস্থ্যের ক্ষেত্রে প্রতিটি মুহূর্ত গুরুত্বপূর্ণ। তাই আপনি:</p>
                            <ul class="mb-3">
                            <li><strong>অবস্থান, বিশেষত্ব বা রোগ</strong> অনুযায়ী ডাক্তার ও হাসপাতাল খুঁজতে পারবেন</li>
                            <li>বিশ্বস্ত ও যাচাইকৃত চিকিৎসা বিশেষজ্ঞদের তথ্য পাবেন</li>
                            <li>নিজের বা প্রিয়জনের জন্য সঠিক চিকিৎসা বেছে নিতে পারবেন</li>
                            </ul>

                            <p><strong>RogiSewa.com</strong> এর লক্ষ্য — রোগী ও স্বাস্থ্য পরিষেবা প্রদানকারীদের মধ্যে দূরত্ব কমানো। সাধারণ চেকআপ, বিশেষ চিকিৎসা বা জরুরি পরিষেবা — যাই হোক না কেন, সঠিক সিদ্ধান্ত নিতে আমরা আপনার পাশে আছি।</p>

                            <p>আপনার স্বাস্থ্য, আপনার সিদ্ধান্ত — এবং আমরা আপনার সঙ্গে আছি।</p>
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

        <!-- ================= EXTRA INFORMATION SECTION ================= -->
    <div class="container-fluid py-5">
        <div class="container">

            <!-- How to Choose Doctor -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="mb-3">How to Choose the Right Doctor</h3>
                    <p>
                        Choosing the right doctor is an important step toward effective medical treatment.
                        Patients should consider factors such as the doctor’s specialization, experience,
                        location, and availability before making a decision.
                    </p>
                    <p>
                        RogiSewa helps simplify this process by allowing users to compare doctors based on
                        specialization, city, and healthcare needs. By accessing structured and verified
                        information, patients can confidently choose the most suitable healthcare professional.
                    </p>
                </div>
            </div>

            <!-- Benefits of Online Doctor Search -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="mb-3">Benefits of Online Doctor Search</h3>
                    <p>
                        Online doctor search platforms have transformed the way patients access healthcare
                        information. Instead of relying on word-of-mouth or outdated sources, patients can
                        now explore multiple healthcare options from a single platform.
                    </p>
                    <p>
                        With RogiSewa, users can save time, compare doctors and hospitals, and make informed
                        healthcare decisions. Online healthcare discovery also improves accessibility for
                        patients living in remote or unfamiliar locations.
                    </p>
                </div>
            </div>

            <!-- Healthcare in India -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="mb-3">Healthcare in India – Challenges & Solutions</h3>
                    <p>
                        India’s healthcare system faces challenges such as limited access to verified
                        information, uneven distribution of medical facilities, and lack of healthcare
                        awareness in many regions.
                    </p>
                    <p>
                        Digital healthcare platforms like RogiSewa aim to address these challenges by
                        organizing healthcare data and improving transparency. By making doctor and hospital
                        information easily accessible, RogiSewa supports better healthcare awareness and
                        timely medical decisions.
                    </p>
                </div>
            </div>

            

        </div>
    </div>
    <!-- ================= EXTRA INFORMATION SECTION END ================= -->


    @endsection