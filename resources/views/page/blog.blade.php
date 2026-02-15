@extends('page.layouts.app')
@section('title', 'Health Blogs & Medical Guides | RogiSewa')

@section('content')

<!-- Page Header -->
<div class="container-fluid bg-primary py-5 mb-5">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white">Health Blogs & Medical Guides</h1>
        <p class="text-white mt-3">
            Trusted healthcare information, patient awareness resources,
            and expert medical guides to help you make informed health decisions.
        </p>
    </div>
</div>

<!-- Blog List Section -->
<div class="container py-5">
    <div class="row">

        <div class="col-lg-10 mx-auto">

            <!-- Blog Item 1 -->
            <div class="mb-5 pb-4 border-bottom">
                <h3>
                    <a href="{{ route('blog-detail') }}?post=choose-right-doctor" class="text-dark">
                        How to Choose the Right Doctor for Your Health Problem
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: Patient Guidance
                </p>
                <p>
                    Choosing the right doctor is one of the most important decisions for your health.
                    Understanding symptoms, checking specialization, verifying experience, and
                    considering clinic location can significantly impact treatment outcomes.
                    This guide explains how to identify qualified medical professionals and
                    make confident healthcare decisions.
                </p>
                <a href="{{ route('blog-detail') }}?post=choose-right-doctor"
                   class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>

            <!-- Blog Item 2 -->
            <div class="mb-5 pb-4 border-bottom">
                <h3>
                    <a href="{{ route('blog-detail') }}?post=visit-specialist" class="text-dark">
                        When Should You Visit a Specialist Instead of a General Physician
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: Medical Advice
                </p>
                <p>
                    While general physicians handle common health concerns, certain symptoms
                    require expert evaluation by specialists. Learn the signs that indicate
                    you should consult a cardiologist, neurologist, dermatologist, or other
                    medical specialist for accurate diagnosis and targeted treatment.
                </p>
                <a href="{{ route('blog-detail') }}?post=visit-specialist"
                   class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>

            <!-- Blog Item 3 -->
            <div class="mb-5 pb-4 border-bottom">
                <h3>
                    <a href="{{ route('blog-detail') }}?post=health-mistakes-india" class="text-dark">
                        Common Health Mistakes Patients Make in India
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: Health Awareness
                </p>
                <p>
                    Many health complications arise due to delayed consultation,
                    self-medication, and ignoring early symptoms. This article highlights
                    common healthcare mistakes and explains how informed decisions
                    can improve recovery, reduce medical expenses, and prevent
                    long-term complications.
                </p>
                <a href="{{ route('blog-detail') }}?post=health-mistakes-india"
                   class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>

            <!-- Blog Item 4 -->
            <div class="mb-5 pb-4 border-bottom">
                <h3>
                    <a href="{{ route('blog-detail') }}?post=early-medical-consultation" class="text-dark">
                        Importance of Early Medical Consultation
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: Preventive Healthcare
                </p>
                <p>
                    Early medical consultation plays a critical role in detecting diseases
                    before they become serious. Timely diagnosis not only improves
                    treatment outcomes but also reduces healthcare costs and
                    prevents emergency situations.
                </p>
                <a href="{{ route('blog-detail') }}?post=early-medical-consultation"
                   class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>

            <!-- Blog Item 5 -->
            <div class="mb-5">
                <h3>
                    <a href="{{ route('blog-detail') }}?post=online-doctor-search" class="text-dark">
                        Online Doctor Search: Benefits, Safety & Trust Factors
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: Digital Healthcare
                </p>
                <p>
                    Online doctor search platforms make healthcare access easier
                    and faster. However, patients must verify credentials, clinic
                    details, and platform reliability before booking appointments.
                    This guide explains how to safely use digital healthcare services.
                </p>
                <a href="{{ route('blog-detail') }}?post=online-doctor-search"
                   class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>

        </div>

    </div>
</div>

@endsection