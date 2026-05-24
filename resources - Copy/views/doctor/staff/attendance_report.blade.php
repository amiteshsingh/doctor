@extends('doctor.layouts.app')
@section('content')

<style>
.rep-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

.rep-wrap { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; }
.rep-header { background:linear-gradient(135deg,#0a6ebd,#00b074); padding:18px 24px; color:#fff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.rep-header h5 { margin:0; font-weight:700; }
.month-input { background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4); color:#fff; border-radius:8px; padding:6px 12px; font-size:13px; }

/* Calendar table */
.rep-table { width:100%; border-collapse:collapse; font-size:12px; }
.rep-table th { padding:8px 6px; text-align:center; background:#f0f7ff; color:#0a6ebd; font-weight:700; border:1px solid #e2e8f0; white-space:nowrap; }
.rep-table td { padding:7px 6px; text-align:center; border:1px solid #f0f4ff; vertical-align:middle; }
.rep-table .staff-name { text-align:left; padding:8px 14px; font-weight:600; white-space:nowrap; min-width:180px; }
.rep-table tbody tr:hover { background:#f8fbff; }

/* Sticky Staff column */
.rep-table th.staff-name,
.rep-table td.staff-name {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #fff;
    box-shadow: 3px 0 8px rgba(0,0,0,.08);
}
.rep-table thead th.staff-name {
    background: #f0f7ff;
    z-index: 3;
}

/* Status dots */
.att-dot {
    display:inline-block; width:28px; height:28px; border-radius:50%;
    line-height:28px; font-size:11px; font-weight:700; cursor:default;
}
.dot-P { background:#e6fff5; color:#00b074; border:1.5px solid #b3f0d8; }
.dot-A { background:#fff0f0; color:#ef4444; border:1.5px solid #fecaca; }
.dot-H { background:#fff8e6; color:#f59e0b; border:1.5px solid #fde68a; }
.dot-L { background:#f0f0ff; color:#7c3aed; border:1.5px solid #ddd6fe; }
.dot-N { background:#f1f5f9; color:#94a3b8; border:1.5px solid #e2e8f0; }

/* Summary cards */
.sum-cards { display:flex; gap:14px; padding:18px 24px; flex-wrap:wrap; background:#f8fbff; border-top:1px solid #e2e8f0; }
.sum-card { flex:1; min-width:120px; border-radius:12px; padding:14px 16px; text-align:center; animation:cardFade .4s ease both; }
@keyframes cardFade { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.sum-card .num { font-size:26px; font-weight:800; }
.sum-card .lbl { font-size:11px; font-weight:600; margin-top:2px; }
</style>

<div class="page-wrapper rep-page">
<div class="content">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <h4 style="margin:0;font-size:20px;font-weight:700;color:#1a1a2e;">
            <i class="fa fa-bar-chart" style="color:#0a6ebd;margin-right:8px;"></i> Monthly Attendance Report
        </h4>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('doctor.staff.attendance') }}" class="btn btn-sm btn-primary" style="border-radius:8px;">
                <i class="fa fa-calendar-check-o"></i> Mark Attendance
            </a>
            <a href="{{ route('doctor.staff.index') }}" class="btn btn-sm btn-secondary" style="border-radius:8px;">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="rep-wrap">
        <div class="rep-header">
            <h5><i class="fa fa-calendar mr-2"></i>
                {{ \Carbon\Carbon::create($year, $mon)->format('F Y') }}
            </h5>
            <div style="display:flex;align-items:center;gap:10px;">
                <input type="month" id="monthPicker" class="month-input"
                       value="{{ $month }}" max="{{ today()->format('Y-m') }}">
                <button onclick="loadReport()" class="btn btn-sm"
                        style="background:rgba(255,255,255,.25);color:#fff;border:1px solid rgba(255,255,255,.4);border-radius:8px;">
                    <i class="fa fa-refresh"></i> Load
                </button>
            </div>
        </div>

        @if($staff->isEmpty())
            <div class="text-center text-muted py-5">No active staff found.</div>
        @else

        {{-- Legend --}}
        <div style="padding:12px 20px;display:flex;gap:14px;flex-wrap:wrap;font-size:12px;border-bottom:1px solid #f0f4ff;">
            <span><span class="att-dot dot-P">P</span> Present</span>
            <span><span class="att-dot dot-A">A</span> Absent</span>
            <span><span class="att-dot dot-H">H</span> Half Day</span>
            <span><span class="att-dot dot-L">L</span> Leave</span>
            <span><span class="att-dot dot-N">—</span> Not Marked</span>
        </div>

        <div class="table-responsive">
            <table class="rep-table">
                <thead>
                    <tr>
                        <th class="staff-name">Staff</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php $dayDate = \Carbon\Carbon::create($year, $mon, $d); @endphp
                            <th style="{{ $dayDate->isWeekend() ? 'background:#fff8e6;color:#f59e0b;' : '' }}">
                                {{ $d }}<br>
                                <span style="font-size:9px;font-weight:400;">{{ $dayDate->format('D') }}</span>
                            </th>
                        @endfor
                        <th style="background:#e8f3ff;color:#0a6ebd;">P</th>
                        <th style="background:#fff0f0;color:#ef4444;">A</th>
                        <th style="background:#fff8e6;color:#f59e0b;">H</th>
                        <th style="background:#f0f0ff;color:#7c3aed;">L</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $s)
                    @php
                        $sRecords = $records[$s->id] ?? collect();
                        $byDate   = $sRecords->keyBy(fn($r) => \Carbon\Carbon::parse($r->attendance_date)->day);
                        $cntP = $sRecords->where('status','present')->count();
                        $cntA = $sRecords->where('status','absent')->count();
                        $cntH = $sRecords->where('status','half_day')->count();
                        $cntL = $sRecords->where('status','leave')->count();
                    @endphp
                    <tr>
                        <td class="staff-name">
                            @php
                                $perDay    = $s->salary ? round($s->salary / $daysInMonth, 2) : 0;
                                $earnedSal = round(($cntP * $perDay) + ($cntH * $perDay * 0.5), 2);
                            @endphp
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#0a6ebd,#00b074);display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($s->name,0,1)) }}
                                </div>
                                <div>
                                    <a href="#" class="staff-detail-link"
                                       style="font-size:13px;font-weight:700;color:#0a6ebd;text-decoration:none;"
                                       data-id="{{ $s->id }}"
                                       data-name="{{ $s->name }}"
                                       data-role="{{ $s->role ?? '—' }}"
                                       data-phone="{{ $s->phone ?? '—' }}"
                                       data-email="{{ $s->email ?? '—' }}"
                                       data-salary="{{ $s->salary ? number_format($s->salary,2) : '—' }}"
                                       data-joining="{{ $s->joining_date ?? '—' }}"
                                       data-p="{{ $cntP }}"
                                       data-a="{{ $cntA }}"
                                       data-h="{{ $cntH }}"
                                       data-l="{{ $cntL }}"
                                       data-days="{{ $daysInMonth }}"
                                       data-perday="{{ $perDay }}"
                                       data-earned="{{ $earnedSal }}"
                                       data-month="{{ \Carbon\Carbon::create($year,$mon)->format('F Y') }}">
                                        {{ $s->name }}
                                    </a>
                                    <div style="font-size:10px;color:#888;">{{ $s->role ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $rec = $byDate[$d] ?? null;
                                $dayDate = \Carbon\Carbon::create($year, $mon, $d);
                                $isFuture = $dayDate->isFuture();
                                $dotMap = ['present'=>'P dot-P','absent'=>'A dot-A','half_day'=>'H dot-H','leave'=>'L dot-L'];
                            @endphp
                            <td style="{{ $dayDate->isWeekend() ? 'background:#fffdf5;' : '' }}">
                                @if($isFuture)
                                    <span style="color:#d0d0d0;font-size:11px;">—</span>
                                @elseif($rec)
                                    @php [$letter, $cls] = explode(' ', $dotMap[$rec->status], 2); @endphp
                                    <span class="att-dot {{ $cls }}" title="{{ ucfirst(str_replace('_',' ',$rec->status)) }}{{ $rec->check_in ? ' | In: '.$rec->check_in : '' }}{{ $rec->check_out ? ' | Out: '.$rec->check_out : '' }}{{ $rec->note ? ' | '.$rec->note : '' }}">
                                        {{ $letter }}
                                    </span>
                                @else
                                    <span class="att-dot dot-N">—</span>
                                @endif
                            </td>
                        @endfor
                        <td style="font-weight:700;color:#00b074;">{{ $cntP }}</td>
                        <td style="font-weight:700;color:#ef4444;">{{ $cntA }}</td>
                        <td style="font-weight:700;color:#f59e0b;">{{ $cntH }}</td>
                        <td style="font-weight:700;color:#7c3aed;">{{ $cntL }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary Cards --}}
        @php
            $totalP = 0; $totalA = 0; $totalH = 0; $totalL = 0;
            foreach($records as $recs) {
                $totalP += collect($recs)->where('status','present')->count();
                $totalA += collect($recs)->where('status','absent')->count();
                $totalH += collect($recs)->where('status','half_day')->count();
                $totalL += collect($recs)->where('status','leave')->count();
            }
        @endphp
        <div class="sum-cards">
            <div class="sum-card" style="background:#e6fff5;animation-delay:.1s;">
                <div class="num" style="color:#00b074;">{{ $totalP }}</div>
                <div class="lbl" style="color:#00b074;">Total Present</div>
            </div>
            <div class="sum-card" style="background:#fff0f0;animation-delay:.2s;">
                <div class="num" style="color:#ef4444;">{{ $totalA }}</div>
                <div class="lbl" style="color:#ef4444;">Total Absent</div>
            </div>
            <div class="sum-card" style="background:#fff8e6;animation-delay:.3s;">
                <div class="num" style="color:#f59e0b;">{{ $totalH }}</div>
                <div class="lbl" style="color:#f59e0b;">Half Days</div>
            </div>
            <div class="sum-card" style="background:#f0f0ff;animation-delay:.4s;">
                <div class="num" style="color:#7c3aed;">{{ $totalL }}</div>
                <div class="lbl" style="color:#7c3aed;">On Leave</div>
            </div>
            <div class="sum-card" style="background:#f0f7ff;animation-delay:.5s;">
                <div class="num" style="color:#0a6ebd;">{{ $staff->count() }}</div>
                <div class="lbl" style="color:#0a6ebd;">Total Staff</div>
            </div>
            <div class="sum-card" style="background:#f8fbff;animation-delay:.6s;">
                <div class="num" style="color:#1a1a2e;">{{ $daysInMonth }}</div>
                <div class="lbl" style="color:#555;">Working Days</div>
            </div>
        </div>

        @endif
    </div>

</div>
</div>

<script>
function loadReport() {
    var month = document.getElementById('monthPicker').value;
    window.location = '{{ route("doctor.staff.attendance.report") }}?month=' + month;
}

var interval = setInterval(function () {
    if (typeof $ === 'undefined') return;
    clearInterval(interval);

    $(document).on('click', '.staff-detail-link', function(e) {
        e.preventDefault();
        var d = $(this).data();

        var perDay  = parseFloat(d.perday) || 0;
        var earned  = parseFloat(d.earned) || 0;
        var salary  = d.salary !== '—' ? parseFloat(d.salary.replace(/,/g,'')) : 0;
        var deduct  = salary > 0 ? (salary - earned).toFixed(2) : 0;

        var formula = salary > 0
            ? '<div style="background:#f0f7ff;border-radius:10px;padding:14px 16px;margin-top:14px;font-size:12.5px;color:#444;">' +
              '<div style="font-weight:700;color:#0a6ebd;margin-bottom:8px;"><i class="fa fa-calculator"></i> Salary Calculation Formula</div>' +
              '<div style="margin-bottom:4px;">📅 Per Day Salary = Monthly Salary ÷ Total Days</div>' +
              '<div style="margin-bottom:4px;padding-left:16px;color:#0a6ebd;">= ₹' + salary.toLocaleString() + ' ÷ ' + d.days + ' = ₹' + perDay.toFixed(2) + '/day</div>' +
              '<div style="margin-bottom:4px;margin-top:8px;">💰 Earned = (Present × Per Day) + (Half Day × Per Day × 0.5)</div>' +
              '<div style="padding-left:16px;color:#00b074;">= (' + d.p + ' × ₹' + perDay.toFixed(2) + ') + (' + d.h + ' × ₹' + perDay.toFixed(2) + ' × 0.5)</div>' +
              '<div style="padding-left:16px;color:#00b074;margin-bottom:8px;">= ₹' + earned.toFixed(2) + '</div>' +
              '<div style="margin-bottom:4px;">❌ Deduction = Monthly Salary − Earned Salary</div>' +
              '<div style="padding-left:16px;color:#ef4444;">= ₹' + salary.toLocaleString() + ' − ₹' + earned.toFixed(2) + ' = ₹' + deduct + '</div>' +
              '</div>'
            : '<div style="background:#fff8e6;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:12px;color:#f59e0b;"><i class="fa fa-info-circle"></i> Salary not set for this staff member.</div>';

        var html =
            '<div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">' +
            '  <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#0a6ebd,#00b074);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:800;flex-shrink:0;">' + d.name.charAt(0).toUpperCase() + '</div>' +
            '  <div><div style="font-size:17px;font-weight:800;color:#1a1a2e;">' + d.name + '</div>' +
            '  <div style="font-size:12px;color:#888;">' + d.role + ' &nbsp;|&nbsp; Joined: ' + d.joining + '</div></div>' +
            '</div>' +

            '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">' +
            '  <div style="background:#f8fbff;border-radius:10px;padding:10px 14px;"><div style="font-size:11px;color:#888;font-weight:600;">PHONE</div><div style="font-size:13px;font-weight:700;">' + d.phone + '</div></div>' +
            '  <div style="background:#f8fbff;border-radius:10px;padding:10px 14px;"><div style="font-size:11px;color:#888;font-weight:600;">EMAIL</div><div style="font-size:13px;font-weight:700;word-break:break-all;">' + d.email + '</div></div>' +
            '  <div style="background:#f8fbff;border-radius:10px;padding:10px 14px;"><div style="font-size:11px;color:#888;font-weight:600;">MONTHLY SALARY</div><div style="font-size:15px;font-weight:800;color:#0a6ebd;">₹' + d.salary + '</div></div>' +
            '  <div style="background:#f8fbff;border-radius:10px;padding:10px 14px;"><div style="font-size:11px;color:#888;font-weight:600;">MONTH</div><div style="font-size:13px;font-weight:700;">' + d.month + '</div></div>' +
            '</div>' +

            '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:4px;">' +
            '  <div style="background:#e6fff5;border:1.5px solid #b3f0d8;border-radius:10px;padding:12px;text-align:center;"><div style="font-size:22px;font-weight:800;color:#00b074;">' + d.p + '</div><div style="font-size:11px;font-weight:700;color:#00b074;">P (Present)</div></div>' +
            '  <div style="background:#fff0f0;border:1.5px solid #fecaca;border-radius:10px;padding:12px;text-align:center;"><div style="font-size:22px;font-weight:800;color:#ef4444;">' + d.a + '</div><div style="font-size:11px;font-weight:700;color:#ef4444;">A (Absent)</div></div>' +
            '  <div style="background:#fff8e6;border:1.5px solid #fde68a;border-radius:10px;padding:12px;text-align:center;"><div style="font-size:22px;font-weight:800;color:#f59e0b;">' + d.h + '</div><div style="font-size:11px;font-weight:700;color:#f59e0b;">H (Half Day)</div></div>' +
            '  <div style="background:#f0f0ff;border:1.5px solid #ddd6fe;border-radius:10px;padding:12px;text-align:center;"><div style="font-size:22px;font-weight:800;color:#7c3aed;">' + d.l + '</div><div style="font-size:11px;font-weight:700;color:#7c3aed;">L (Leave)</div></div>' +
            '</div>' +

            (salary > 0 ?
            '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:10px;">' +
            '  <div style="background:linear-gradient(135deg,#e6fff5,#f0fff8);border:2px solid #b3f0d8;border-radius:12px;padding:14px;text-align:center;"><div style="font-size:11px;color:#00b074;font-weight:700;">EARNED SALARY</div><div style="font-size:22px;font-weight:800;color:#00b074;">₹' + earned.toFixed(2) + '</div></div>' +
            '  <div style="background:linear-gradient(135deg,#fff0f0,#fff5f5);border:2px solid #fecaca;border-radius:12px;padding:14px;text-align:center;"><div style="font-size:11px;color:#ef4444;font-weight:700;">DEDUCTION</div><div style="font-size:22px;font-weight:800;color:#ef4444;">₹' + deduct + '</div></div>' +
            '</div>' : '') +

            formula;

        $('#staffDetailBody').html(html);
        $('#staffDetailModal').modal('show');
    });
}, 100);
</script>

{{-- Staff Detail Modal --}}
<div class="modal fade" id="staffDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#0a6ebd,#00b074);border:none;">
                <h5 class="modal-title text-white" style="font-weight:700;">
                    <i class="fa fa-user-circle mr-2"></i> Staff Details & Salary
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:22px;" id="staffDetailBody"></div>
            <div class="modal-footer" style="border-top:1px solid #f0f4ff;">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
