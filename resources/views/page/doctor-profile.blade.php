@extends('page.layouts.app')

@section('title', 'RogiSewa - ' . $doctor->name ?? 'Doctor Profile')

@section('content')

<style>
/* Blink effect for day name only */
@keyframes blink-color {
    0%, 50%, 100% { opacity: 1; color: blue; }
    25%, 75% { opacity: 0; color: red; }
}

.blink {
    animation: blink-color 1s infinite;
}
</style>


<div class="container py-5">
    @if($doctor)
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="card shadow-lg border-0 rounded-4 text-center p-4 bg-light">
                    <img src="{{ asset('storage/upload/doctor/' . ($doctor->profile_pic ?? 'user.jpg')) }}" 
                         class="rounded-circle mx-auto mb-3 border border-4 border-primary" 
                         alt="{{ $doctor->name ?? 'Doctor' }}" 
                         style="width: 160px; height: 160px; object-fit: cover;">
                    
                    <h3 class="fw-bold text-dark">{{ $doctor->name ?? 'N/A' }}</h3>
                    <p class="text-muted mb-2"><i class="fa fa-envelope text-danger me-2"></i>{{ $doctor->email ?? 'Not available' }}</p>
                    <p><i class="fa fa-phone text-success me-2"></i>{{ $doctor->phone_no ?? 'N/A' }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-gradient bg-success fs-6 p-2">
                            {{ $doctor->experience ? now()->year - $doctor->experience : 'N/A' }}+ Years Experience
                        </span>
                    </div>

                    @auth
                        @if(auth()->user()->role?->role === 'user')
                        <div class="mt-3">
                            <button id="favBtn" onclick="toggleFavourite()" class="btn btn-sm {{ $isFavourite ? 'btn-danger' : 'btn-outline-danger' }}" style="border-radius:20px;">
                                <i class="fa fa-heart me-1"></i>
                                <span id="favText">{{ $isFavourite ? 'Saved' : 'Add to Favourite' }}</span>
                            </button>
                        </div>
                        @endif
                    @endauth
                </div>

                <!-- Specializations -->
                <div class="card shadow mt-4 border-0 rounded-4 p-3 bg-white">
                    <h5 class="text-primary fw-bold border-bottom pb-2">Specializations</h5>
                    <ul class="list-unstyled">
                        @forelse($doctor->specializations as $spec)
                            <li class="mb-1">
                                <i class="fa fa-stethoscope text-danger me-2"></i>{{ $spec->specialization->name ?? 'N/A' }}
                            </li>
                        @empty
                            <li class="text-muted">No specialization added</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Languages -->
                <div class="card shadow mt-4 border-0 rounded-4 p-3 bg-white">
                    <h5 class="text-primary fw-bold border-bottom pb-2">Languages</h5>
                    <div>
                        
                        @forelse($doctor->languages as $lang)
                            <span class="badge bg-info text-dark me-1 mb-1">{{ $lang->language->name ?? 'N/A' }}</span>
                        @empty
                            <p class="text-muted">No languages added</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Practice Locations -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h4 class="text-danger fw-bold mb-0">📍 Practice Location</h4>
                        @if($isOnlineBooking)
                        <a href="#" class="btn btn-danger btn-sm" onclick="openBookingModal(); return false;">
                            <i class="fa fa-calendar-check me-1"></i> Book Appointment
                        </a>
                        @else
                        <a href="#" class="btn btn-warning btn-sm" onclick="openOfflineModal(); return false;">
                            <i class="fa fa-calendar-check me-1"></i> Book Appointment
                        </a>
                        @endif
                    </div>
                    @forelse($doctor->locations as $loc)
                        <div class="mb-3 p-3 rounded bg-light">
                            <h6 class="fw-bold text-primary">{{ $loc->practice_name ?? 'N/A' }}</h6>
                            <p class="mb-1">
                                {{ $loc->address ?? '' }}, {{ $loc->city ?? '' }}, 
                                {{ $loc->state ?? '' }} - {{ $loc->zip_code ?? '' }}
                            </p>
                            <p class="mb-1"><i class="fa fa-phone text-success me-2"></i>{{ $loc->phone ?? 'N/A' }}</p>
                            @if($loc->website)
                                <a href="{{ $loc->website }}" target="_blank" class="text-decoration-none text-info">
                                    🌐 {{ $loc->website }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No practice location available</p>
                    @endforelse
                </div>


                <!-- Availability -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="fw-bold text-warning mb-3">
                        <i class="bi bi-clock-history me-2"></i> Availability
                    </h4>

                    @if($doctor->availability && count($doctor->availability) > 0)
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Day</th>
                                    <th>Start</th>
                                    <th>End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctor->availability as $slot)
                                    @php
                                        $today = \Carbon\Carbon::now()->format('l'); // Current day like "Thursday"
                                        $isToday = ($slot->day == $today); // assuming $slot->day is lowercase
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-dark {{ $isToday ? 'blink' : '' }}">
                                            <i class="bi bi-calendar3 me-2 text-primary"></i>
                                            {{ ucfirst($slot->day) ?? 'N/A' }}
                                        </td>
                                        <td class="text-success">
                                            <i class="bi bi-alarm me-1"></i> 
                                            {!! $isToday ? '<b>' . ($slot->start_time ?? 'N/A') . '</b>' : ($slot->start_time ?? 'N/A') !!}
                                        </td>
                                        <td class="text-danger">
                                            <i class="bi bi-alarm-fill me-1"></i> 
                                            {!! $isToday ? '<b>' . ($slot->end_time ?? 'N/A') . '</b>' : ($slot->end_time ?? 'N/A') !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-exclamation-circle me-2"></i> No schedule available
                        </div>
                    @endif
                </div>






                @if(!empty($doctor->educations->first()->details))
                    <!-- About Doctor -->
                    <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                        <h4 class="text-success fw-bold border-bottom pb-2">👨‍⚕️ About Doctor</h4>
                        <p class="mb-0">{!! $doctor->educations->first()->details !!}</p>
                    </div>
                @endif


                <!-- Education -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="text-info fw-bold border-bottom pb-2">🎓 Education</h4>
                    <ul class="list-unstyled">
                        @forelse($doctor->educations as $edu)
                            <li class="mb-3">
                                <strong class="text-dark">{{ $edu->degree_type ?? 'N/A' }}</strong> 
                                @if(!empty($edu->institution_name))
                                    from <span class="text-primary">{{ $edu->institution_name ?? 'N/A' }}</span> 
                                    ({{ $edu->graduation_year ?? 'N/A' }})
                                 @endif
                            
                            </li>
                        @empty
                            <li class="text-muted">No education info</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Portfolio Gallery -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white" id="publicGallerySection" style="display:none;">
                    <h4 class="text-secondary fw-bold border-bottom pb-2">🖼️ Portfolio Gallery</h4>
                    <div id="publicGalleryGrid" class="row mt-2"></div>
                </div>
            </div>
        </div>
    @else
        <p class="text-danger text-center fw-bold">❌ Doctor not found</p>
    @endif
</div>

<!-- Booking Modal -->
<div id="bookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; overflow-y:auto;">
    <div class="d-flex align-items-center justify-content-center" style="min-height:100%; padding:15px;">
        <div class="w-100" style="max-width:650px;">

            <!-- Header -->
            <div class="text-white p-4" style="background:linear-gradient(135deg,#13C5DD,#354F8E); border-radius:16px 16px 0 0;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-stethoscope" style="color:#13C5DD;font-size:20px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold">Book Appointment</h5>
                            <small style="opacity:0.8;"><i class="fa fa-user-md mr-1"></i> {{ $doctor->name }}</small>
                        </div>
                    </div>
                    <button onclick="closeBookingModal()" style="background:rgba(255,255,255,0.15);border:none;color:#fff;border-radius:50%;width:34px;height:34px;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
                </div>
            </div>

            <!-- Body -->
            <div class="bg-white p-4" style="border-radius:0 0 16px 16px; box-shadow:0 10px 40px rgba(0,0,0,0.15);">
                <form id="bookingForm">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    <!-- Patient Name -->
                    <div class="form-group">
                        <label class="small font-weight-bold" style="color:#555;">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" class="form-control" placeholder="Enter full name" required style="border-radius:8px;">
                    </div>

                    <!-- Age & Gender -->
                    <div class="form-row d-flex">
                        <div class="form-group col-6">
                            <label class="small font-weight-bold" style="color:#555;">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" class="form-control" placeholder="Age" min="0" required style="border-radius:8px;">
                        </div>
                        <div class="form-group col-6">
                            <label class="small font-weight-bold" style="color:#555;">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required style="border-radius:8px;">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="small font-weight-bold" style="color:#555;">Phone No <span class="text-danger">*</span></label>
                        <input type="text" name="patient_phone_no" class="form-control" placeholder="Enter phone number" required style="border-radius:8px;">
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label class="small font-weight-bold" style="color:#555;">Address</label>
                        <textarea name="patient_address" class="form-control" rows="2" placeholder="Enter address (optional)" style="border-radius:8px;"></textarea>
                    </div>

                    <!-- Booking Date -->
                    <div class="form-group">
                        <label class="small font-weight-bold" style="color:#555;">Booking Date <span class="text-danger">*</span></label>
                        <input type="date" name="booking_date" id="booking_date" class="form-control" required min="{{ date('Y-m-d') }}" onchange="loadTimeSlots(this.value)" style="border-radius:8px;">
                    </div>

                    <!-- Time Slots -->
                    <div class="form-group" id="time_slot_section" style="display:none;">
                        <label class="small font-weight-bold" style="color:#555;"><i class="fa fa-clock mr-1" style="color:#1a73e8;"></i>Select Time Slot <span class="text-danger">*</span></label>
                        <input type="hidden" name="booking_time" id="booking_time" required>
                        <div id="time_slots" class="p-2 border rounded" style="background:#f8f9fa; border-radius:8px !important;"></div>
                    </div>

                    <div id="booking_msg"></div>

                    <!-- Buttons -->
                    <div class="row mt-3">
                        <div class="col-8">
                            <button type="submit" class="btn btn-block font-weight-bold text-white" style="background:linear-gradient(135deg,#13C5DD,#354F8E);border:none;border-radius:8px;height:44px;">
                                <i class="fa fa-check-circle mr-1"></i> Confirm Appointment
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" onclick="closeBookingModal()" class="btn btn-block btn-outline-secondary font-weight-bold" style="border-radius:8px;height:44px;">Cancel</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div id="bookingBackdrop" onclick="closeBookingModal()" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1049;"></div>

<script>
var slotStart    = '{{ $slotStart }}';
var slotEnd      = '{{ $slotEnd }}';
var slotDuration = {{ (int)$slotDuration }};

function loadTimeSlots(date) {
    if (!date) return;
    var now = new Date();
    var today = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-' + String(now.getDate()).padStart(2,'0');
    var isToday = (date === today);
    var currentMinutes = now.getHours() * 60 + now.getMinutes();

    // Parse start/end from invoice_master
    var startParts = slotStart.split(':');
    var endParts   = slotEnd.split(':');
    var startMins  = parseInt(startParts[0]) * 60 + parseInt(startParts[1]);
    var endMins    = parseInt(endParts[0])   * 60 + parseInt(endParts[1]);

    fetch('{{ route("booked.slots") }}?doctor_id={{ $doctor->id }}&date=' + date)
    .then(function(r) { return r.json(); })
    .then(function(booked) {
        var slots = [];
        for (var m = startMins; m < endMins; m += slotDuration) {
            var h    = Math.floor(m / 60);
            var min  = m % 60;
            var ampm = h >= 12 ? 'PM' : 'AM';
            var h12  = h > 12 ? h - 12 : (h === 0 ? 12 : h);
            var label = (h12 < 10 ? '0' : '') + h12 + ':' + (min < 10 ? '0' : '') + min + ' ' + ampm;
            var value = (h < 10 ? '0' : '') + h + ':' + (min < 10 ? '0' : '') + min;
            slots.push({
                label:    label,
                value:    value,
                isPast:   isToday && m <= currentMinutes,
                isBooked: booked.indexOf(value) !== -1
            });
        }

        if (slots.length === 0) {
            document.getElementById('time_slots').innerHTML = '<p class="text-muted small mb-0">No slots available.</p>';
            document.getElementById('time_slot_section').style.display = 'block';
            return;
        }

        var html = '';
        slots.forEach(function(s) {
            if (s.isBooked) {
                html += '<span class="time-slot booked" title="Already booked">' + s.label + '</span>';
            } else if (s.isPast) {
                html += '<span class="time-slot disabled" title="Time passed">' + s.label + '</span>';
            } else {
                html += '<span class="time-slot" onclick="selectSlot(this, \'' + s.value + '\')">' + s.label + '</span>';
            }
        });

        document.getElementById('time_slots').innerHTML = html;
        document.getElementById('time_slot_section').style.display = 'block';
        document.getElementById('booking_time').value = '';
    });
}
function selectSlot(el, val) {
    document.querySelectorAll('.time-slot').forEach(function(s) { s.classList.remove('active'); });
    el.classList.add('active');
    document.getElementById('booking_time').value = val;
}
</script>

<style>
.time-slot {
    display: inline-block;
    padding: 5px 12px;
    margin: 3px;
    border: 1px solid #13C5DD;
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
    color: #13C5DD;
    background: #fff;
    transition: all 0.2s;
}
.time-slot:hover, .time-slot.active {
    background: #13C5DD;
    color: #fff;
}
.time-slot.disabled {
    border-color: #ccc;
    color: #aaa;
    background: #f0f0f0;
    cursor: not-allowed;
    text-decoration: line-through;
}
.time-slot.booked {
    border-color: #dc3545;
    color: #dc3545;
    background: #fff5f5;
    cursor: not-allowed;
    text-decoration: line-through;
    opacity: 0.7;
}
</style>

<script>
function openBookingModal() {
    @guest
    window.location.href = '{{ route('user.login') }}?redirect={{ urlencode(url()->current()) }}&book=1';
    return;
    @endguest
    document.getElementById('bookingModal').style.display = 'block';
    document.getElementById('bookingModal').style.zIndex = '1050';
    document.getElementById('bookingBackdrop').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
    document.getElementById('bookingBackdrop').style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('booking_msg').innerHTML = '';
    document.getElementById('time_slot_section').style.display = 'none';
    document.getElementById('time_slots').innerHTML = '';
    document.getElementById('booking_time').value = '';
}
document.getElementById('bookingBackdrop').addEventListener('click', closeBookingModal);

@if(request('book') == '1' && auth()->check())
openBookingModal();
@endif

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var btn = form.querySelector('button[type=submit]');
    btn.disabled = true;
    document.getElementById('booking_msg').innerHTML = '';

    var formData = new FormData(form);

    fetch('{{ route("book.appointment") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        document.getElementById('booking_msg').innerHTML =
            '<div class="alert alert-' + (res.status === 200 ? 'success' : 'danger') + ' mt-2">' + res.msg + '</div>';
        if (res.status === 200) { form.reset(); }
        btn.disabled = false;
    })
    .catch(function() { btn.disabled = false; });
});
</script>

<script>
function toggleFavourite() {
    var btn = document.getElementById('favBtn');
    var text = document.getElementById('favText');
    btn.disabled = true;
    fetch('{{ route("user.favourite.toggle") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ doctor_id: {{ $doctor->id }} })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.action === 'added') {
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            text.innerText = 'Saved';
        } else {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
            text.innerText = 'Add to Favourite';
        }
        btn.disabled = false;
    })
    .catch(function() { btn.disabled = false; });
}
</script>

<script>
window.addEventListener('load', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '{{ route("doctor.gallery.images") }}?id={{ $doctor->id }}&type=doctor', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function() {
        var grid = document.getElementById('publicGalleryGrid');
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (!res.images || res.images.length === 0) {
                return;
            }
            var html = '';
            res.images.forEach(function(img) {
                html += '<div class="col-md-3 col-sm-4 col-6 mb-3">' +
                    '<img src="' + img.url + '" class="img-fluid rounded shadow-sm" ' +
                    'style="height:130px;width:100%;object-fit:cover;cursor:pointer;" ' +
                    'onclick="openLightbox(\'' + img.url + '\')">' +
                    '</div>';
            });
            grid.innerHTML = html;
            document.getElementById('publicGallerySection').style.display = 'block';
        }
    };
    xhr.send();
});

function openLightbox(url) {
    var lb = document.createElement('div');
    lb.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:pointer;';
    lb.innerHTML = '<img src="' + url + '" style="max-width:90%;max-height:90%;border-radius:8px;box-shadow:0 0 30px rgba(0,0,0,0.5);">';
    lb.onclick = function() { document.body.removeChild(lb); };
    document.body.appendChild(lb);
}
</script>

<!-- Offline Booking Modal -->
<div id="offlineModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; overflow-y:auto;">
    <div class="d-flex align-items-center justify-content-center" style="min-height:100%; padding:15px;">
        <div class="w-100" style="max-width:480px;">
            <div class="text-white p-4" style="background:linear-gradient(135deg,#f59e0b,#d97706); border-radius:16px 16px 0 0;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-hospital" style="color:#d97706;font-size:20px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Book Appointment</h5>
                            <small style="opacity:.85;"><i class="fa fa-user-md me-1"></i>{{ $doctor->name }}</small>
                        </div>
                    </div>
                    <button onclick="closeOfflineModal()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;border-radius:50%;width:34px;height:34px;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
                </div>
            </div>
            <div class="bg-white p-4 text-center" style="border-radius:0 0 16px 16px; box-shadow:0 10px 40px rgba(0,0,0,0.15);">
                <div class="mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;">
                        <i class="fa fa-info-circle fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Offline Booking Only</h5>
                    <p class="text-muted mb-3">
                        This doctor is currently providing <strong>offline booking</strong> service only.
                        Online appointment booking is not available at this time.
                    </p>
                    <p class="text-muted mb-4">You can contact the doctor directly to schedule an appointment:</p>
                </div>

                @php $loc = $doctor->locations->first(); @endphp

                @if(!empty($doctor->phone_no))
                <a href="tel:{{ $doctor->phone_no }}" class="btn btn-success w-100 mb-2">
                    <i class="fa fa-phone me-2"></i> Call {{ $doctor->phone_no }}
                </a>
                @endif

                @if(!empty($loc->phone))
                <a href="tel:{{ $loc->phone }}" class="btn btn-outline-success w-100 mb-2">
                    <i class="fa fa-clinic-medical me-2"></i> Clinic: {{ $loc->phone }}
                </a>
                @endif

                @if(empty($doctor->phone_no) && empty($loc->phone))
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    Contact number not available. Please visit the clinic directly.
                </div>
                @endif

                @if($loc)
                <p class="text-muted small mt-3 mb-3">
                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                    {{ implode(', ', array_filter([$loc->address, $loc->city, $loc->state, $loc->zip_code])) }}
                </p>
                @endif

                <button onclick="closeOfflineModal()" class="btn btn-outline-secondary w-100">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div id="offlineBackdrop" onclick="closeOfflineModal()" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1049;"></div>

<script>
function openOfflineModal() {
    document.getElementById('offlineModal').style.display = 'block';
    document.getElementById('offlineBackdrop').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeOfflineModal() {
    document.getElementById('offlineModal').style.display = 'none';
    document.getElementById('offlineBackdrop').style.display = 'none';
    document.body.style.overflow = '';
}
</script>

@endsection
