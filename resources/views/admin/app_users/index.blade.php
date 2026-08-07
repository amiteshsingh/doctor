@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
<div class="content">

    <div class="row mb-3">
        <div class="col-sm-8">
            <h4 class="page-title"><i class="fa fa-users" style="color:#0a6ebd;margin-right:8px;"></i> App Users</h4>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div style="background:linear-gradient(135deg,#0a6ebd,#00b074);border-radius:14px;padding:18px 20px;color:#fff;text-align:center;">
                <div style="font-size:28px;font-weight:800;">{{ $total }}</div>
                <div style="font-size:13px;opacity:.85;">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div style="background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:14px;padding:18px 20px;color:#fff;text-align:center;">
                <div style="font-size:28px;font-weight:800;">{{ $withToken }}</div>
                <div style="font-size:13px;opacity:.85;">With FCM Token</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div style="background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:14px;padding:18px 20px;color:#fff;text-align:center;">
                <div style="font-size:28px;font-weight:800;">{{ $activeToday }}</div>
                <div style="font-size:13px;opacity:.85;">Active Today</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#f0fff8;border:1.5px solid #b3f0d8;border-radius:10px;padding:12px 18px;margin-bottom:16px;color:#00b074;font-weight:600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Search --}}
    <div style="background:#fff;border-radius:14px;padding:16px 20px;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:16px;">
        <form method="GET" action="{{ route('admin.app-users.index') }}" class="d-flex gap-2" style="gap:10px;">
            <input type="text" name="search" value="{{ $search }}" class="form-control"
                placeholder="Search by name, email, phone..." style="max-width:360px;border-radius:10px;">
            <button type="submit" class="btn btn-primary" style="border-radius:10px;padding:8px 20px;">
                <i class="fa fa-search"></i> Search
            </button>
            @if($search)
            <a href="{{ route('admin.app-users.index') }}" class="btn btn-secondary" style="border-radius:10px;padding:8px 16px;">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div style="background:#fff;border-radius:16px;padding:0;box-shadow:0 2px 16px rgba(0,0,0,.07);overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="font-size:13px;">
                <thead style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;">
                    <tr>
                        <th style="padding:12px 16px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>FCM</th>
                        <th>Last Seen</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                    <tr>
                        <td style="padding:10px 16px;color:#888;">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700;color:#134e4a;">{{ $u->name }}</div>
                        </td>
                        <td style="color:#555;">{{ $u->email ?? '—' }}</td>
                        <td style="color:#555;">{{ $u->phone_no ?? '—' }}</td>
                        <td>{{ $u->gender ?? '—' }}</td>
                        <td>
                            @if($u->fcm_token)
                                <span style="background:#f0fff8;color:#00b074;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;">✓ Yes</span>
                            @else
                                <span style="background:#fef2f2;color:#ef4444;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;">✗ No</span>
                            @endif
                        </td>
                        <td style="color:#888;font-size:12px;">
                            {{ $u->last_seen ? \Carbon\Carbon::parse($u->last_seen)->diffForHumans() : '—' }}
                        </td>
                        <td style="color:#888;font-size:12px;">
                            {{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.app-users.destroy', $u->id) }}"
                                onsubmit="return confirm('Delete this user?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;padding:4px 10px;font-size:12px;cursor:pointer;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fa fa-users" style="font-size:32px;margin-bottom:10px;display:block;"></i>
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
</div>
@endsection
