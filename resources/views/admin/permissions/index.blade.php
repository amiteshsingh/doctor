@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Admin Module Permissions</h4>
                <p class="text-muted mb-0">Access is required for every module; View and Delete also require Access.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @if($admins->isEmpty())
            <div class="alert alert-info">No other users with the admin role are available.</div>
        @else
            <form method="GET" action="{{ route('admin.permissions.index') }}" class="form-inline mb-3">
                <label for="admin_id" class="mr-2">Select admin</label>
                <select name="admin_id" id="admin_id" class="form-control mr-2" onchange="this.form.submit()">
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" @selected($selectedAdmin && (int) $selectedAdmin->id === (int) $admin->id)>
                            {{ $admin->name }} ({{ $admin->email }})
                        </option>
                    @endforeach
                </select>
            </form>

            <form method="POST" action="{{ route('admin.permissions.update', $selectedAdmin->id) }}">
                @csrf
                @method('PUT')
                <div class="table-responsive bg-white rounded shadow-sm">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th class="text-center">Access (Create/Edit)</th>
                                <th class="text-center">View Data</th>
                                <th class="text-center">Delete Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Support\AdminModules::MODULES as $module => $details)
                                @php $permission = $permissions[$module] ?? null; @endphp
                                <tr>
                                    <td>{{ $details['label'] }}</td>
                                    @foreach(['access_module', 'view_data', 'delete_data'] as $field)
                                        <td class="text-center">
                                            <input
                                                type="checkbox"
                                                name="permissions[{{ $module }}][{{ $field }}]"
                                                value="1"
                                                @checked($permission && $permission->{$field})
                                                aria-label="{{ $details['label'] }} {{ str_replace('_', ' ', $field) }}"
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
            </form>
        @endif
    </div>
</div>
@endsection
