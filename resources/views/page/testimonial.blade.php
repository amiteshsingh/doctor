@extends('page.layouts.app')

@section('title', 'Testimonials - RogiSewa')
@section('content')

<!-- Page Header -->
<div class="container-fluid bg-primary py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-3 text-white animated slideInDown">Testimonials</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white" href="{{ route('/') }}">Home</a></li>
                        <li class="breadcrumb-item text-white active">Testimonials</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="container-fluid py-5">
    <div class="container">

        <div class="text-center mx-auto mb-5" style="max-width:550px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Testimonials</h5>
            <h1 class="display-4">What Our Doctors Say</h1>
        </div>

        @if($testimonials->count())

        {{-- Carousel for top 5 --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="owl-carousel testimonial-carousel">
                    @foreach($testimonials->take(6) as $t)
                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-5">
                            @if($t->photo)
                                <img class="img-fluid rounded-circle mx-auto"
                                     src="{{ asset('storage/upload/testimonials/'.$t->photo) }}"
                                     style="width:100px;height:100px;object-fit:cover;" alt="{{ $t->name }}">
                            @else
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                     style="width:100px;height:100px;background:linear-gradient(135deg,#0d6efd,#13C5DD);">
                                    <i class="fa fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle shadow"
                                 style="width:60px;height:60px;">
                                <i class="fa fa-quote-left fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star" style="color:{{ $i <= $t->rating ? '#f59e0b' : '#ddd' }};font-size:16px;"></i>
                            @endfor
                        </div>
                        <p class="fs-5 fw-normal fst-italic px-3">{{ $t->message }}</p>
                        <hr class="w-25 mx-auto">
                        <h4 class="mb-0">{{ $t->name }}</h4>
                        @if($t->designation)
                            <h6 class="fw-normal text-primary mb-3">{{ $t->designation }}</h6>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- All testimonials grid --}}
        @if($testimonials->count() > 6)
        <div class="text-center mb-4">
            <h4 class="text-muted">More Reviews</h4>
        </div>
        @endif

        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            @if($t->photo)
                                <img src="{{ asset('storage/upload/testimonials/'.$t->photo) }}"
                                     style="width:55px;height:55px;border-radius:50%;object-fit:cover;border:3px solid #0d6efd;margin-right:14px;"
                                     alt="{{ $t->name }}">
                            @else
                                <div style="width:55px;height:55px;border-radius:50%;background:linear-gradient(135deg,#0d6efd,#13C5DD);display:flex;align-items:center;justify-content:center;margin-right:14px;flex-shrink:0;">
                                    <i class="fa fa-user fa-lg text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $t->name }}</h6>
                                @if($t->designation)
                                    <small class="text-primary">{{ $t->designation }}</small>
                                @endif
                                <div class="mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star" style="color:{{ $i <= $t->rating ? '#f59e0b' : '#ddd' }};font-size:11px;"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:14px;line-height:1.7;">
                            <i class="fa fa-quote-left text-primary me-1" style="font-size:12px;"></i>
                            {{ $t->message }}
                            <i class="fa fa-quote-right text-primary ms-1" style="font-size:12px;"></i>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        <div class="text-center py-5">
            <i class="fa fa-comments fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No testimonials yet.</h5>
        </div>
        @endif

    </div>
</div>

@endsection
