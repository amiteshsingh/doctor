@extends('user.layouts.app')
@section('title', 'RogiSewa - My Favourites')

@section('user_content')
<div class="card shadow border-0 rounded-4 p-4 bg-white">
    <h5 class="fw-bold border-bottom pb-2 mb-4"><i class="fa fa-heart me-2 text-danger"></i>My Favourite Doctors</h5>

    @forelse($favourites as $fav)
        @php $doc = $fav->doctor; @endphp
        <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded-3 bg-light">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/upload/doctor/' . ($doc->profile_pic ?? 'user.jpg')) }}"
                     class="rounded-circle border border-2 border-primary me-3"
                     style="width:55px;height:55px;object-fit:cover;">
                <div>
                    <h6 class="fw-bold mb-0">{{ $doc->name ?? 'N/A' }}</h6>
                    <small class="text-muted">
                        {{ $doc->specializations->first()?->specialization?->name ?? 'N/A' }}
                    </small>
                </div>
            </div>
            <a href="{{ route('doctors-profile', ['id' => $doc->id, 'name' => Str::slug($doc->name)]) }}"
               class="btn btn-sm btn-outline-primary">View Profile</a>
        </div>
    @empty
        <p class="text-muted text-center py-3">No favourite doctors added yet.</p>
    @endforelse
</div>
@endsection
