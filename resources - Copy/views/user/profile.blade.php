@extends('user.layouts.app')
@section('title', 'RogiSewa - My Profile')

@section('user_content')
@php
    $pic = auth()->user()->profile_image
        ? (app()->environment('local')
            ? asset('uploads/profile_images/' . auth()->user()->profile_image)
            : asset('storage/uploads/profile_images/' . auth()->user()->profile_image))
        : asset('img/user.jpg');
@endphp

<style>
.prof-card {
    background:#fff; border-radius:18px;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    overflow:hidden;
}
.prof-card-head {
    background:linear-gradient(135deg,#667eea,#764ba2);
    padding:20px 24px; color:#fff;
    display:flex; align-items:center; gap:10px;
}
.prof-card-head h5 { margin:0; font-size:16px; font-weight:700; }
.prof-body { padding:28px 24px; }

/* Avatar preview */
.avatar-section { text-align:center; margin-bottom:24px; }
.avatar-preview-wrap { position:relative; display:inline-block; }
.avatar-preview {
    width:90px; height:90px; border-radius:50%; object-fit:cover;
    border:3px solid #667eea;
    box-shadow:0 4px 16px rgba(102,126,234,.3);
    transition:transform .3s;
    animation: avatarPop .6s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes avatarPop { from{opacity:0;transform:scale(.5)} to{opacity:1;transform:scale(1)} }
.avatar-preview:hover { transform:scale(1.06); }
.avatar-edit-btn {
    position:absolute; bottom:2px; right:2px;
    width:26px; height:26px; border-radius:50%;
    background:#667eea; color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; cursor:pointer;
    box-shadow:0 2px 8px rgba(0,0,0,.2);
    transition:transform .2s;
}
.avatar-edit-btn:hover { transform:scale(1.15); }

/* Fields */
.pf-label { font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; display:block; }
.pf-input {
    width:100%; border:1.5px solid #e2e8f0; border-radius:9px;
    padding:10px 13px; font-size:13.5px; background:#fafbff;
    transition:border-color .25s, box-shadow .25s;
}
.pf-input:focus { border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.1); outline:none; background:#fff; }
select.pf-input { appearance:auto; }
textarea.pf-input { resize:vertical; min-height:70px; }

/* Save btn */
.pf-btn {
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff; border:none; border-radius:10px;
    padding:11px 30px; font-size:14px; font-weight:700;
    cursor:pointer; transition:opacity .2s, transform .2s;
    box-shadow:0 4px 14px rgba(102,126,234,.3);
}
.pf-btn:hover { opacity:.9; transform:translateY(-1px); }

/* Success alert */
.pf-success {
    background:#e6fff5; border:1.5px solid #b3f0d8;
    border-radius:10px; padding:11px 16px;
    color:#00b074; font-size:13px; font-weight:600;
    margin-bottom:18px; display:flex; align-items:center; gap:8px;
    animation: fadeInUp .4s ease both;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

.form-row-anim { animation: fadeInUp .4s ease both; }
.form-row-anim:nth-child(1){animation-delay:.1s}
.form-row-anim:nth-child(2){animation-delay:.18s}
.form-row-anim:nth-child(3){animation-delay:.26s}
.form-row-anim:nth-child(4){animation-delay:.34s}
.form-row-anim:nth-child(5){animation-delay:.42s}
</style>

<div class="prof-card">
    <div class="prof-card-head">
        <i class="fa fa-user-circle fa-lg"></i>
        <h5>My Profile</h5>
    </div>
    <div class="prof-body">

        @if(session('success'))
        <div class="pf-success"><i class="fa fa-check-circle fa-lg"></i> {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="avatar-section">
                <div class="avatar-preview-wrap">
                    <img src="{{ $pic }}" id="avatarPrev" class="avatar-preview" alt="Profile">
                    <div class="avatar-edit-btn" onclick="document.getElementById('profile_image').click()">
                        <i class="fa fa-camera"></i>
                    </div>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                           style="display:none;" onchange="previewAvatar(this)">
                </div>
                <div style="font-size:12px;color:#aaa;margin-top:6px;">Click camera to change photo</div>
                @error('profile_image')<div style="color:#ef4444;font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 form-row-anim">
                    <label class="pf-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="pf-input @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:#ef4444;font-size:12px;">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3 form-row-anim">
                    <label class="pf-label">Email</label>
                    <input type="email" name="email" class="pf-input @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">
                    @error('email')<div style="color:#ef4444;font-size:12px;">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3 form-row-anim">
                    <label class="pf-label">Gender</label>
                    <select name="gender" class="pf-input">
                        <option value="">Select Gender</option>
                        @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" {{ old('gender',$user->gender) == $g ? 'selected':'' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 form-row-anim">
                    <label class="pf-label">Date of Birth</label>
                    <input type="date" name="dob" class="pf-input" value="{{ old('dob', $user->dob) }}">
                </div>
                <div class="col-md-12 mb-4 form-row-anim">
                    <label class="pf-label">Address</label>
                    <textarea name="address" class="pf-input" rows="2">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <button type="submit" class="pf-btn">
                <i class="fa fa-save mr-2"></i> Update Profile
            </button>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById('avatarPrev').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
