@extends('page.layouts.app')

@section('title', 'Health Blog | RogiSewa')

@section('content')

<div class="container py-5">
@php
$post = request('post');
@endphp

{{-- ================= BLOG 1 ================= --}}
@if($post == 'choose-right-doctor')

<h1 class="mb-3">How to Choose the Right Doctor for Your Health Problem</h1>
<p class="text-muted mb-4">
    Published by RogiSewa | Healthcare Awareness
</p>

<p>
Choosing the right doctor is one of the most important decisions for your
overall health and well-being. Many patients delay treatment or consult
the wrong specialist due to lack of information. This can lead to
misdiagnosis, unnecessary expenses, and delayed recovery.
</p>

<h3>1. Understand Your Health Symptoms</h3>
<p>
The first step is to clearly understand your symptoms. Common problems like
fever, cough, headache, acidity, or mild infections can usually be treated
by a general physician. However, symptoms related to heart, skin, bones,
nerves, or digestion may require a specialist.
</p>

<h3>2. Choose the Right Specialization</h3>
<p>
Doctors are trained in specific medical fields. Consulting a specialist
ensures accurate diagnosis and targeted treatment. For example:
</p>
<ul>
    <li>Heart-related problems → Cardiologist</li>
    <li>Skin issues → Dermatologist</li>
    <li>Joint or bone pain → Orthopedist</li>
    <li>Nerve-related problems → Neurologist</li>
</ul>

<h3>3. Check Experience and Qualifications</h3>
<p>
A doctor’s experience plays a crucial role in treatment quality. Doctors
with several years of practice have handled diverse medical cases and are
better prepared to manage complications. Always check qualifications,
certifications, and medical registration details.
</p>

<h3>4. Location and Accessibility</h3>
<p>
Choosing a doctor near your home or workplace is beneficial, especially
for follow-up visits. Easy accessibility saves time, travel costs, and
stress during emergencies.
</p>

<h3>5. Patient Reviews and Reputation</h3>
<p>
Patient reviews provide insights into a doctor’s communication skills,
treatment approach, and clinic environment. While reviews should not be
the only deciding factor, they help patients make informed choices.
</p>

<h3>Conclusion</h3>
<p>
Selecting the right doctor requires careful evaluation of specialization,
experience, location, and patient feedback. Platforms like
<b>RogiSewa</b> simplify this process by helping patients discover
trusted doctors and healthcare professionals near them.
</p>

{{-- ================= BLOG 2 ================= --}}
@elseif($post == 'visit-specialist')

<h1 class="mb-3">
When Should You Visit a Specialist Instead of a General Physician
</h1>

<p class="text-muted mb-4">
    Published by RogiSewa | Medical Guidance
</p>

<p>
A general physician is often the first doctor patients consult for common
health problems. However, some conditions require specialized care.
Knowing when to consult a specialist can prevent complications and ensure
better treatment outcomes.
</p>

<h3>Role of a General Physician</h3>
<p>
General physicians diagnose and treat common illnesses such as fever,
infections, diabetes, blood pressure, and seasonal diseases. They also
provide preventive care and lifestyle guidance.
</p>

<h3>When You Need a Specialist</h3>
<p>
You should consult a specialist if:
</p>
<ul>
    <li>Symptoms persist for a long time</li>
    <li>Pain or discomfort keeps recurring</li>
    <li>You have a chronic health condition</li>
    <li>Previous treatments are not effective</li>
    <li>Advanced diagnostic tests are required</li>
</ul>

<h3>Benefits of Specialist Consultation</h3>
<p>
Specialists have in-depth knowledge of specific medical conditions. They
use advanced diagnostic tools and provide targeted treatments, resulting
in faster recovery and better long-term health management.
</p>

<h3>Conclusion</h3>
<p>
Consulting the right specialist at the right time improves treatment
accuracy and patient outcomes. RogiSewa helps patients find experienced
specialists easily and confidently.
</p>

{{-- ================= BLOG 3 ================= --}}
@elseif($post == 'health-mistakes-india')

<h1 class="mb-3">Common Health Mistakes Patients Make in India</h1>

<p class="text-muted mb-4">
    Published by RogiSewa | Patient Awareness
</p>

<p>
Many health problems become serious due to small mistakes made by patients.
Awareness and timely medical consultation can help prevent unnecessary
health complications.
</p>

<h3>Ignoring Early Symptoms</h3>
<p>
People often ignore symptoms like fatigue, pain, or fever, assuming they
will resolve on their own. Delayed consultation may worsen the condition.
</p>

<h3>Self-Medication</h3>
<p>
Using medicines without a doctor’s advice can cause side effects and
incorrect treatment. Antibiotic misuse is a major concern in India.
</p>

<h3>Skipping Follow-Up Visits</h3>
<p>
Stopping treatment once symptoms improve can lead to relapse. Follow-up
visits help ensure complete recovery.
</p>

<h3>Relying on Unverified Information</h3>
<p>
Online health information should not replace professional medical advice.
Always consult a qualified healthcare professional.
</p>

<h3>Conclusion</h3>
<p>
Avoiding these common mistakes can significantly improve health outcomes.
RogiSewa encourages informed healthcare decisions by connecting patients
with verified doctors.
</p>

{{-- ================= BLOG 4 ================= --}}
@elseif($post == 'early-medical-consultation')

<h1 class="mb-3">Importance of Early Medical Consultation</h1>

<p class="text-muted mb-4">
    Published by RogiSewa | Health Education
</p>

<p>
Early medical consultation is essential for preventing serious health
issues. Many diseases can be managed effectively if detected at an early
stage.
</p>

<h3>Early Diagnosis Saves Lives</h3>
<p>
Conditions such as diabetes, heart disease, and hypertension show early
warning signs. Consulting a doctor early allows timely diagnosis.
</p>

<h3>Lower Treatment Costs</h3>
<p>
Early-stage treatment is usually simpler and more affordable compared to
advanced-stage medical care.
</p>

<h3>Improved Recovery</h3>
<p>
Prompt medical attention prevents disease progression and improves overall
recovery chances.
</p>

<h3>Conclusion</h3>
<p>
Never delay consulting a doctor when symptoms appear. Early action ensures
better health and peace of mind.
</p>

{{-- ================= BLOG 5 ================= --}}
@elseif($post == 'online-doctor-search')

<h1 class="mb-3">
Online Doctor Search: Benefits, Safety & Trust Factors
</h1>

<p class="text-muted mb-4">
    Published by RogiSewa | Digital Healthcare
</p>

<p>
With the rise of digital healthcare, online doctor search has become a
convenient way to find medical professionals. However, patients must be
aware of safety and trust factors.
</p>

<h3>Benefits of Online Doctor Search</h3>
<ul>
    <li>Easy access to multiple doctors</li>
    <li>Compare specialization and experience</li>
    <li>Saves time and travel effort</li>
    <li>Helps in informed decision-making</li>
</ul>

<h3>Safety Tips for Patients</h3>
<p>
Always verify doctor credentials, clinic details, and medical registration.
Avoid sharing sensitive personal information on untrusted platforms.
</p>

<h3>Importance of Verified Platforms</h3>
<p>
Verified platforms like <b>RogiSewa</b> ensure transparency, reliability,
and patient safety.
</p>

<h3>Conclusion</h3>
<p>
Online doctor search is effective when done carefully. Choose trusted
platforms to ensure quality healthcare access.
</p>

@endif

<a href="{{ route('blog') }}" class="btn btn-secondary mt-4">
    ← Back to Blogs
</a>

</div>
@endsection
