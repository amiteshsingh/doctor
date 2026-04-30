@extends('user.layouts.app')
@section('title', 'RogiSewa - My Favourites')

@section('user_content')

<style>
.fav-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.fav-head {
    background:linear-gradient(135deg,#f093fb,#f5576c);
    padding:20px 24px; color:#fff;
    display:flex; align-items:center; justify-content:space-between;
}
.fav-head h5 { margin:0; font-size:16px; font-weight:700; display:flex; align-items:center; gap:8px; }
.fav-count { background:rgba(255,255,255,.25); border-radius:20px; padding:3px 12px; font-size:13px; font-weight:700; }
.fav-body { padding:20px; }

/* Doctor card */
.doc-card {
    background:#fff; border:1.5px solid #e2e8f0;
    border-radius:16px; padding:18px 20px; margin-bottom:14px;
    display:flex; align-items:center; gap:16px; flex-wrap:wrap;
    animation: docFade .4s ease both;
    transition:transform .25s, box-shadow .25s, border-color .25s;
}
.doc-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(240,147,251,.15); border-color:#f5576c; }
@keyframes docFade { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

.doc-avatar {
    width:60px; height:60px; border-radius:14px; object-fit:cover;
    border:2px solid #f5576c; flex-shrink:0;
    box-shadow:0 4px 12px rgba(245,87,108,.2);
    transition:transform .3s;
}
.doc-card:hover .doc-avatar { transform:scale(1.08); }

.doc-info { flex:1; min-width:180px; }
.doc-name {
    font-size:15px; font-weight:700; color:#1a1a2e;
    margin-bottom:4px; display:flex; align-items:center; gap:6px;
}
.doc-spec {
    background:#fff0f0; color:#f5576c;
    border-radius:20px; padding:2px 10px;
    font-size:11px; font-weight:700; display:inline-block;
}
.doc-meta { font-size:12px; color:#888; margin-top:6px; }

.doc-actions { display:flex; gap:8px; flex-wrap:wrap; }
.doc-btn {
    padding:8px 16px; border-radius:10px; font-size:12px;
    font-weight:700; text-decoration:none; transition:all .2s;
    display:inline-flex; align-items:center; gap:6px;
}
.doc-btn-view {
    background:linear-gradient(135deg,#f093fb,#f5576c);
    color:#fff; border:none;
}
.doc-btn-view:hover { opacity:.9; transform:scale(1.04); color:#fff; text-decoration:none; }
.doc-btn-unfav {
    background:#fff0f0; color:#ef4444; border:1px solid #fecaca;
}
.doc-btn-unfav:hover { background:#ffe4e4; text-decoration:none; }

/* Empty state */
.fav-empty { text-align:center; padding:50px 20px; color:#aaa; }
.fav-empty i { font-size:48px; color:#ffd0e0; margin-bottom:14px; display:block; }
</style>

<div class="fav-card">
    <div class="fav-head">
        <h5><i class="fa fa-heart"></i> My Favourite Doctors</h5>
        <span class="fav-count">{{ $favourites->count() }} Saved</span>
    </div>
    <div class="fav-body">

        @forelse($favourites as $i => $fav)
            @php $doc = $fav->doctor; @endphp
            <div class="doc-card" style="animation-delay:{{ $i * 0.07 }}s">
                <img src="{{ asset('storage/upload/doctor/' . ($doc->profile_pic ?? 'user.jpg')) }}"
                     class="doc-avatar" alt="{{ $doc->name }}">
                <div class="doc-info">
                    <div class="doc-name">
                        <i class="fa fa-stethoscope" style="color:#f5576c;font-size:13px;"></i>
                        Dr. {{ $doc->name ?? 'N/A' }}
                    </div>
                    <span class="doc-spec">
                        {{ $doc->specializations->first()?->specialization?->name ?? 'General' }}
                    </span>
                    @if($doc->phone_no || $doc->email)
                    <div class="doc-meta">
                        @if($doc->phone_no)
                            <i class="fa fa-phone"></i> {{ $doc->phone_no }}
                        @endif
                        @if($doc->email)
                            &nbsp;|&nbsp; <i class="fa fa-envelope-o"></i> {{ $doc->email }}
                        @endif
                    </div>
                    @endif
                </div>
                <div class="doc-actions">
                    <a href="{{ route('doctors-profile', ['id' => $doc->id, 'name' => Str::slug($doc->name)]) }}"
                       class="doc-btn doc-btn-view">
                        <i class="fa fa-eye"></i> View Profile
                    </a>
                    <form method="POST" action="{{ route('user.favourite.toggle') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doc->id }}">
                        <button type="submit" class="doc-btn doc-btn-unfav">
                            <i class="fa fa-heart-o"></i> Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
        <div class="fav-empty">
            <i class="fa fa-heart-o"></i>
            <div style="font-size:15px;font-weight:600;color:#555;margin-bottom:6px;">No favourites yet</div>
            <div style="font-size:13px;">Save your favourite doctors for quick access.</div>
            <a href="{{ url('doctors') }}" style="display:inline-block;margin-top:14px;background:linear-gradient(135deg,#f093fb,#f5576c);color:#fff;border-radius:10px;padding:9px 22px;font-size:13px;font-weight:700;text-decoration:none;">
                Find Doctors
            </a>
        </div>
        @endforelse

    </div>
</div>

@endsection
