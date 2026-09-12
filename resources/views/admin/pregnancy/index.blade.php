@extends('admin.layout.app')

@section('content')
<style>
.preg-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.07); overflow:hidden; }
.preg-head { background:linear-gradient(135deg,#f093fb,#f5576c); padding:16px 22px; color:#fff; display:flex; align-items:center; justify-content:space-between; }
.preg-head h5 { margin:0; font-weight:800; font-size:16px; }
.badge-soon  { background:#fff3cd; color:#856404; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-today { background:#d1e7dd; color:#0a3622; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-past  { background:#f8d7da; color:#842029; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-ok    { background:#e2e8f0; color:#334155; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.progress-bar-preg { height:8px; border-radius:4px; background:linear-gradient(90deg,#f093fb,#f5576c); }
</style>

<div class="page-wrapper">
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="font-weight:800;color:#1a1a2e;">🤰 Pregnancy Tracker</h4>
            <small class="text-muted">Upcoming deliveries — gift bhejne ke liye</small>
        </div>
        <span style="background:#f0f4ff;padding:6px 16px;border-radius:8px;font-size:13px;color:#555;">
            Total: <strong>{{ $pregnancies->count() }}</strong>
        </span>
    </div>

    {{-- Filter tabs --}}
    <div class="mb-3 d-flex gap-2 flex-wrap" style="gap:8px;">
        <button onclick="filterTable('all')"    class="btn btn-sm btn-primary"   id="tab-all">All</button>
        <button onclick="filterTable('soon')"   class="btn btn-sm btn-warning"   id="tab-soon">⚠️ Within 30 Days</button>
        <button onclick="filterTable('past')"   class="btn btn-sm btn-danger"    id="tab-past">Past EDD</button>
    </div>

    <div class="preg-card">
        <div class="preg-head">
            <h5>🎁 Delivery List</h5>
            <input type="text" id="searchBox" onkeyup="searchTable()" placeholder="Search name / phone..." 
                style="border:none;border-radius:8px;padding:5px 12px;font-size:13px;outline:none;width:220px;">
        </div>
        <div class="table-responsive">
            <table class="table mb-0" id="pregTable">
                <thead style="background:#f8f9ff;">
                    <tr style="font-size:12px;color:#555;font-weight:700;">
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>LMP Date</th>
                        <th>Due Date (EDD)</th>
                        <th>Weeks Pregnant</th>
                        <th>Days Left</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pregnancies as $i => $p)
                    @php
                        $daysLeft = $p->days_left;
                        $progress = min(100, round(($p->weeks_gone / 40) * 100));
                        if ($p->is_past) {
                            $badgeClass = 'badge-past'; $badgeText = 'Delivered?';
                        } elseif ($daysLeft <= 7) {
                            $badgeClass = 'badge-today'; $badgeText = 'Very Soon!';
                        } elseif ($daysLeft <= 30) {
                            $badgeClass = 'badge-soon'; $badgeText = 'Coming Soon';
                        } else {
                            $badgeClass = 'badge-ok'; $badgeText = $daysLeft.' days left';
                        }
                        $filterClass = $p->is_past ? 'row-past' : ($daysLeft <= 30 ? 'row-soon' : 'row-ok');
                    @endphp
                    <tr class="preg-row {{ $filterClass }}" style="font-size:13px;vertical-align:middle;">
                        <td style="color:#aaa;">{{ $i+1 }}</td>
                        <td>
                            <div style="font-weight:700;color:#1a1a2e;">{{ $p->name }}</div>
                            <div style="font-size:11px;color:#888;">{{ $p->email }}</div>
                        </td>
                        <td>{{ $p->phone_no ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->lmp_date)->format('d M Y') }}</td>
                        <td style="font-weight:700;color:#f5576c;">{{ \Carbon\Carbon::parse($p->edd)->format('d M Y') }}</td>
                        <td>
                            <div style="font-size:12px;margin-bottom:3px;">{{ $p->weeks_gone }} / 40 weeks</div>
                            <div style="background:#f0f4ff;border-radius:4px;height:8px;overflow:hidden;">
                                <div class="progress-bar-preg" style="width:{{ $progress }}%;"></div>
                            </div>
                        </td>
                        <td style="font-weight:700;">
                            @if($p->is_past)
                                <span style="color:#ef4444;">{{ abs((int)$daysLeft) }} days ago</span>
                            @else
                                {{ (int)$daysLeft }} days
                            @endif
                        </td>
                        <td><span class="{{ $badgeClass }}">{{ $badgeText }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Koi pregnancy record nahi mila.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<script>
function filterTable(type) {
    document.querySelectorAll('.preg-row').forEach(function(row) {
        if (type === 'all') { row.style.display = ''; return; }
        if (type === 'soon') row.style.display = row.classList.contains('row-soon') ? '' : 'none';
        if (type === 'past') row.style.display = row.classList.contains('row-past') ? '' : 'none';
    });
}
function searchTable() {
    var q = document.getElementById('searchBox').value.toLowerCase();
    document.querySelectorAll('.preg-row').forEach(function(row) {
        row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endsection
