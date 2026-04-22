@extends('doctor.layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-8"><h4 class="page-title">Medicine Management</h4></div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('doctor.medicine.add') }}" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add Medicine</a>
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
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Stock</th>
                                <th>Price (₹)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicines as $i => $med)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $med->name }}</strong></td>
                                <td>{{ $med->category ?? '—' }}</td>
                                <td>{{ $med->unit ?? '—' }}</td>
                                <td>{{ $med->stock }}</td>
                                <td>{{ number_format($med->price, 2) }}</td>
                                <td>
                                    @if($med->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('doctor.medicine.add') }}?id={{ $med->id }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('doctor.medicine.delete', $med->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this medicine?')"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No medicines added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
