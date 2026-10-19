@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title">Database Backup</h4>
                    <p class="text-muted mb-0">Download a SQL backup of the configured database.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-4">
                    <dt class="col-sm-3">Database</dt>
                    <dd class="col-sm-9">{{ $databaseName }}</dd>
                    <dt class="col-sm-3">Driver</dt>
                    <dd class="col-sm-9">{{ strtoupper($driver) }}</dd>
                </dl>

                @if($supported)
                    <p class="text-muted">
                        The SQL file includes every table's structure and data. Store the downloaded backup securely.
                    </p>
                    <a href="{{ route('admin.database-backup.download') }}" class="btn btn-primary">
                        <i class="fa fa-download mr-1"></i> Download Database Backup
                    </a>
                @else
                    <div class="alert alert-warning mb-0">
                        Backup downloads are currently available for MySQL and MariaDB databases only.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
