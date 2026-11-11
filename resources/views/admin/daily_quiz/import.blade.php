@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="row mb-3">
            <div class="col-sm-7">
                <h4 class="page-title">
                    <i class="fa fa-file-excel-o text-success"></i>
                    Excel se Quiz Import Karo
                </h4>
                <p class="text-muted mb-0">Excel file upload karke ek saath bahut saare questions add karo</p>
            </div>
            <div class="col-sm-5 text-right">
                <a href="{{ route('admin.daily-quiz.template') }}" class="btn btn-success btn-rounded mr-2">
                    <i class="fa fa-download"></i> Sample Template Download
                </a>
                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {!! session('success') !!}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
        @endif

        @if(session('import_errors'))
        <div class="alert alert-warning">
            <strong><i class="fa fa-exclamation-triangle"></i> Row Errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">

            {{-- LEFT: Import Form --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 style="color:#1A237E;" class="mb-4">
                            <i class="fa fa-upload"></i> File Upload & Quiz Schedule
                        </h5>

                        <form method="POST" action="{{ route('admin.daily-quiz.import') }}" enctype="multipart/form-data" id="importForm">
                            @csrf

                            {{-- Schedule --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Batch Title <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="e.g. Week 3 Health Quiz"
                                            value="{{ old('title') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quiz Starts At <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="starts_at" class="form-control"
                                            required value="{{ old('starts_at') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quiz Ends At <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="ends_at" class="form-control"
                                            required value="{{ old('ends_at') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- File Drop Zone --}}
                            <div class="form-group">
                                <label>Excel File Upload <span class="text-danger">*</span></label>
                                <div id="dropZone" onclick="document.getElementById('excel_file').click()"
                                    style="border: 2px dashed #1A237E; border-radius: 10px; padding: 40px 20px;
                                           text-align: center; cursor: pointer; background: #F3F4FF;
                                           transition: background 0.2s;">
                                    <i class="fa fa-cloud-upload fa-3x" style="color:#1A237E;"></i>
                                    <p class="mt-2 mb-0" style="font-size:16px; color:#1A237E; font-weight:600;">
                                        Click ya drag karke file yahan drop karo
                                    </p>
                                    <p class="text-muted mb-0"><small>Supported: .xlsx, .xls, .csv — Max 5MB</small></p>
                                    <div id="fileNameDisplay" class="mt-2" style="display:none;">
                                        <span class="badge badge-success" style="font-size:13px;" id="fileNameText"></span>
                                    </div>
                                </div>
                                <input type="file" id="excel_file" name="excel_file"
                                    accept=".xlsx,.xls,.csv" class="d-none" required>
                                @error('excel_file')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
                                <i class="fa fa-upload"></i> Import Karo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Instructions + Column Guide --}}
            <div class="col-md-5">

                {{-- Download CTA --}}
                <div class="card mb-3" style="background: linear-gradient(135deg, #1A237E, #3949AB); color:#fff; border:none;">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-file-excel-o fa-3x mb-2"></i>
                        <h5 class="mb-1">Sample Template</h5>
                        <p class="mb-3" style="font-size:13px; opacity:0.85;">
                            Pehle yeh template download karo, fill karo, phir upload karo
                        </p>
                        <a href="{{ route('admin.daily-quiz.template') }}"
                            class="btn btn-warning btn-sm px-4">
                            <i class="fa fa-download"></i> Download Template (.xlsx)
                        </a>
                    </div>
                </div>

                {{-- Column Guide --}}
                <div class="card">
                    <div class="card-body">
                        <h6 style="color:#1A237E;" class="mb-3">
                            <i class="fa fa-info-circle"></i> Excel Columns Guide
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead style="background:#E8EAF6;">
                                    <tr>
                                        <th>Column</th>
                                        <th>Field</th>
                                        <th>Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong>A</strong></td><td>Question</td><td><span class="badge badge-danger">Yes</span></td></tr>
                                    <tr><td><strong>B</strong></td><td>Option A</td><td><span class="badge badge-danger">Yes</span></td></tr>
                                    <tr><td><strong>C</strong></td><td>Option B</td><td><span class="badge badge-danger">Yes</span></td></tr>
                                    <tr><td><strong>D</strong></td><td>Option C</td><td><span class="badge badge-danger">Yes</span></td></tr>
                                    <tr><td><strong>E</strong></td><td>Option D</td><td><span class="badge badge-danger">Yes</span></td></tr>
                                    <tr>
                                        <td><strong>F</strong></td>
                                        <td>Correct Answer</td>
                                        <td><span class="badge badge-danger">Yes</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>G</strong></td>
                                        <td>Category</td>
                                        <td><span class="badge badge-secondary">Optional</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>H</strong></td>
                                        <td>Time Limit (sec)</td>
                                        <td><span class="badge badge-secondary">Optional</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>I</strong></td>
                                        <td>Explanation</td>
                                        <td><span class="badge badge-secondary">Optional</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 p-2" style="background:#FFF8E1; border-radius:6px; border-left:3px solid #FFC107;">
                            <small>
                                <strong>⚠️ Dhyan Do:</strong><br>
                                • <strong>Row 1</strong> header hai — data Row 2 se shuru karo<br>
                                • Correct Answer sirf <strong>A, B, C ya D</strong> likhna hai<br>
                                • Category: General, Health, Science, History, Geography, Sports, Technology, Math<br>
                                • Time Limit: 10 to 300 seconds (default: 30)<br>
                                • Khali rows ignore ho jayenge
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
// File input change
document.getElementById('excel_file').addEventListener('change', function () {
    if (this.files.length > 0) {
        document.getElementById('fileNameText').textContent = '📄 ' + this.files[0].name;
        document.getElementById('fileNameDisplay').style.display = 'block';
        document.getElementById('dropZone').style.background = '#E8F5E9';
        document.getElementById('dropZone').style.borderColor = '#4CAF50';
    }
});

// Drag and drop
const dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', function (e) {
    e.preventDefault();
    this.style.background = '#E3F2FD';
});

dropZone.addEventListener('dragleave', function () {
    this.style.background = '#F3F4FF';
});

dropZone.addEventListener('drop', function (e) {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const input = document.getElementById('excel_file');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    }
});

// Submit loading state
document.getElementById('importForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Import ho raha hai...';
    btn.disabled = true;
});
</script>

@endsection
