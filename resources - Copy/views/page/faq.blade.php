@extends('page.layouts.app')
@section('title', 'RogiSewa - Frequently Asked Questions')
@section('content')

<!-- FAQ Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">
                Help Center
            </h5>
            <h1 class="display-5">Frequently Asked Questions (FAQ)</h1>
            <p class="mt-3 text-muted">
                Find answers to common questions about RogiSewa, how our platform works,
                and how we help patients connect with trusted healthcare professionals.
            </p>
        </div>

        <div class="accordion" id="faqAccordion">

            <!-- FAQ 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        What is RogiSewa?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show"
                    aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        RogiSewa is an online healthcare discovery platform that helps patients
                        find verified doctors, hospitals, and clinics across India based on
                        location, specialization, and medical needs.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Is RogiSewa free to use?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse"
                    aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, RogiSewa is completely free for patients. Users can search for
                        doctors and hospitals without any registration or payment.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Does RogiSewa provide medical advice?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse"
                    aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No. RogiSewa does not provide medical advice, diagnosis, or treatment.
                        All information on this website is for general informational purposes only.
                        Users should consult qualified healthcare professionals for medical concerns.
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        How are doctors listed on RogiSewa?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse"
                    aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Doctors and healthcare facilities listed on RogiSewa are sourced from
                        publicly available data and verified information wherever possible.
                        However, patients are advised to independently verify details before
                        booking any appointment.
                    </div>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        How can I contact RogiSewa support?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse"
                    aria-labelledby="faqFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can reach us through our Contact Us page or by emailing us at
                        <strong>rogisewa25@gmail.com</strong>. Our support team usually responds
                        within 24–48 hours.
                    </div>
                </div>
            </div>

        </div>

        <!-- Extra Trust Section -->
        <div class="mt-5 text-center">
            <p class="text-muted">
                Still have questions? Feel free to visit our
                <a href="{{ url('/contact') }}">Contact Us</a> page.
                RogiSewa is committed to improving healthcare awareness and accessibility in India.
            </p>
        </div>
    </div>
</div>
<!-- FAQ End -->

@endsection
