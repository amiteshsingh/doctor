@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
<div class="content">

    <div class="row mb-3">
        <div class="col-sm-8">
            <h4 class="page-title"><i class="fa fa-bell" style="color:#0a6ebd;margin-right:8px;"></i> Broadcast Notification</h4>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div style="background:linear-gradient(135deg,#0a6ebd,#00b074);border-radius:14px;padding:18px 20px;color:#fff;text-align:center;">
                <div style="font-size:28px;font-weight:800;">{{ $totalUsers }}</div>
                <div style="font-size:13px;opacity:.85;">Total App Users</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div style="background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:14px;padding:18px 20px;color:#fff;text-align:center;">
                <div style="font-size:28px;font-weight:800;">{{ $usersWithToken }}</div>
                <div style="font-size:13px;opacity:.85;">Users with FCM Token</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#f0fff8;border:1.5px solid #b3f0d8;border-radius:10px;padding:12px 18px;margin-bottom:16px;color:#00b074;font-weight:600;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div style="background:#fff;border-radius:16px;padding:28px;box-shadow:0 2px 16px rgba(0,0,0,.07);">
        <form method="POST" action="{{ route('admin.notification.send') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label class="col-lg-3 col-form-label font-weight-bold">Send To</label>
                <div class="col-lg-9">
                    <select name="target" class="form-control" onchange="toggleUserSelect(this.value)">
                        <option value="all">All Users ({{ $usersWithToken }} users)</option>
                        <option value="specific">Specific User</option>
                    </select>
                </div>
            </div>

            <div class="form-group row" id="specific_user_row" style="display:none;">
                <label class="col-lg-3 col-form-label font-weight-bold">Select User</label>
                <div class="col-lg-9">
                    <select name="user_id" class="form-control">
                        <option value="">-- Select User --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label font-weight-bold">Title <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" name="title" class="form-control" placeholder="Notification title..." required value="{{ old('title') }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label font-weight-bold">Message <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <textarea name="message" class="form-control" rows="4" placeholder="Notification message... (Hindi/English dono supported)" required>{{ old('message') }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label font-weight-bold">Image <small class="text-muted">(optional)</small></label>
                <div class="col-lg-9">
                    <div id="img-dropzone" onclick="document.getElementById('notif_image').click()"
                         style="border:2px dashed #0a6ebd;border-radius:12px;padding:24px;text-align:center;cursor:pointer;background:#f0f7ff;transition:background .2s;"
                         ondragover="event.preventDefault();this.style.background='#dbeafe'"
                         ondragleave="this.style.background='#f0f7ff'"
                         ondrop="handleImgDrop(event)">
                        <i class="fa fa-image" style="font-size:32px;color:#0a6ebd;margin-bottom:8px;"></i>
                        <div style="font-size:13px;font-weight:600;color:#555;">Click or drag image here</div>
                        <div style="font-size:11px;color:#aaa;margin-top:4px;">JPG, PNG supported</div>
                        <input type="file" name="image" id="notif_image" accept="image/jpeg,image/png" style="display:none;" onchange="previewNotifImg(this)">
                    </div>
                    <div id="img-preview" style="margin-top:12px;display:none;">
                        <img id="img-preview-src" style="max-height:120px;border-radius:10px;border:2px solid #e2e8f0;">
                        <button type="button" onclick="removeImg()" style="display:block;margin-top:6px;background:#fef2f2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;padding:4px 12px;font-size:12px;cursor:pointer;">Remove Image</button>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-9 offset-lg-3">
                    <button type="submit" class="btn"
                        style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;border:none;border-radius:10px;padding:10px 28px;font-weight:700;"
                        onclick="this.disabled=true; this.innerHTML='<i class=\'fa fa-spinner fa-spin\'></i> Sending...'; this.form.submit();">
                        <i class="fa fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Recent Logs --}}
    @if($logs->count())
    <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 16px rgba(0,0,0,.07);margin-top:24px;">
        <h5 style="font-weight:700;margin-bottom:16px;"><i class="fa fa-history" style="color:#0a6ebd;"></i> Recent Broadcasts</h5>
        <div class="table-responsive">
            <table class="table table-bordered" style="font-size:13px;">
                <thead style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;">
                    <tr><th>#</th><th>Title</th><th>Message</th><th>Sent To</th><th>Sent</th><th>Failed</th><th>Time</th></tr>
                </thead>
                <tbody>
                    @foreach($logs as $i => $log)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><strong>{{ $log->title }}</strong></td>
                        <td>{{ Str::limit($log->message, 60) }}</td>
                        <td>
                            @if($log->target === 'all')
                                <span style="background:#e8f3ff;color:#0a6ebd;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;">All Users</span>
                            @else
                                <span style="background:#f0fff8;color:#00b074;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;">Specific</span>
                            @endif
                        </td>
                        <td><span style="color:#00b074;font-weight:700;">{{ $log->sent_count }}</span></td>
                        <td><span style="color:#ef4444;font-weight:700;">{{ $log->failed_count }}</span></td>
                        <td style="color:#888;">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
</div>

<script>
function toggleUserSelect(val) {
    document.getElementById('specific_user_row').style.display = val === 'specific' ? 'flex' : 'none';
}
function previewNotifImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('img-preview-src').src = e.target.result;
            document.getElementById('img-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function handleImgDrop(e) {
    e.preventDefault();
    document.getElementById('img-dropzone').style.background = '#f0f7ff';
    var file = e.dataTransfer.files[0];
    if (!file) return;
    var dt = new DataTransfer();
    dt.items.add(file);
    var input = document.getElementById('notif_image');
    input.files = dt.files;
    previewNotifImg(input);
}
function removeImg() {
    document.getElementById('notif_image').value = '';
    document.getElementById('img-preview').style.display = 'none';
    document.getElementById('img-preview-src').src = '';
}
</script>
@endsection
