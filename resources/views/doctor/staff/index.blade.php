@extends('doctor.layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-8"><h4 class="page-title">Staff Management</h4></div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('doctor.staff.attendance') }}" class="btn btn-success btn-rounded mr-1"><i class="fa fa-calendar-check-o"></i> Attendance</a>
                <a href="{{ route('doctor.staff.add') }}" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add Staff</a>
            </div>
        </div>

        @if(session('msg'))
            <div class="alert alert-success">{{ session('msg') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Salary (₹)</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $s->name }}</strong></td>
                                <td>{{ $s->role ?? '—' }}</td>
                                <td>{{ $s->phone ?? '—' }}</td>
                                <td>{{ $s->salary ? number_format($s->salary, 2) : '—' }}</td>
                                <td>{{ $s->joining_date ?? '—' }}</td>
                                <td>
                                    @if($s->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('doctor.staff.add') }}?id={{ $s->id }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('doctor.staff.delete', $s->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff member?')"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No staff added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
