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

            @forelse($blogs as $blog)
            <div class="mb-5 pb-4 border-bottom">
                @if($blog->image)
                <img src="{{ asset('uploads/blog/'.$blog->image) }}" class="img-fluid rounded mb-3" alt="{{ $blog->title }}">
                @endif
                <h3>
                    <a href="{{ route('blog-detail', $blog->slug) }}" class="text-dark">
                        {{ $blog->title }}
                    </a>
                </h3>
                <p class="text-muted small mb-2">
                    Category: {{ $blog->category }} | {{ $blog->created_at->format('M d, Y') }}
                </p>
                <p>{{ $blog->excerpt }}</p>
                <a href="{{ route('blog-detail', $blog->slug) }}" class="btn btn-outline-primary btn-sm">
                    Continue Reading →
                </a>
            </div>
            @empty
            <p class="text-center">No blogs available yet.</p>
            @endforelse

            <div class="mt-4">
                {{ $blogs->links() }}
            </div>

        </div>
    </div>
</div>

@endsection