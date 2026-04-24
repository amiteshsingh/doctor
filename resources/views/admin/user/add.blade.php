@extends('admin.layout.app')

@section('content')

@php $isEdit = isset($user->id) && $user->id; @endphp

<style>
.uform-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

/* ── TOP BAR ── */
.uform-topbar {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:22px;
}
.uform-topbar h4 { margin:0; font-size:20px; font-weight:700; color:#1a1a2e; }
.btn-back-u {
    background:#f0f4ff; color:#0a6ebd; border:1px solid #d0e4ff;
    border-radius:8px; padding:7px 16px; font-size:13px;
    text-decoration:none; transition:background .2s;
}
.btn-back-u:hover { background:#dbeafe; color:#0a6ebd; text-decoration:none; }

/* ── TAB WRAPPER ── */
.uform-wrap {
    background:#fff; border-radius:16px;
    box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden;
}

/* ── TAB NAV ── */
.uform-wrap .nav-tabs {
    background:linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border:none; padding:0 20px; display:flex;
}
.uform-wrap .nav-tabs .nav-link {
    color:rgba(255,255,255,.7); border:none; border-radius:0;
    padding:15px 22px; font-size:13px; font-weight:600;
    position:relative; transition:color .25s; background:transparent;
    white-space:nowrap;
}
.uform-wrap .nav-tabs .nav-link i { margin-right:7px; }
.uform-wrap .nav-tabs .nav-link::after {
    content:''; position:absolute; bottom:0; left:0; right:0;
    height:3px; background:#fff; border-radius:3px 3px 0 0;
    transform:scaleX(0); transition:transform .3s ease;
}
.uform-wrap .nav-tabs .nav-link.active { color:#fff; }
.uform-wrap .nav-tabs .nav-link.active::after { transform:scaleX(1); }
.uform-wrap .tab-content { padding:30px; }

/* ── SECTION HEAD ── */
.sec-hd {
    display:flex; align-items:center; gap:10px;
    font-size:14px; font-weight:700; color:#1a1a2e;
    border-bottom:2px solid #f0f4ff;
    padding-bottom:10px; margin-bottom:22px;
}
.sec-ic {
    width:34px; height:34px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; color:#fff;
}
.ic-blue   { background:linear-gradient(135deg,#0a6ebd,#4da6ff); }
.ic-gold   { background:linear-gradient(135deg,#f59e0b,#fcd34d); }

/* ── FORM FIELDS ── */
.uf-label {
    font-size:12.5px; font-weight:600; color:#555;
    margin-bottom:5px; display:block;
}
.uf-input {
    border:1.5px solid #e2e8f0; border-radius:9px;
    padding:10px 14px; font-size:13.5px; width:100%;
    transition:border-color .25s, box-shadow .25s;
    background:#fafbff; color:#222;
}
.uf-input:focus {
    border-color:#0a6ebd; box-shadow:0 0 0 3px rgba(10,110,189,.1);
    outline:none; background:#fff;
}
select.uf-input { appearance:auto; }
textarea.uf-input { resize:vertical; min-height:75px; }

/* ── MEMBERSHIP CARD ── */
.mem-status-card {
    border-radius:14px; padding:20px 22px; margin-bottom:24px;
    display:flex; align-items:center; gap:18px;
    animation: memPop .5s cubic-bezier(.34,1.56,.64,1) .1s both;
}
@keyframes memPop { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
.mem-status-card.active-mem  { background:linear-gradient(135deg,#e6fff5,#f0fff8); border:1.5px solid #b3f0d8; }
.mem-status-card.expired-mem { background:linear-gradient(135deg,#fff8e6,#fffbf0); border:1.5px solid #fde68a; }
.mem-status-card.no-mem      { background:#f8fbff; border:1.5px dashed #d0e4ff; }
.mem-icon { font-size:32px; }
.mem-title { font-size:15px; font-weight:700; color:#1a1a2e; }
.mem-sub   { font-size:12px; color:#666; margin-top:2px; }
.mem-badge {
    margin-left:auto; border-radius:20px; padding:4px 14px;
    font-size:12px; font-weight:700;
}
.badge-active  { background:#00b074; color:#fff; }
.badge-expired { background:#f59e0b; color:#fff; }
.badge-none    { background:#94a3b8; color:#fff; }

/* ── AMOUNT INPUT SPECIAL ── */
.amount-wrap { position:relative; }
.amount-wrap .currency {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    font-weight:700; color:#0a6ebd; font-size:15px;
}
.amount-wrap .uf-input { padding-left:30px; }

/* ── DATE RANGE VISUAL ── */
.date-range-bar {
    background:#f0f7ff; border-radius:10px; padding:12px 16px;
    display:flex; align-items:center; gap:10px; margin-bottom:20px;
    font-size:13px; color:#444; flex-wrap:wrap;
    animation: fadeInUp .4s ease .2s both;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
.date-range-bar .dr-item { display:flex; align-items:center; gap:6px; }
.date-range-bar .dr-arrow { color:#0a6ebd; font-size:16px; }
.date-range-bar strong { color:#0a6ebd; }

/* ── SUBMIT BTN ── */
.btn-save {
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff; border:none; border-radius:9px;
    padding:11px 30px; font-size:14px; font-weight:600;
    cursor:pointer; transition:opacity .25s, transform .2s;
    box-shadow:0 4px 14px rgba(10,110,189,.3);
}
.btn-save:hover { opacity:.9; transform:translateY(-1px); }
.btn-save:disabled { opacity:.6; cursor:not-allowed; transform:none; }

/* ── ERROR ALERT ── */
.uf-error {
    background:#fff0f0; border:1px solid #fecaca;
    border-radius:8px; padding:10px 14px;
    color:#dc2626; font-size:13px; margin-bottom:16px;
}
</style>

<div class="page-wrapper uform-page">
<div class="content">

    <div class="uform-topbar">
        <h4>
            <i class="fa fa-user-circle" style="color:#0a6ebd;margin-right:8px;"></i>
            {{ $isEdit ? 'Edit User' : 'Add User' }}
        </h4>
        <a href="{{ route('admin.user') }}" class="btn-back-u">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="uform-wrap">

        {{-- ── TAB NAV ── --}}
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#utab1" data-toggle="tab">
                    <i class="fa fa-user"></i> User Details
                </a>
            </li>
            @if($isEdit)
            <li class="nav-item">
                <a class="nav-link" href="#utab2" data-toggle="tab">
                    <i class="fa fa-star"></i> Membership
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content">

            {{-- ══ TAB 1: User Details ══ --}}
            <div class="tab-pane show active" id="utab1">

                <div class="sec-hd">
                    <div class="sec-ic ic-blue"><i class="fa fa-user"></i></div>
                    Basic Information
                </div>

                <div id="user_form_error" class="uf-error" style="display:none;"></div>

                <form id="user_form" method="POST" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $isEdit ? $user->id : '' }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="uf-input" name="name"
                                   value="{{ $isEdit ? $user->name : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="uf-input" name="email"
                                   value="{{ $isEdit ? $user->email : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">
                                Password {{ $isEdit ? '<small style="color:#999">(blank = no change)</small>' : '<span class="text-danger">*</span>' }}
                            </label>
                            <input type="password" class="uf-input" name="password"
                                   {{ $isEdit ? '' : 'required' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">Phone</label>
                            <input type="text" class="uf-input" name="phone_no"
                                   value="{{ $isEdit ? $user->phone_no : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">Gender</label>
                            <select class="uf-input" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male"   {{ ($isEdit && $user->gender == 'Male')   ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ ($isEdit && $user->gender == 'Female') ? 'selected' : '' }}>Female</option>
                                <option value="Other"  {{ ($isEdit && $user->gender == 'Other')  ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="uf-label">Address</label>
                            <textarea class="uf-input" name="address" rows="2">{{ $isEdit ? $user->address : '' }}</textarea>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn-save" id="save_user">
                            <i class="fa fa-save"></i> {{ $isEdit ? 'Update User' : 'Save User' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══ TAB 2: Membership ══ --}}
            @if($isEdit)
            <div class="tab-pane" id="utab2">

                @php
                    $mem = $membership ?? null;
                    $today = \Carbon\Carbon::today();
                    $memStatus = 'none';
                    if ($mem) {
                        $endDate = \Carbon\Carbon::parse($mem->membership_subscription_end_date);
                        $memStatus = $endDate->gte($today) ? 'active' : 'expired';
                    }
                @endphp

                {{-- Status Card --}}
                <div class="mem-status-card {{ $memStatus === 'active' ? 'active-mem' : ($memStatus === 'expired' ? 'expired-mem' : 'no-mem') }}">
                    <div class="mem-icon">
                        {{ $memStatus === 'active' ? '✅' : ($memStatus === 'expired' ? '⚠️' : '🔒') }}
                    </div>
                    <div>
                        <div class="mem-title">
                            {{ $memStatus === 'active' ? 'Active Membership' : ($memStatus === 'expired' ? 'Membership Expired' : 'No Membership') }}
                        </div>
                        <div class="mem-sub">
                            @if($mem)
                                ₹{{ number_format($mem->membership_amount, 2) }} &nbsp;|&nbsp;
                                {{ \Carbon\Carbon::parse($mem->membership_subscription_date)->format('d M Y') }}
                                →
                                {{ \Carbon\Carbon::parse($mem->membership_subscription_end_date)->format('d M Y') }}
                            @else
                                No membership record found for this user.
                            @endif
                        </div>
                    </div>
                    <span class="mem-badge {{ $memStatus === 'active' ? 'badge-active' : ($memStatus === 'expired' ? 'badge-expired' : 'badge-none') }}">
                        {{ ucfirst($memStatus) }}
                    </span>
                </div>

                <div class="sec-hd">
                    <div class="sec-ic ic-gold"><i class="fa fa-star"></i></div>
                    {{ $mem ? 'Update Membership' : 'Assign Membership' }}
                </div>

                <div id="mem_form_error" class="uf-error" style="display:none;"></div>

                {{-- Date range preview bar --}}
                <div class="date-range-bar" id="dateRangeBar" style="{{ $mem ? '' : 'display:none;' }}">
                    <div class="dr-item"><i class="fa fa-calendar" style="color:#0a6ebd;"></i> Start: <strong id="previewStart">{{ $mem ? \Carbon\Carbon::parse($mem->membership_subscription_date)->format('d M Y') : '' }}</strong></div>
                    <span class="dr-arrow">→</span>
                    <div class="dr-item"><i class="fa fa-calendar-check-o" style="color:#00b074;"></i> End: <strong id="previewEnd">{{ $mem ? \Carbon\Carbon::parse($mem->membership_subscription_end_date)->format('d M Y') : '' }}</strong></div>
                </div>

                <form id="membership_form" method="POST" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="uf-label">Membership Amount (₹) <span class="text-danger">*</span></label>
                            <div class="amount-wrap">
                                <span class="currency">₹</span>
                                <input type="number" class="uf-input" name="membership_amount"
                                       min="0" step="0.01" placeholder="0.00"
                                       value="{{ $mem ? $mem->membership_amount : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="uf-label">Subscription Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="uf-input" name="membership_subscription_date"
                                   id="startDate"
                                   value="{{ $mem ? $mem->membership_subscription_date : '' }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="uf-label">Subscription End Date <span class="text-danger">*</span></label>
                            <input type="date" class="uf-input" name="membership_subscription_end_date"
                                   id="endDate"
                                   value="{{ $mem ? $mem->membership_subscription_end_date : '' }}" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn-save" id="save_membership">
                            <i class="fa fa-star"></i> {{ $mem ? 'Update Membership' : 'Save Membership' }}
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>{{-- tab-content --}}
    </div>{{-- uform-wrap --}}

</div>
</div>

<script>
var interval = setInterval(function () {
    if (typeof $ === 'undefined') return;
    clearInterval(interval);

    /* ── Active tab from URL hash ── */
    var hash = window.location.hash;
    if (hash) {
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    }
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.getAttribute('href');
    });

    /* ── User Form Submit ── */
    $('#user_form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var btn   = $('#save_user');
        var label = '{{ $isEdit ? "Update User" : "Save User" }}';
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#user_form_error').hide();

        $.ajax({
            url: '{{ route("admin.user.add") }}',
            type: 'POST',
            data: $('#user_form').serialize(),
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
                if (res.status === 200) {
                    $.jGrowl(res.msg, { theme: 'success-theme', life: 2500 });
                    setTimeout(function () { window.location.href = '{{ route("admin.user") }}'; }, 1500);
                } else {
                    $('#user_form_error').text(res.msg).show();
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
                $('#user_form_error').text(xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'Something went wrong.').show();
            }
        });
    });

    /* ── Membership Form Submit ── */
    $('#membership_form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var btn   = $('#save_membership');
        var label = '{{ $membership ? "Update Membership" : "Save Membership" }}';
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#mem_form_error').hide();

        $.ajax({
            url: '{{ route("admin.user.membership") }}',
            type: 'POST',
            data: $('#membership_form').serialize(),
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fa fa-star"></i> ' + label);
                if (res.status === 200) {
                    $.jGrowl(res.msg, { theme: 'success-theme', life: 2500 });
                    setTimeout(function () {
                        window.location.hash = '#utab2';
                        location.reload();
                    }, 1500);
                } else {
                    $('#mem_form_error').text(res.msg).show();
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-star"></i> ' + label);
                $('#mem_form_error').text(xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'Something went wrong.').show();
            }
        });
    });

    /* ── Date range preview ── */
    function updateDatePreview() {
        var s  = $('#startDate').val();
        var en = $('#endDate').val();
        if (s || en) {
            $('#dateRangeBar').show();
            if (s)  $('#previewStart').text(new Date(s).toLocaleDateString('en-IN',  {day:'2-digit', month:'short', year:'numeric'}));
            if (en) $('#previewEnd').text(new Date(en).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'}));
        }
    }
    $('#startDate, #endDate').on('change', updateDatePreview);

}, 100);
</script>

@endsection
