@extends('doctor.layouts.app')

@section('content')

@php $isEdit = isset($doctor->id) && $doctor->id; @endphp

<style>
.dform-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

/* ── TOP BAR ── */
.dform-topbar {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:22px;
}
.dform-topbar h4 { margin:0; font-size:20px; font-weight:700; color:#1a1a2e; }
.btn-back {
    background:#f0f4ff; color:#0a6ebd; border:1px solid #d0e4ff;
    border-radius:8px; padding:7px 16px; font-size:13px;
    text-decoration:none; transition:background .2s;
}
.btn-back:hover { background:#dbeafe; color:#0a6ebd; text-decoration:none; }

/* ── TAB NAV ── */
.dform-tabs { background:#fff; border-radius:14px; box-shadow:0 2px 16px rgba(0,0,0,.07); overflow:hidden; }
.dform-tabs .nav-tabs {
    background:linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border:none; padding:0 16px; display:flex; flex-wrap:wrap;
}
.dform-tabs .nav-tabs .nav-item .nav-link {
    color:rgba(255,255,255,.75); border:none; border-radius:0;
    padding:14px 18px; font-size:13px; font-weight:600;
    position:relative; transition:color .25s; background:transparent;
}
.dform-tabs .nav-tabs .nav-item .nav-link i { margin-right:6px; }
.dform-tabs .nav-tabs .nav-item .nav-link::after {
    content:''; position:absolute; bottom:0; left:0; right:0;
    height:3px; background:#fff; border-radius:3px 3px 0 0;
    transform:scaleX(0); transition:transform .3s ease;
}
.dform-tabs .nav-tabs .nav-item .nav-link.active { color:#fff; }
.dform-tabs .nav-tabs .nav-item .nav-link.active::after { transform:scaleX(1); }
.dform-tabs .tab-content { padding:28px 28px 20px; }

/* ── SECTION HEADER ── */
.sec-head {
    display:flex; align-items:center; gap:10px;
    font-size:14px; font-weight:700; color:#1a1a2e;
    border-bottom:2px solid #f0f4ff; padding-bottom:10px; margin-bottom:20px;
}
.sec-icon {
    width:32px; height:32px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; color:#fff;
}
.si-blue   { background:linear-gradient(135deg,#0a6ebd,#4da6ff); }
.si-green  { background:linear-gradient(135deg,#00b074,#4cffb0); }
.si-purple { background:linear-gradient(135deg,#7c3aed,#a78bfa); }
.si-orange { background:linear-gradient(135deg,#f59e0b,#fcd34d); }

/* ── FORM FIELDS ── */
.dform-label {
    font-size:13px; font-weight:600; color:#555;
    margin-bottom:5px; display:block;
}
.dform-control {
    border:1.5px solid #e2e8f0; border-radius:8px;
    padding:9px 13px; font-size:13.5px; width:100%;
    transition:border-color .25s, box-shadow .25s;
    background:#fafbff;
}
.dform-control:focus {
    border-color:#0a6ebd; box-shadow:0 0 0 3px rgba(10,110,189,.1);
    outline:none; background:#fff;
}
select.dform-control { appearance:auto; }
textarea.dform-control { resize:vertical; min-height:80px; }

/* Avatar preview */
.avatar-preview-wrap { position:relative; display:inline-block; margin-top:10px; }
.avatar-preview {
    width:80px; height:80px; border-radius:50%; object-fit:cover;
    border:3px solid #0a6ebd; box-shadow:0 4px 12px rgba(10,110,189,.2);
    transition:transform .3s;
}
.avatar-preview:hover { transform:scale(1.08); }

/* ── SPECIALIZATION CHECKBOXES ── */
.spec-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:10px; }
.spec-item {
    display:flex; align-items:center; gap:8px;
    background:#f8fbff; border:1.5px solid #e2e8f0;
    border-radius:8px; padding:9px 12px; cursor:pointer;
    transition:border-color .2s, background .2s;
}
.spec-item:hover { border-color:#0a6ebd; background:#f0f7ff; }
.spec-item input[type=checkbox] { width:16px; height:16px; accent-color:#0a6ebd; cursor:pointer; }
.spec-item label { margin:0; font-size:13px; cursor:pointer; color:#333; }
.spec-item.checked { border-color:#0a6ebd; background:#e8f3ff; }

/* ── AVAILABILITY ── */
.day-card {
    background:#f8fbff; border:1.5px solid #e2e8f0;
    border-radius:10px; padding:14px 16px; margin-bottom:12px;
    transition:border-color .2s;
}
.day-card:hover { border-color:#0a6ebd; }
.day-label {
    font-size:13px; font-weight:700; color:#0a6ebd;
    margin-bottom:10px; display:flex; align-items:center; gap:6px;
}
.slot-row { display:flex; align-items:center; gap:10px; margin-bottom:8px; flex-wrap:wrap; }
.slot-row .dform-control { flex:1; min-width:120px; }
.btn-slot-add { background:#00b074; color:#fff; border:none; border-radius:6px; width:30px; height:30px; font-size:16px; cursor:pointer; transition:background .2s; }
.btn-slot-add:hover { background:#009060; }
.btn-slot-rem { background:#ef4444; color:#fff; border:none; border-radius:6px; width:30px; height:30px; font-size:16px; cursor:pointer; transition:background .2s; }
.btn-slot-rem:hover { background:#dc2626; }

/* ── SUBMIT BTN ── */
.btn-submit {
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    color:#fff; border:none; border-radius:8px;
    padding:10px 28px; font-size:14px; font-weight:600;
    cursor:pointer; transition:opacity .25s, transform .2s;
    box-shadow:0 4px 14px rgba(10,110,189,.3);
}
.btn-submit:hover { opacity:.9; transform:translateY(-1px); }
.btn-submit:disabled { opacity:.6; cursor:not-allowed; }

/* ── HR DIVIDER ── */
.dform-divider { border:none; border-top:2px dashed #e2e8f0; margin:22px 0; }
</style>

<div class="page-wrapper dform-page">
<div class="content">

    {{-- Top Bar --}}
    <div class="dform-topbar">
        <h4><i class="fa fa-user-md" style="color:#0a6ebd;margin-right:8px;"></i>
            {{ $isEdit ? 'Edit Doctor' : 'Add Doctor' }}
        </h4>
        <a href="{{ route('doctor.mydoctor') }}" class="btn-back">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="dform-tabs">

        {{-- ── TAB NAV ── --}}
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#tab1" data-toggle="tab">
                    <i class="fa fa-user"></i> Basic Details
                </a>
            </li>
            @if($isEdit)
            <li class="nav-item">
                <a class="nav-link" href="#tab2" data-toggle="tab">
                    <i class="fa fa-stethoscope"></i> Specialization
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab3" data-toggle="tab">
                    <i class="fa fa-map-marker"></i> Location & Education
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab4" data-toggle="tab">
                    <i class="fa fa-calendar-check-o"></i> Availability
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab5" data-toggle="tab">
                    <i class="fa fa-image"></i> Gallery
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab6" data-toggle="tab">
                    <i class="fa fa-android"></i> App Guide
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content">

            {{-- ══ TAB 1: Basic Details ══ --}}
            <div class="tab-pane show active" id="tab1">
                <form method="POST" id="doctor_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $isEdit ? $doctor->id : '' }}">

                    <div class="sec-head">
                        <div class="sec-icon si-blue"><i class="fa fa-user"></i></div>
                        Basic Information
                    </div>

                    <div class="row">
                        {{-- Avatar --}}
                        <div class="col-md-12 mb-3">
                            <label class="dform-label">Profile Picture</label>
                            <div>
                                <input type="file" name="profile_pic" id="profile_pic" accept="image/*"
                                       style="display:none;" onchange="previewAvatar(this)">
                                <div class="avatar-preview-wrap">
                                    @php
                                        $profileImage = isset($doctor->profile_pic) && !empty($doctor->profile_pic)
                                            ? asset('storage/upload/doctor/'.$doctor->profile_pic)
                                            : asset('admin/assets/img/user.jpg');
                                    @endphp
                                    <img id="avatarPreview" src="{{ $profileImage }}" class="avatar-preview">
                                </div>
                                <br>
                                <button type="button" onclick="document.getElementById('profile_pic').click()"
                                        class="btn-back mt-2" style="cursor:pointer;">
                                    <i class="fa fa-upload"></i> Upload Photo
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="dform-control" name="name"
                                   value="{{ $doctor->name ?? '' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Phone Number</label>
                            <input type="text" class="dform-control" name="phone_no"
                                   value="{{ $doctor->phone_no ?? '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Email</label>
                            <input type="email" class="dform-control" name="email"
                                   value="{{ $doctor->email ?? '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Gender</label>
                            <select class="dform-control" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male"   {{ ($doctor->gender ?? '') == 'Male'   ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ ($doctor->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        @if($isEdit)
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Hospital</label>
                            <select class="dform-control" name="hospital_id">
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}"
                                        {{ ($doctor->hospital_id ?? '') == $hospital->id ? 'selected' : '' }}>
                                        {{ $hospital->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Status</label>
                            <select class="dform-control" name="status">
                                <option value="">Select Status</option>
                                <option value="1" {{ ($doctor->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ isset($doctor->status) && $doctor->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="save_doctor" class="btn-submit">
                            <i class="fa fa-save"></i> {{ $isEdit ? 'Update' : 'Save' }} Doctor
                        </button>
                    </div>
                </form>
            </div>

            @if($isEdit)

            {{-- ══ TAB 2: Specialization ══ --}}
            <div class="tab-pane" id="tab2">
                <form method="POST" id="doctor_specialization_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $doctor->id }}">

                    <div class="sec-head">
                        <div class="sec-icon si-green"><i class="fa fa-stethoscope"></i></div>
                        Select Specializations
                    </div>

                    <div class="spec-grid">
                        @foreach($specializations as $spec)
                            @php $checked = isset($doctor->specialization_data) && in_array($spec->id, $doctor->specialization_data); @endphp
                            <div class="spec-item {{ $checked ? 'checked' : '' }}" onclick="toggleSpec(this)">
                                <input type="checkbox" id="spec_{{ $spec->id }}"
                                       name="specialization_ids[]" value="{{ $spec->id }}"
                                       {{ $checked ? 'checked' : '' }}>
                                <label for="spec_{{ $spec->id }}">{{ $spec->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="save_doctor_specialization" class="btn-submit">
                            <i class="fa fa-save"></i> Save Specializations
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══ TAB 3: Location & Education ══ --}}
            <div class="tab-pane" id="tab3">
                <form method="POST" id="doctor_location_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $doctor->id }}">

                    {{-- Location --}}
                    <div class="sec-head">
                        <div class="sec-icon si-blue"><i class="fa fa-map-marker"></i></div>
                        Location Details
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Practice Name</label>
                            <input type="text" class="dform-control" name="practice_name"
                                   placeholder="Dr. ..." value="{{ $doctor->practice_name ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Phone</label>
                            <input type="text" class="dform-control" name="location_phone"
                                   value="{{ $doctor->location_phone ?? '' }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="dform-label">Address</label>
                            <input type="text" class="dform-control" name="address"
                                   value="{{ $doctor->address ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="dform-label">City</label>
                            <input type="text" class="dform-control" name="city"
                                   value="{{ $doctor->city ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="dform-label">State</label>
                            <select name="state" class="dform-control">
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->state_name }}"
                                        {{ ($doctor->state ?? '') == $state->state_name ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="dform-label">Pin Code</label>
                            <input type="number" class="dform-control" name="pin_code"
                                   value="{{ $doctor->zip_code ?? '' }}" min="100000" max="999999">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Experience <small>(from year)</small></label>
                            <select name="experience" class="dform-control">
                                <option value="">-- Select Year --</option>
                                @for($year = date('Y'); $year >= 1980; $year--)
                                    <option value="{{ $year }}"
                                        {{ ($doctor->experience ?? '') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Registration Number</label>
                            <input type="text" class="dform-control" name="registration_no"
                                   value="{{ $doctor->registration_no ?? '' }}">

                        </div>

                    </div>

                    <hr class="dform-divider">

                    {{-- Education --}}
                    <div class="sec-head">
                        <div class="sec-icon si-purple"><i class="fa fa-graduation-cap"></i></div>
                        Education
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Degree Type</label>
                            <input type="text" class="dform-control" name="degree_type"
                                   value="{{ $doctor->degree_type ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Institution Name</label>
                            <input type="text" class="dform-control" name="institution_name"
                                   value="{{ $doctor->institution_name ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="dform-label">Graduation Year</label>
                            <input type="text" class="dform-control" name="graduation_year"
                                   value="{{ $doctor->graduation_year ?? '' }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="dform-label">Details</label>
                            <textarea class="dform-control" name="education_details" rows="3">{{ $doctor->education_details ?? '' }}</textarea>
                        </div>
                    </div>

                    <hr class="dform-divider">

                    {{-- Languages --}}
                    <div class="sec-head">
                        <div class="sec-icon si-orange"><i class="fa fa-language"></i></div>
                        Languages
                    </div>
                    <div class="spec-grid mb-4">
                        @foreach($languages as $lang)
                            @php $langChecked = isset($doctor->language_data) && in_array($lang->id, $doctor->language_data); @endphp
                            <div class="spec-item {{ $langChecked ? 'checked' : '' }}" onclick="toggleSpec(this)">
                                <input type="checkbox" id="lang_{{ $lang->id }}"
                                       name="languages[]" value="{{ $lang->id }}"
                                       {{ $langChecked ? 'checked' : '' }}>
                                <label for="lang_{{ $lang->id }}">{{ $lang->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" id="save_doctor_location" class="btn-submit">
                        <i class="fa fa-save"></i> Save Information
                    </button>
                </form>
            </div>

            {{-- ══ TAB 4: Availability ══ --}}
            <div class="tab-pane" id="tab4">
                <form method="POST" id="doctor_availability_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $doctor->id }}">

                    <div class="sec-head">
                        <div class="sec-icon si-green"><i class="fa fa-calendar-check-o"></i></div>
                        Weekly Availability
                    </div>

                    @php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; @endphp

                    @foreach($days as $day)
                    <div class="day-card" data-day="{{ $day }}">
                        <div class="day-label">
                            <i class="fa fa-calendar-o"></i> {{ $day }}
                        </div>
                        <div class="slot-wrapper">
                            @php $slots = $doctor->availability[$day] ?? [['start_time'=>'','end_time'=>'']]; @endphp
                            @foreach($slots as $idx => $slot)
                            <div class="slot-row">
                                <input type="text" class="dform-control datetimepicker3"
                                       name="availability[{{ $day }}][{{ $idx }}][start_time]"
                                       placeholder="Start Time" value="{{ $slot['start_time'] ?? '' }}">
                                <input type="text" class="dform-control datetimepicker3"
                                       name="availability[{{ $day }}][{{ $idx }}][end_time]"
                                       placeholder="End Time" value="{{ $slot['end_time'] ?? '' }}">
                                @if($idx == 0)
                                    <button type="button" class="btn-slot-add add-slot" title="Add slot">+</button>
                                @else
                                    <button type="button" class="btn-slot-rem remove-slot" title="Remove">−</button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-3">
                        <button type="submit" id="save_doctor_availability" class="btn-submit">
                            <i class="fa fa-save"></i> Save Availability
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══ TAB 5: Gallery ══ --}}
            <div class="tab-pane" id="tab5">
                <div class="sec-head">
                    <div class="sec-icon si-purple"><i class="fa fa-image"></i></div>
                    Doctor Gallery
                </div>
                @include('components.gallery-tab', [
                    'entityId'    => $doctor->id,
                    'entityType'  => 'doctor',
                    'uploadRoute' => route('doctor.gallery.upload'),
                    'deleteRoute' => route('doctor.gallery.delete'),
                    'imagesRoute' => route('doctor.gallery.images'),
                ])
            </div>

            {{-- ══ TAB 6: App Guide ══ --}}
            <div class="tab-pane" id="tab6">

                <div class="sec-head">
                    <div class="sec-icon" style="background:linear-gradient(135deg,#00b074,#38f9d7);"><i class="fa fa-android"></i></div>
                    RogiSewa Doctor App — Complete Guide
                </div>

                {{-- Download Banner --}}
                <div style="background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);border-radius:14px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#00b074,#38f9d7);display:flex;align-items:center;justify-content:center;font-size:26px;">
                                <i class="fa fa-user-md" style="color:#fff;"></i>
                            </div>
                            <div>
                                <div style="color:#fff;font-size:18px;font-weight:700;">RogiSewa Doctor App</div>
                                <div style="color:rgba(255,255,255,.6);font-size:13px;">Doctors & Clinics ke liye — Free App</div>
                            </div>
                        </div>
                        <p style="color:rgba(255,255,255,.75);font-size:13px;margin:0;max-width:480px;">
                            Apni clinic manage karein, appointments track karein, prescription invoice generate karein — sab kuch ek hi app mein. Bilkul FREE!
                        </p>
                    </div>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                       style="display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border-radius:12px;padding:12px 24px;text-decoration:none;font-size:14px;font-weight:700;box-shadow:0 4px 16px rgba(0,176,116,.4);transition:transform .2s,box-shadow .2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,176,116,.5)'"
                       onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(0,176,116,.4)'">
                        <i class="fa fa-android" style="font-size:20px;"></i>
                        Download on Google Play
                    </a>
                </div>

                <div class="row g-3">

                    {{-- Features --}}
                    <div class="col-lg-6">
                        <div style="background:#f8fbff;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px;height:100%;">
                            <div style="font-size:14px;font-weight:700;color:#1a1a2e;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                                <span style="background:linear-gradient(135deg,#0a6ebd,#4da6ff);color:#fff;border-radius:8px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;"><i class="fa fa-star"></i></span>
                                App Features / मुख्य विशेषताएँ
                            </div>
                            @php
                            $features = [
                                ['fa-id-card',          '#667eea', 'Doctor Profile Listing',       'डॉक्टर प्रोफ़ाइल लिस्टिंग'],
                                ['fa-hospital-o',       '#f5576c', 'Hospital / Clinic Management',  'हॉस्पिटल / क्लिनिक मैनेजमेंट'],
                                ['fa-calendar-check-o', '#4facfe', 'Appointment Management',        'अपॉइंटमेंट मैनेजमेंट'],
                                ['fa-file-text',        '#f59e0b', 'Prescription Invoice (Free)',    'प्रिस्क्रिप्शन इनवॉइस (फ्री)'],
                                ['fa-medkit',           '#00b074', 'Medicine Management',           'दवाई मैनेजमेंट'],
                                ['fa-users',            '#a18cd1', 'Staff & Attendance Tracking',   'स्टाफ व अटेंडेंस ट्रैकिंग'],
                                ['fa-bell',             '#e91e8c', 'Booking Reminders',             'बुकिंग रिमाइंडर'],
                                ['fa-bar-chart',        '#10b981', 'Reports & Analytics',           'रिपोर्ट्स और एनालिटिक्स'],
                            ];
                            @endphp
                            @foreach($features as $f)
                            <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;margin-bottom:8px;">
                                <div style="width:34px;height:34px;border-radius:8px;background:{{ $f[1] }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fa {{ $f[0] }}" style="color:{{ $f[1] }};font-size:14px;"></i>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $f[2] }}</div>
                                    <div style="font-size:11px;color:#888;">{{ $f[3] }}</div>
                                </div>
                                <i class="fa fa-check-circle" style="color:#00b074;font-size:14px;"></i>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- How to Use Steps --}}
                    <div class="col-lg-6">
                        <div style="background:#f8fbff;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:16px;">
                            <div style="font-size:14px;font-weight:700;color:#1a1a2e;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                                <span style="background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border-radius:8px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;"><i class="fa fa-list-ol"></i></span>
                                How to Use / कैसे उपयोग करें
                            </div>
                            @php
                            $steps = [
                                ['Download & install from Google Play',          'Google Play से डाउनलोड करें',              '#0a6ebd'],
                                ['Login with your registered email',              'अपने ईमेल से लॉगिन करें',                  '#e91e8c'],
                                ['Complete your Doctor Profile',                  'डॉक्टर प्रोफ़ाइल पूरी करें',               '#f59e0b'],
                                ['Add your Hospital / Clinic details',            'हॉस्पिटल / क्लिनिक जोड़ें',               '#00b074'],
                                ['Set your weekly availability & time slots',     'अपनी उपलब्धता सेट करें',                  '#7c3aed'],
                                ['Manage appointments & create invoices',         'अपॉइंटमेंट व इनवॉइस मैनेज करें',          '#10b981'],
                                ['Track staff attendance & manage medicines',     'स्टाफ अटेंडेंस व दवाई मैनेज करें',        '#f5576c'],
                            ];
                            @endphp
                            @foreach($steps as $i => $step)
                            <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;">
                                <span style="width:26px;height:26px;border-radius:50%;background:{{ $step[2] }};color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">{{ $i+1 }}</span>
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ $step[0] }}</div>
                                    <div style="font-size:11px;color:#888;">{{ $step[1] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Why use --}}
                        <div style="background:#f8fbff;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px;">
                            <div style="font-size:14px;font-weight:700;color:#1a1a2e;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                                <span style="background:linear-gradient(135deg,#e91e8c,#f59e0b);color:#fff;border-radius:8px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;"><i class="fa fa-thumbs-up"></i></span>
                                Why Use This App?
                            </div>
                            @foreach([
                                ['🆓', '#00b074', 'Completely Free',          'Koi hidden charge nahi'],
                                ['📱', '#0a6ebd', 'Easy to Use',              'Simple aur fast interface'],
                                ['🔔', '#f59e0b', 'Smart Reminders',          'Booking alerts & notifications'],
                                ['📄', '#7c3aed', 'PDF Prescription (Only use in Website)',         'Professional invoice PDF generate karein'],
                                ['🌐', '#e91e8c', 'Online Visibility',        'Patients aapko RogiSewa par dhundh sakte hain'],
                            ] as $w)
                            <div style="display:flex;align-items:center;gap:10px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;margin-bottom:8px;">
                                <span style="font-size:18px;">{{ $w[0] }}</span>
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:{{ $w[1] }};">{{ $w[2] }}</div>
                                    <div style="font-size:11px;color:#888;">{{ $w[3] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>{{-- row --}}

                {{-- ══ APPOINTMENT BOOKING INFO ══ --}}
                <div style="margin-top:24px;">

                    {{-- Section heading --}}
                    <div style="display:flex;align-items:center;gap:10px;border-bottom:2px solid #f0f4ff;padding-bottom:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#e91e8c,#f59e0b);display:flex;align-items:center;justify-content:center;font-size:14px;color:#fff;">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#1a1a2e;">Appointment Booking — How It Works</div>
                            <div style="font-size:12px;color:#888;">अपॉइंटमेंट बुकिंग — यह कैसे काम करता है</div>
                        </div>
                    </div>

                    {{-- Alert info box --}}
                    <div style="background:linear-gradient(135deg,#fff7ed,#fef3c7);border:1.5px solid #f59e0b;border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;gap:14px;align-items:flex-start;">
                        <div style="font-size:26px;flex-shrink:0;">📲</div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#92400e;margin-bottom:4px;">
                                Appointment booking ke liye RogiSewa Patient App zaroori hai!
                            </div>
                            <div style="font-size:13px;color:#78350f;line-height:1.7;">
                                <strong>English:</strong> Patients book appointments through the <strong>RogiSewa Patient App</strong>. Once a patient books, the appointment automatically appears in your <strong>RogiSewa Doctor App</strong>. To receive and manage bookings, you must have the Doctor App installed and your profile must be active.
                            </div>
                            <div style="font-size:13px;color:#78350f;line-height:1.7;margin-top:6px;">
                                <strong>Hindi:</strong> मरीज़ <strong>RogiSewa Patient App</strong> से अपॉइंटमेंट बुक करते हैं। जैसे ही मरीज़ बुकिंग करता है, वह अपॉइंटमेंट आपके <strong>RogiSewa Doctor App</strong> में अपने आप दिखने लगती है। बुकिंग पाने के लिए Doctor App इंस्टॉल होना और आपकी प्रोफ़ाइल Active होनी चाहिए।
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Patient side --}}
                        <div class="col-md-6">
                            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:18px;height:100%;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#1a73e8,#0d47a1);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa fa-heartbeat" style="color:#fff;font-size:16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#1a1a2e;">Patient Side — RogiSewa Patient App</div>
                                        <div style="font-size:11px;color:#888;">मरीज़ की तरफ से — Patient App</div>
                                    </div>
                                </div>
                                @php
                                $patientSteps = [
                                    ['📥', '#1a73e8', 'Patient downloads RogiSewa Patient App',                    'मरीज़ RogiSewa Patient App डाउनलोड करता है'],
                                    ['🔍', '#00b074', 'Searches for doctor by name, specialization or city',       'नाम, स्पेशलाइज़ेशन या शहर से डॉक्टर खोजता है'],
                                    ['👤', '#7c3aed', 'Opens your doctor profile',                                 'आपकी डॉक्टर प्रोफ़ाइल खोलता है'],
                                    ['📅', '#f59e0b', 'Selects available date & time slot',                       'उपलब्ध तारीख और टाइम स्लॉट चुनता है'],
                                    ['✅', '#10b981', 'Confirms the appointment booking',                          'अपॉइंटमेंट बुकिंग कन्फ़र्म करता है'],
                                    ['🔔', '#e91e8c', 'Patient receives booking confirmation notification',        'मरीज़ को बुकिंग कन्फ़र्मेशन नोटिफ़िकेशन मिलती है'],
                                ];
                                @endphp
                                @foreach($patientSteps as $i => $s)
                                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;background:#fff;border:1px solid #d1fae5;border-radius:8px;padding:9px 12px;">
                                    <span style="font-size:16px;flex-shrink:0;">{{ $s[0] }}</span>
                                    <div>
                                        <div style="font-size:12px;font-weight:600;color:#1a1a2e;">{{ $s[2] }}</div>
                                        <div style="font-size:11px;color:#666;">{{ $s[3] }}</div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- Patient app download --}}
                                <a href="https://play.google.com/store/apps/details?id=com.rogisewa" target="_blank"
                                   style="display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#1a73e8,#0d47a1);color:#fff;border-radius:8px;padding:10px;text-decoration:none;font-size:13px;font-weight:700;margin-top:12px;">
                                    <i class="fa fa-android" style="font-size:16px;"></i>
                                    Patient App — Google Play
                                </a>
                            </div>
                        </div>

                        {{-- Doctor side --}}
                        <div class="col-md-6">
                            <div style="background:#f0f7ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:18px;height:100%;">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#00b074,#38f9d7);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa fa-user-md" style="color:#fff;font-size:16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#1a1a2e;">Doctor Side — RogiSewa Doctor App</div>
                                        <div style="font-size:11px;color:#888;">डॉक्टर की तरफ से — Doctor App</div>
                                    </div>
                                </div>
                                @php
                                $doctorSteps = [
                                    ['📲', '#00b074', 'Doctor App install karo aur login karo',                   'Doctor App इंस्टॉल करें और लॉगिन करें'],
                                    ['✅', '#0a6ebd', 'Profile Active rakho taaki patients book kar sakein',      'प्रोफ़ाइल Active रखें ताकि मरीज़ बुक कर सकें'],
                                    ['🔔', '#e91e8c', 'New booking aane par instant notification milti hai',      'नई बुकिंग आने पर तुरंत नोटिफ़िकेशन मिलती है'],
                                    ['📋', '#7c3aed', 'App mein Appointments section mein booking dikhai deti hai','App के Appointments सेक्शन में बुकिंग दिखती है'],
                                    ['👁️', '#f59e0b', 'Patient ka naam, date, time aur details dekho',            'मरीज़ का नाम, तारीख, समय और विवरण देखें'],
                                    ['📄', '#10b981', 'Prescription invoice generate karo — bilkul FREE',         'प्रिस्क्रिप्शन इनवॉइस बनाएं — बिल्कुल FREE'],
                                ];
                                @endphp
                                @foreach($doctorSteps as $i => $s)
                                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;background:#fff;border:1px solid #dbeafe;border-radius:8px;padding:9px 12px;">
                                    <span style="font-size:16px;flex-shrink:0;">{{ $s[0] }}</span>
                                    <div>
                                        <div style="font-size:12px;font-weight:600;color:#1a1a2e;">{{ $s[2] }}</div>
                                        <div style="font-size:11px;color:#666;">{{ $s[3] }}</div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- Doctor app download --}}
                                <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                                   style="display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border-radius:8px;padding:10px;text-decoration:none;font-size:13px;font-weight:700;margin-top:12px;">
                                    <i class="fa fa-android" style="font-size:16px;"></i>
                                    Doctor App — Google Play
                                </a>
                            </div>
                        </div>

                    </div>{{-- row --}}

                    {{-- Flow diagram --}}
                    <div style="margin-top:20px;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px;">
                        <div style="font-size:13px;font-weight:700;color:#1a1a2e;margin-bottom:16px;text-align:center;">
                            📊 Booking Flow — बुकिंग का पूरा प्रवाह
                        </div>
                        <div style="display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:6px;">
                            @php
                            $flow = [
                                ['#1a73e8', '📱', 'Patient App',       'मरीज़ App'],
                                ['#f59e0b', '🔍', 'Doctor Search',     'डॉक्टर खोजें'],
                                ['#7c3aed', '📅', 'Select Slot',       'स्लॉट चुनें'],
                                ['#10b981', '✅', 'Book Confirm',      'बुकिंग कन्फ़र्म'],
                                ['#e91e8c', '🔔', 'Notification',      'नोटिफ़िकेशन'],
                                ['#00b074', '📋', 'Doctor App',        'Doctor App में दिखे'],
                            ];
                            @endphp
                            @foreach($flow as $i => $f)
                                <div style="text-align:center;">
                                    <div style="background:{{ $f[0] }}15;border:2px solid {{ $f[0] }};border-radius:10px;padding:10px 14px;min-width:80px;">
                                        <div style="font-size:20px;">{{ $f[1] }}</div>
                                        <div style="font-size:11px;font-weight:700;color:{{ $f[0] }};margin-top:4px;">{{ $f[2] }}</div>
                                        <div style="font-size:10px;color:#888;">{{ $f[3] }}</div>
                                    </div>
                                </div>
                                @if($i < count($flow)-1)
                                <div style="font-size:18px;color:#cbd5e1;font-weight:700;">→</div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>{{-- appointment section --}}

                {{-- Bottom CTA --}}
                <div style="margin-top:20px;background:linear-gradient(135deg,#00b074,#0a6ebd);border-radius:12px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <div style="color:#fff;font-size:15px;font-weight:700;">📲 Abhi Download Karein — It's FREE!</div>
                        <div style="color:rgba(255,255,255,.8);font-size:12px;margin-top:4px;">Package: <code style="background:rgba(255,255,255,.15);padding:2px 8px;border-radius:4px;color:#fff;">com.rogisewadr</code></div>
                    </div>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewadr" target="_blank"
                       style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#00b074;border-radius:10px;padding:10px 22px;text-decoration:none;font-size:14px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,.15);">
                        <i class="fa fa-android" style="font-size:18px;"></i>
                        Get it on Google Play
                    </a>
                </div>

            </div>{{-- tab6 --}}

            @endif

        </div>{{-- tab-content --}}
    </div>{{-- dform-tabs --}}

</div>
</div>

<script>
/* Avatar preview */
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* Specialization / Language checkbox toggle */
function toggleSpec(el) {
    var cb = el.querySelector('input[type=checkbox]');
    cb.checked = !cb.checked;
    el.classList.toggle('checked', cb.checked);
}

document.addEventListener('DOMContentLoaded', function () {

    /* Availability slot add/remove */
    document.querySelectorAll('.day-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            var day = card.getAttribute('data-day');
            var wrapper = card.querySelector('.slot-wrapper');

            if (e.target.classList.contains('add-slot')) {
                var idx = wrapper.querySelectorAll('.slot-row').length;
                var row = document.createElement('div');
                row.className = 'slot-row';
                row.innerHTML =
                    '<input type="text" class="dform-control datetimepicker3" name="availability[' + day + '][' + idx + '][start_time]" placeholder="Start Time">' +
                    '<input type="text" class="dform-control datetimepicker3" name="availability[' + day + '][' + idx + '][end_time]" placeholder="End Time">' +
                    '<button type="button" class="btn-slot-rem remove-slot" title="Remove">−</button>';
                wrapper.appendChild(row);
                initPicker();
            }

            if (e.target.classList.contains('remove-slot')) {
                e.target.closest('.slot-row').remove();
            }
        });
    });

    function initPicker() {
        if (typeof $ !== 'undefined' && $.fn.datetimepicker) {
            $('.datetimepicker3').datetimepicker({ format: 'hh:mm A' });
        }
    }

    var pickerInterval = setInterval(function () {
        if (typeof $ !== 'undefined' && $.fn.datetimepicker) {
            clearInterval(pickerInterval);
            initPicker();
        }
    }, 100);
});
</script>

@endsection
