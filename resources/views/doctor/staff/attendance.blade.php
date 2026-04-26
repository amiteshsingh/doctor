@extends('doctor.layouts.app')
@section('content')

<style>
.att-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

.att-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
.att-topbar h4 { margin:0; font-size:20px; font-weight:700; color:#1a1a2e; }

.att-wrap { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; }
.att-header { background:linear-gradient(135deg,#0a6ebd,#00b074); padding:18px 24px; color:#fff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.att-header h5 { margin:0; font-size:16px; font-weight:700; }
.att-date-input { background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4); color:#fff; border-radius:8px; padding:6px 12px; font-size:13px; }
.att-date-input::-webkit-calendar-picker-indicator { filter:invert(1); }

.att-table { width:100%; border-collapse:collapse; }
.att-table thead tr { background:#f0f7ff; }
.att-table th { padding:12px 16px; font-size:12px; font-weight:700; color:#0a6ebd; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; }
.att-table td { padding:12px 16px; border-bottom:1px solid #f0f4ff; vertical-align:middle; font-size:13.5px; }
.att-table tbody tr { transition:background .2s; animation: rowFade .4s ease both; }
.att-table tbody tr:hover { background:#f8fbff; }
@keyframes rowFade { from{opacity:0;transform:translateX(-10px)} to{opacity:1;transform:translateX(0)} }

/* Status buttons */
.status-group { display:flex; gap:6px; flex-wrap:wrap; }
.status-btn {
    padding:5px 12px; border-radius:20px; font-size:12px; font-weight:600;
    border:2px solid transparent; cursor:pointer; transition:all .2s;
    background:#f1f5f9; color:#64748b;
}
.status-btn:hover { transform:scale(1.05); }
.status-btn.active-present  { background:#e6fff5; color:#00b074; border-color:#00b074; }
.status-btn.active-absent   { background:#fff0f0; color:#ef4444; border-color:#ef4444; }
.status-btn.active-half_day { background:#fff8e6; color:#f59e0b; border-color:#f59e0b; }
.status-btn.active-leave    { background:#f0f0ff; color:#7c3aed; border-color:#7c3aed; }

.time-input {
    border:1.5px solid #e2e8f0; border-radius:8px; padding:6px 10px;
    font-size:12px; width:100px; transition:border-color .2s;
}
.time-input:focus { border-color:#0a6ebd; outline:none; }

.note-input {
    border:1.5px solid #e2e8f0; border-radius:8px; padding:6px 10px;
    font-size:12px; width:140px; transition:border-color .2s;
}
.note-input:focus { border-color:#0a6ebd; outline:none; }

.staff-avatar {
    width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#0a6ebd,#00b074);
    display:inline-flex; align-items:center; justify-content:center;
    color:#fff; font-weight:700; font-size:13px; margin-right:8px;
}

.btn-save-att {
    background:linear-gradient(135deg,#0a6ebd,#00b074); color:#fff;
    border:none; border-radius:10px; padding:11px 30px; font-size:14px;
    font-weight:700; cursor:pointer; transition:opacity .2s, transform .2s;
    box-shadow:0 4px 14px rgba(10,110,189,.3);
}
.btn-save-att:hover { opacity:.9; transform:translateY(-1px); }
.btn-save-att:disabled { opacity:.6; cursor:not-allowed; }

/* Summary bar */
.summary-bar { display:flex; gap:12px; padding:14px 24px; background:#f8fbff; border-top:1px solid #e2e8f0; flex-wrap:wrap; }
.sum-item { display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; }
.sum-dot { width:10px; height:10px; border-radius:50%; }
</style>

<div class="page-wrapper att-page">
<div class="content">

    <div class="att-topbar">
        <h4><i class="fa fa-calendar-check-o" style="color:#0a6ebd;margin-right:8px;"></i> Mark Attendance</h4>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('doctor.staff.attendance.report') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                <i class="fa fa-bar-chart"></i> Monthly Report
            </a>
            <a href="{{ route('doctor.staff.index') }}" class="btn btn-sm btn-secondary" style="border-radius:8px;">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="att-wrap">
        <div class="att-header">
            <h5><i class="fa fa-users mr-2"></i> Staff Attendance</h5>
            <div style="display:flex;align-items:center;gap:10px;">
                <label style="margin:0;font-size:13px;opacity:.85;">Date:</label>
                <input type="date" id="attendanceDate" class="att-date-input"
                       value="{{ $date }}" max="{{ today()->toDateString() }}">
                <button onclick="loadAttendance()" class="btn btn-sm"
                        style="background:rgba(255,255,255,.25);color:#fff;border:1px solid rgba(255,255,255,.4);border-radius:8px;">
                    <i class="fa fa-refresh"></i> Load
                </button>
            </div>
        </div>

        <div id="att_form_msg"></div>

        @if($staff->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fa fa-users fa-3x mb-3" style="color:#d0e4ff;"></i><br>
                No active staff found. <a href="{{ route('doctor.staff.add') }}">Add staff first.</a>
            </div>
        @else
        <form id="attendance_form">
            @csrf
            <input type="hidden" name="date" id="formDate" value="{{ $date }}">

            <div class="table-responsive">
                <table class="att-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Member</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $i => $s)
                        @php $att = $existing[$s->id] ?? null; @endphp
                        <tr style="animation-delay:{{ $i * 0.05 }}s">
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;">
                                    <div class="staff-avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $s->name }}</strong><br>
                                        <small style="color:#888;">{{ $s->phone ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span style="background:#f0f4ff;color:#0a6ebd;border-radius:12px;padding:3px 10px;font-size:12px;">{{ $s->role ?? '—' }}</span></td>
                            <td>
                                @php $curStatus = $att ? $att->status : ''; @endphp
                                <div class="status-group" data-staff="{{ $s->id }}">
                                    @foreach(['present'=>'✅ Present','absent'=>'❌ Absent','half_day'=>'🌓 Half Day','leave'=>'🏖 Leave'] as $val => $label)
                                    <button type="button"
                                        class="status-btn {{ $curStatus === $val ? 'active-'.$val : '' }}"
                                        data-status="{{ $val }}"
                                        onclick="setStatus(this, {{ $s->id }})">{{ $label }}</button>
                                    @endforeach
                                    <input type="hidden" name="attendance[{{ $s->id }}][status]"
                                           id="status_{{ $s->id }}" value="{{ $curStatus }}">
                                </div>
                            </td>
                            <td>
                                <input type="time" class="time-input"
                                       name="attendance[{{ $s->id }}][check_in]"
                                       value="{{ $att->check_in ?? '' }}">
                            </td>
                            <td>
                                <input type="time" class="time-input"
                                       name="attendance[{{ $s->id }}][check_out]"
                                       value="{{ $att->check_out ?? '' }}">
                            </td>
                            <td>
                                <input type="text" class="note-input" placeholder="Optional note"
                                       name="attendance[{{ $s->id }}][note]"
                                       value="{{ $att->note ?? '' }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary bar --}}
            <div class="summary-bar" id="summaryBar">
                <span class="sum-item"><div class="sum-dot" style="background:#00b074;"></div> Present: <span id="cnt_present">0</span></span>
                <span class="sum-item"><div class="sum-dot" style="background:#ef4444;"></div> Absent: <span id="cnt_absent">0</span></span>
                <span class="sum-item"><div class="sum-dot" style="background:#f59e0b;"></div> Half Day: <span id="cnt_half_day">0</span></span>
                <span class="sum-item"><div class="sum-dot" style="background:#7c3aed;"></div> Leave: <span id="cnt_leave">0</span></span>
                <span class="sum-item" style="margin-left:auto;">Total: <strong>{{ $staff->count() }}</strong></span>
            </div>

            <div style="padding:16px 24px;">
                <button type="submit" class="btn-save-att" id="saveAttBtn">
                    <i class="fa fa-save"></i> Save Attendance
                </button>
            </div>
        </form>
        @endif
    </div>

</div>
</div>

<script>
function setStatus(btn, staffId) {
    var group = btn.closest('.status-group');
    group.querySelectorAll('.status-btn').forEach(function(b) {
        b.className = 'status-btn';
    });
    var status = btn.getAttribute('data-status');
    btn.classList.add('active-' + status);
    document.getElementById('status_' + staffId).value = status;
    updateSummary();
}

function updateSummary() {
    var counts = { present: 0, absent: 0, half_day: 0, leave: 0 };
    document.querySelectorAll('[id^="status_"]').forEach(function(el) {
        var v = el.value;
        if (counts[v] !== undefined) counts[v]++;
    });
    document.getElementById('cnt_present').textContent  = counts.present;
    document.getElementById('cnt_absent').textContent   = counts.absent;
    document.getElementById('cnt_half_day').textContent = counts.half_day;
    document.getElementById('cnt_leave').textContent    = counts.leave;
}

function loadAttendance() {
    var date = document.getElementById('attendanceDate').value;
    window.location = '{{ route("doctor.staff.attendance") }}?date=' + date;
}

// Change date on input change
document.getElementById('attendanceDate').addEventListener('change', function() {
    document.getElementById('formDate').value = this.value;
});

updateSummary();

var interval = setInterval(function () {
    if (typeof $ === 'undefined') return;
    clearInterval(interval);

    $('#attendance_form').off('submit').on('submit', function(e) {
        e.preventDefault();
        var btn = $('#saveAttBtn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#att_form_msg').html('');

        $.ajax({
            url: '{{ route("doctor.staff.attendance.save") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Attendance');
                var cls = res.status == 200 ? 'success' : 'danger';
                $('#att_form_msg').html('<div class="alert alert-' + cls + ' mx-4 mt-3">' + res.msg + '</div>');
                setTimeout(function() { $('#att_form_msg').html(''); }, 3000);
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Attendance');
                $('#att_form_msg').html('<div class="alert alert-danger mx-4 mt-3">Something went wrong.</div>');
            }
        });
    });
}, 100);
</script>
@endsection
