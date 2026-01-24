@extends('page.layouts.app')

@section('title', 'Health Blogs & Medical Guides | RogiSewa')

@section('content')

<!-- Page Header -->
<div class="container-fluid bg-primary py-5 mb-5">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white">Health Blogs & Medical Guides</h1>
        <p class="text-white mt-3">
            Trusted healthcare information, patient awareness articles, and medical guides
            to help you make informed health decisions.
        </p>
    </div>
</div>

<!-- Blog List -->
<div class="container py-5">
    <div class="row g-4">

        <!-- Blog Card 1 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        How to Choose the Right Doctor for Your Health Problem
                    </h5>
                    <p class="card-text">
                        Learn how to select the right doctor based on symptoms,
                        specialization, experience, and location.
                    </p>
                    <a href="{{ route('blog-detail') }}?post=choose-right-doctor"
                       class="btn btn-primary btn-sm">
                        Read More
                    </a>
                </div>
            </div>
        </div>

        <!-- Blog Card 2 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        When Should You Visit a Specialist Instead of a General Physician
                    </h5>
                    <p class="card-text">
                        Understand when specialist consultation is required
                        for better diagnosis and treatment.
                    </p>
                    <a href="{{ route('blog-detail') }}?post=visit-specialist"
                       class="btn btn-primary btn-sm">
                        Read More
                    </a>
                </div>
            </div>
        </div>

        <!-- Blog Card 3 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        Common Health Mistakes Patients Make in India
                    </h5>
                    <p class="card-text">
                        Avoid common healthcare mistakes that can delay
                        recovery and worsen health conditions.
                    </p>
                    <a href="{{ route('blog-detail') }}?post=health-mistakes-india"
                       class="btn btn-primary btn-sm">
                        Read More
                    </a>
                </div>
            </div>
        </div>

        <!-- Blog Card 4 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        Importance of Early Medical Consultation
                    </h5>
                    <p class="card-text">
                        Learn why early diagnosis and timely medical advice
                        can prevent serious health complications.
                    </p>
                    <a href="{{ route('blog-detail') }}?post=early-medical-consultation"
                       class="btn btn-primary btn-sm">
                        Read More
                    </a>
                </div>
            </div>
        </div>

        <!-- Blog Card 5 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        Online Doctor Search: Benefits, Safety & Trust Factors
                    </h5>
                    <p class="card-text">
                        Discover how to safely find doctors online and
                        choose verified healthcare professionals.
                    </p>
                    <a href="{{ route('blog-detail') }}?post=online-doctor-search"
                       class="btn btn-primary btn-sm">
                        Read More
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
