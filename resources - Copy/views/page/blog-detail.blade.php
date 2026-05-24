@extends('page.layouts.app')
@section('title', $blog->title . ' | RogiSewa')
@section('meta_description', $blog->excerpt)

@section('content')

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                    <li class="breadcrumb-item active">{{ $blog->title }}</li>
                </ol>
            </nav>

            <article class="blog-post">
                @if($blog->image)
                <img src="{{ asset('uploads/blog/'.$blog->image) }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}">
                @endif

                <h1 class="mb-3">{{ $blog->title }}</h1>
                <p class="text-muted mb-4">
                    <span class="badge bg-primary">{{ $blog->category }}</span>
                    <span class="ms-2">{{ $blog->created_at->format('M d, Y') }}</span>
                </p>
                
                <div class="blog-content">
                    {!! $blog->content !!}
                </div>
            </article>

            <div class="mt-5 pt-4 border-top">
                <a href="{{ route('blog') }}" class="btn btn-outline-primary">
                    ← Back to All Blogs
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
