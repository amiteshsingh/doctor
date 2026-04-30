@extends('user.layouts.app')
@section('title', 'RogiSewa - My Bookings')

@section('user_content')

<style>
.bk-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.bk-head {
    background:linear-gradient(135deg,#4facfe,#00f2fe);
    padding:20px 24px; color:#fff;
    display:flex; align-items:center; justify-content:space-between;
}
.bk-head h5 { margin:0; font-size:16px; font-weight:700; display:flex; align-items:center; gap:8px; }
.bk-count { background:rgba(255,255,255,.25); border-radius:20px; padding:3px 12px; font-size:13px; font-weight:700; }
.bk-body { padding:20px; }

/* Booking item */
.bk-item {
    background:#f8fbff; border:1.5px solid #e2e8f0;
    border-radius:14px; padding:16px 18px; margin-bottom:12px;
    display:flex; align-items:center; gap:16px; flex-wrap:wrap;
    animation: bkFade .4s ease both;
    transition:transform .25s, box-shadow .25s;
}
.bk-item:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(10,110,189,.1); border-color:#bfdbfe; }
@keyframes bkFade { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

.bk-num {
    width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#4facfe,#00f2fe);
    color:#fff; font-weight:800; font-size:13px;
    display:flex; align-items:center; justify-content:center;
}
.bk-info { flex:1; min-width:160px; }
.bk-doctor { font-size:14px; font-weight:700; color:#1a1a2e; margin-bottom:2px; }
.bk-patient { font-size:12px; color:#888; }
.bk-meta { display:flex; gap:10px; flex-wrap:wrap; }
.bk-badge {
    display:inline-flex; align-items:center; gap:5px;
    background:#fff; border:1px solid #e2e8f0;
    border-radius:8px; padding:4px 10px; font-size:12px; color:#555;
}
.bk-invoice {
    background:#eef2ff; color:#667eea;
    border-radius:20px; padding:3px 12px;
    font-size:11px; font-weight:700; white-space:nowrap;
}

/* Empty state */
.bk-empty { text-align:center; padding:50px 20px; color:#aaa; }
.bk-empty i { font-size:48px; color:#d0e4ff; margin-bottom:14px; display:block; }
</style>

<div class="bk-card">
    <div class="bk-head">
        <h5><i class="fa fa-calendar-check-o"></i> My Bookings</h5>
        <span class="bk-count">{{ $bookings->total() }} Total</span>
    </div>
    <div class="bk-body">

        @forelse($bookings as $i => $b)
        @php $i = ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->index; @endphp
        <div class="bk-item" style="animation-delay:{{ $i * 0.06 }}s">
            <div class="bk-num">{{ $i + 1 }}</div>
            <div class="bk-info">
                <div class="bk-doctor">
                    <i class="fa fa-user-md" style="color:#4facfe;margin-right:5px;"></i>
                    Dr. {{ $b->invoiceMaster->doctor->name ?? 'N/A' }}
                </div>
                <div class="bk-patient">
                    <i class="fa fa-user" style="margin-right:4px;"></i>{{ $b->patient_name }}
                    &nbsp;|&nbsp;
                    <i class="fa fa-phone" style="margin-right:4px;"></i>{{ $b->patient_phone_no }}
                </div>
            </div>
            <div class="bk-meta">
                @if($b->booking_date)
                <span class="bk-badge">
                    <i class="fa fa-calendar" style="color:#4facfe;"></i>
                    {{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}
                </span>
                @endif
                @if($b->booking_time)
                <span class="bk-badge">
                    <i class="fa fa-clock-o" style="color:#00b074;"></i>
                    {{ $b->booking_time }}
                </span>
                @endif
                <span class="bk-invoice">{{ $b->invoice_number }}</span>
            </div>
        </div>
        @empty
        <div class="bk-empty">
            <i class="fa fa-calendar-times-o"></i>
            <div style="font-size:15px;font-weight:600;color:#555;margin-bottom:6px;">No bookings yet</div>
            <div style="font-size:13px;">Book an appointment with a doctor to see it here.</div>
            <a href="{{ url('doctors') }}" style="display:inline-block;margin-top:14px;background:linear-gradient(135deg,#4facfe,#00f2fe);color:#fff;border-radius:10px;padding:9px 22px;font-size:13px;font-weight:700;text-decoration:none;">
                Find Doctors
            </a>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($bookings->hasPages())
        <div style="margin-top:16px;">
            <style>
            .bk-pagination { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
            .bk-pagination .page-info { font-size:13px; color:#888; }
            .bk-pagination .page-links { display:flex; gap:6px; flex-wrap:wrap; }
            .bk-pagination .page-links a,
            .bk-pagination .page-links span {
                display:inline-flex; align-items:center; justify-content:center;
                width:34px; height:34px; border-radius:8px; font-size:13px; font-weight:600;
                text-decoration:none; transition:all .2s;
                border:1.5px solid #e2e8f0; color:#555; background:#fff;
            }
            .bk-pagination .page-links a:hover { background:#f0f7ff; border-color:#4facfe; color:#4facfe; }
            .bk-pagination .page-links span.active { background:linear-gradient(135deg,#4facfe,#00f2fe); color:#fff; border-color:transparent; }
            .bk-pagination .page-links span.disabled { opacity:.4; cursor:not-allowed; }
            </style>
            <div class="bk-pagination">
                <div class="page-info">
                    Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
                </div>
                <div class="page-links">
                    {{-- Prev --}}
                    @if($bookings->onFirstPage())
                        <span class="disabled"><i class="fa fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}"><i class="fa fa-chevron-left"></i></a>
                    @endif

                    {{-- Pages --}}
                    @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                        @if($page == $bookings->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}"><i class="fa fa-chevron-right"></i></a>
                    @else
                        <span class="disabled"><i class="fa fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
