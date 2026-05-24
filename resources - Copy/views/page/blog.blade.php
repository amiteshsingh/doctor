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
                @if($blogs->hasPages())
                <style>
                .blog-pagi { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding:16px 0; }
                .blog-pagi .pagi-info { font-size:13px; color:#888; }
                .blog-pagi .pagi-links { display:flex; gap:6px; flex-wrap:wrap; }
                .blog-pagi .pagi-links a,
                .blog-pagi .pagi-links span {
                    display:inline-flex; align-items:center; justify-content:center;
                    min-width:36px; height:36px; padding:0 10px;
                    border-radius:9px; font-size:13px; font-weight:600;
                    text-decoration:none; transition:all .2s;
                    border:1.5px solid #e2e8f0; color:#555; background:#fff;
                }
                .blog-pagi .pagi-links a:hover { background:#f0f7ff; border-color:#009efb; color:#009efb; }
                .blog-pagi .pagi-links .pagi-active { background:linear-gradient(135deg,#009efb,#00b074); color:#fff; border-color:transparent; box-shadow:0 4px 12px rgba(0,158,251,.3); }
                .blog-pagi .pagi-links .pagi-disabled { opacity:.4; cursor:not-allowed; pointer-events:none; }
                </style>
                <div class="blog-pagi">
                    <div class="pagi-info">
                        Showing {{ $blogs->firstItem() }}&ndash;{{ $blogs->lastItem() }} of {{ $blogs->total() }} blogs
                    </div>
                    <div class="pagi-links">
                        {{-- Prev --}}
                        @if($blogs->onFirstPage())
                            <span class="pagi-disabled"><i class="fa fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}"><i class="fa fa-chevron-left"></i></a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                            @if($page == $blogs->currentPage())
                                <span class="pagi-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}"><i class="fa fa-chevron-right"></i></a>
                        @else
                            <span class="pagi-disabled"><i class="fa fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection