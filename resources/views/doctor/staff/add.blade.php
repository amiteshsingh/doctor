@extends('doctor.layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h4 class="page-title">{{ isset($member->id) ? 'Edit' : 'Add' }} Staff</h4>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <form id="staff_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $member->id ?? '' }}">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $member->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="">-- Select Role --</option>
                                @foreach(['Nurse','Receptionist','Compounder','Lab Technician','Ward Boy','Cleaner','Security','Other'] as $r)
                                    <option value="{{ $r }}" {{ (isset($member->role) && $member->role == $r) ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $member->phone ?? '' }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $member->email ?? '' }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Salary (₹)</label>
                            <input type="number" name="salary" class="form-control" min="0" step="0.01" value="{{ $member->salary ?? '' }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" value="{{ $member->joining_date ?? '' }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $member->address ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ (!isset($member->status) || $member->status == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (isset($member->status) && $member->status == 0) ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div id="form_msg"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('doctor.staff.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var interval = setInterval(function () {
    if (typeof $ === 'undefined') return;
    clearInterval(interval);
    $('#staff_form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('button[type=submit]');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#form_msg').html('');
        $.ajax({
            url: '{{ route("doctor.staff.add") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html('Save');
                $('#form_msg').html('<div class="alert alert-' + (res.status == 200 ? 'success' : 'danger') + '">' + res.msg + '</div>');
                if (res.status == 200) setTimeout(function () { window.location = '{{ route("doctor.staff.index") }}'; }, 1000);
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('Save');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                $('#form_msg').html('<div class="alert alert-danger">' + msg + '</div>');
            }
        });
    });
}, 100);
</script>
@endsection
