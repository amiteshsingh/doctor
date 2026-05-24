@extends('doctor.layouts.app')

@section('content')

@php
$Image = isset($user->profile_image) && file_exists(public_path('storage/upload/profile_images/'.$user->profile_image))
    ? asset('storage/upload/profile_images/'.$user->profile_image)
    : asset('admin/assets/img/user.jpg');
@endphp

<style>
.ep-page { animation: pgFade .5s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* ── HERO BANNER ── */
.ep-hero {
    background: linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border-radius: 16px; padding: 30px 32px; color: #fff;
    position: relative; overflow: hidden; margin-bottom: 24px;
    animation: heroSlide .6s cubic-bezier(.25,.46,.45,.94) both;
}
@keyframes heroSlide { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }
.ep-hero::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; background:rgba(255,255,255,.07); border-radius:50%;
}
.ep-hero::after {
    content:''; position:absolute; bottom:-40px; right:100px;
    width:130px; height:130px; background:rgba(255,255,255,.05); border-radius:50%;
}

/* Avatar */
.ep-avatar-wrap { position:relative; display:inline-block; }
.ep-avatar {
    width:100px; height:100px; border-radius:50%; object-fit:cover;
    border:4px solid rgba(255,255,255,.8);
    box-shadow:0 8px 24px rgba(0,0,0,.2);
    transition:transform .3s;
    animation: avatarPop .7s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes avatarPop { from{opacity:0;transform:scale(.4)} to{opacity:1;transform:scale(1)} }
.ep-avatar:hover { transform:scale(1.06); }
.ep-avatar-edit {
    position:absolute; bottom:4px; right:4px;
    width:28px; height:28px; border-radius:50%;
    background:#fff; color:#0a6ebd;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.2);
    transition:transform .2s;
}
.ep-avatar-edit:hover { transform:scale(1.15); }

/* ── SECTION CARDS ── */
.ep-card {
    background:#fff; border-radius:14px;
    box-shadow:0 2px 16px rgba(0,0,0,.07);
    padding:24px 28px; margin-bottom:20px;
    animation:cardFade .5s ease both;
    transition:box-shadow .3s, transform .3s;
}
.ep-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.11); transform:translateY(-2px); }
@keyframes cardFade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.ep-card:nth-child(1){animation-delay:.1s}
.ep-card:nth-child(2){animation-delay:.2s}
.ep-card:nth-child(3){animation-delay:.3s}

.ep-card-head {
    display:flex; align-items:center; gap:10px;
    font-size:14px; font-weight:700; color:#1a1a2e;
    border-bottom:2px solid #f0f4ff;
    padding-bottom:10px; margin-bottom:20px;
}
.ep-ic {
    width:34px; height:34px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; color:#fff;
}
.ic-blue   { background:linear-gradient(135deg,#0a6ebd,#4da6ff); }
.ic-green  { background:linear-gradient(135deg,#00b074,#4cffb0); }
.ic-red    { background:linear-gradient(135deg,#ef4444,#f87171); }

/* ── INPUTS ── */
.ep-label {
    font-size:12px; font-weight:700; color:#888;
    text-transform:uppercase; letter-spacing:.5px;
    margin-bottom:5px; display:block;
}
.ep-input {
    border:1.5px solid #e2e8f0; border-radius:9px;
    padding:10px 14px; font-size:13.5px; width:100%;
    transition:border-color .25s, box-shadow .25s;
    background:#fafbff; color:#222;
}
.ep-input:focus {
    border-color:#0a6ebd; box-shadow:0 0 0 3px rgba(10,110,189,.1);
    outline:none; background:#fff;
}
select.ep-input { appearance:auto; }

/* Password toggle */
.pw-wrap { position:relative; }
.pw-toggle {
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    cursor:pointer; color:#888; font-size:14px; transition:color .2s;
}
.pw-toggle:hover { color:#0a6ebd; }

/* ── SAVE BTN ── */
.ep-save-btn {
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff; border:none; border-radius:10px;
    padding:12px 40px; font-size:15px; font-weight:700;
    cursor:pointer; transition:opacity .25s, transform .2s;
    box-shadow:0 4px 16px rgba(10,110,189,.3);
    animation: btnPop .5s cubic-bezier(.34,1.56,.64,1) .5s both;
}
@keyframes btnPop { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
.ep-save-btn:hover { opacity:.9; transform:translateY(-2px); }

/* ── ALERT ── */
.ep-alert-success {
    background:#e6fff5; border:1.5px solid #b3f0d8; border-radius:10px;
    padding:12px 16px; color:#00b074; font-weight:600; font-size:13px;
    margin-bottom:18px; animation:fadeInUp .4s ease both;
    display:flex; align-items:center; gap:8px;
}
.ep-alert-danger {
    background:#fff0f0; border:1.5px solid #fecaca; border-radius:10px;
    padding:12px 16px; color:#ef4444; font-size:13px;
    margin-bottom:18px; animation:fadeInUp .4s ease both;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>

<div class="page-wrapper ep-page">
<div class="content">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="ep-alert-success"><i class="fa fa-check-circle fa-lg"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="ep-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <ul class="mb-0 mt-1 pl-3">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('doctor.update-profile') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ── HERO BANNER ── --}}
        <div class="ep-hero">
            <div class="d-flex align-items-center flex-wrap" style="gap:22px;position:relative;z-index:1;">
                <div class="ep-avatar-wrap">
                    <img src="{{ $Image }}" id="avatarPreview" class="ep-avatar" alt="{{ $user->name }}">
                    <div class="ep-avatar-edit" onclick="document.getElementById('profile_image').click()" title="Change Photo">
                        <i class="fa fa-camera"></i>
                    </div>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                           style="display:none;" onchange="previewImg(this)">
                </div>
                <div style="animation:fadeInUp .5s ease .3s both;opacity:0;">
                    <h3 style="margin:0 0 4px;font-size:22px;font-weight:800;">{{ $user->name }}</h3>
                    <div style="font-size:13px;opacity:.85;">
                        <i class="fa fa-envelope mr-1"></i> {{ $user->email }}
                        @if($user->phone_no)
                            &nbsp;&nbsp;<i class="fa fa-phone mr-1"></i> {{ $user->phone_no }}
                        @endif
                    </div>
                    <div style="margin-top:8px;font-size:12px;opacity:.75;">
                        <i class="fa fa-camera mr-1"></i> Click the camera icon to change photo
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">

                {{-- ── BASIC INFO ── --}}
                <div class="ep-card">
                    <div class="ep-card-head">
                        <div class="ep-ic ic-blue"><i class="fa fa-user"></i></div>
                        Basic Information
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">Full Name</label>
                            <input type="text" name="name" class="ep-input"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">Date of Birth</label>
                            <input type="date" name="dob" class="ep-input"
                                   value="{{ old('dob', $user->dob) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">Gender</label>
                            <select name="gender" class="ep-input">
                                <option value="">Select Gender</option>
                                <option value="male"   {{ old('gender',$user->gender) == 'male'   ? 'selected':'' }}>Male</option>
                                <option value="female" {{ old('gender',$user->gender) == 'female' ? 'selected':'' }}>Female</option>
                                <option value="other"  {{ old('gender',$user->gender) == 'other'  ? 'selected':'' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">Phone Number</label>
                            <input type="text" name="phone_no" class="ep-input"
                                   value="{{ old('phone_no', $user->phone_no) }}">
                        </div>
                    </div>
                </div>

                {{-- ── CONTACT INFO ── --}}
                <div class="ep-card">
                    <div class="ep-card-head">
                        <div class="ep-ic ic-green"><i class="fa fa-map-marker"></i></div>
                        Contact & Address
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="ep-label">Address</label>
                            <input type="text" name="address" class="ep-input"
                                   value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="ep-label">State</label>
                            <input type="text" name="state" class="ep-input"
                                   value="{{ old('state', $user->state) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="ep-label">Country</label>
                            <input type="text" name="country" class="ep-input"
                                   value="{{ old('country', $user->country) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="ep-label">Pin Code</label>
                            <input type="text" name="pin_code" class="ep-input"
                                   value="{{ old('pin_code', $user->pin_code) }}">
                        </div>
                    </div>
                </div>

                {{-- ── PASSWORD ── --}}
                <div class="ep-card">
                    <div class="ep-card-head">
                        <div class="ep-ic ic-red"><i class="fa fa-lock"></i></div>
                        Change Password
                        <small style="color:#999;font-weight:400;font-size:12px;">(leave blank to keep current)</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">New Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="pw1" class="ep-input" placeholder="Min 6 characters">
                                <span class="pw-toggle" onclick="togglePw('pw1',this)"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="ep-label">Confirm Password</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_confirmation" id="pw2" class="ep-input" placeholder="Repeat password">
                                <span class="pw-toggle" onclick="togglePw('pw2',this)"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <button type="submit" class="ep-save-btn">
                        <i class="fa fa-save mr-2"></i> Save Changes
                    </button>
                </div>

            </div>

            {{-- ── RIGHT: Profile Summary ── --}}
            <div class="col-lg-4">
                <div class="ep-card" style="text-align:center;">
                    <img src="{{ $Image }}" id="sidePreview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #0a6ebd;margin-bottom:12px;">
                    <h5 style="font-weight:700;margin-bottom:4px;">{{ $user->name }}</h5>
                    <p style="font-size:12px;color:#888;margin-bottom:14px;">{{ $user->email }}</p>
                    <hr style="border-color:#f0f4ff;">
                    <div style="text-align:left;font-size:13px;">
                        @foreach([
                            ['fa-phone',   'Phone',   $user->phone_no   ?? '—'],
                            ['fa-venus-mars','Gender', ucfirst($user->gender ?? '—')],
                            ['fa-birthday-cake','DOB', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '—'],
                            ['fa-map-marker','Address',$user->address ?? '—'],
                            ['fa-calendar', 'Joined',  $user->created_at->format('d M Y')],
                        ] as $row)
                        <div style="display:flex;gap:10px;padding:7px 0;border-bottom:1px solid #f5f5f5;">
                            <i class="fa {{ $row[0] }}" style="color:#0a6ebd;width:16px;margin-top:2px;"></i>
                            <div>
                                <div style="font-size:10px;color:#aaa;font-weight:700;text-transform:uppercase;">{{ $row[1] }}</div>
                                <div style="color:#333;font-weight:600;">{{ $row[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
</div>

<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('sidePreview').src   = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePw(id, icon) {
    var inp = document.getElementById(id);
    var i   = icon.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        i.className = 'fa fa-eye-slash';
    } else {
        inp.type = 'password';
        i.className = 'fa fa-eye';
    }
}
</script>

@endsection
