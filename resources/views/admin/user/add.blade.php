@extends('admin.layout.app')

@section('content')

@php $isEdit = isset($user->id) && $user->id; @endphp

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $isEdit ? 'Edit' : 'Add' }} User</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">
                    <h4 class="card-title">{{ $isEdit ? 'Edit' : 'Add' }} User</h4>

                    <form id="user_form" method="POST" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $isEdit ? $user->id : '' }}">

                        <div id="form_error" class="alert alert-danger d-none mb-3"></div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Full Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name"
                                       value="{{ $isEdit ? $user->name : '' }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" name="email"
                                       value="{{ $isEdit ? $user->email : '' }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">
                                Password {{ $isEdit ? '(leave blank to keep)' : '' }}
                                @if(!$isEdit)<span class="text-danger">*</span>@endif
                            </label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" name="password"
                                       {{ $isEdit ? '' : 'required' }}>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Phone</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="phone_no"
                                       value="{{ $isEdit ? $user->phone_no : '' }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Gender</label>
                            <div class="col-md-8">
                                <select class="form-control select" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male"   {{ ($isEdit && $user->gender == 'Male')   ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ ($isEdit && $user->gender == 'Female') ? 'selected' : '' }}>Female</option>
                                    <option value="Other"  {{ ($isEdit && $user->gender == 'Other')  ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Address</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="address" rows="2">{{ $isEdit ? $user->address : '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary" id="save_user">
                                    <i class="fa fa-save"></i> {{ $isEdit ? 'Update' : 'Save' }}
                                </button>
                                <a href="{{ route('admin.user') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Wait for jQuery to be available
    var interval = setInterval(function () {
        if (typeof $ === 'undefined') return;
        clearInterval(interval);

        $('#user_form').off('submit').on('submit', function (e) {
            e.preventDefault();

            var btn    = $('#save_user');
            var label  = '{{ $isEdit ? 'Update' : 'Save' }}';
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $('#form_error').addClass('d-none').text('');

            $.ajax({
                url: '{{ route("admin.user.add") }}',
                type: 'POST',
                data: $('#user_form').serialize(),
                dataType: 'json',
                success: function (res) {
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
                    if (res.status === 200) {
                        $.jGrowl(res.msg, { theme: 'success-theme', life: 2500 });
                        setTimeout(function () {
                            window.location.href = '{{ route("admin.user") }}';
                        }, 1500);
                    } else {
                        $('#form_error').removeClass('d-none').text(res.msg);
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
                    var msg = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.msg) msg = xhr.responseJSON.msg;
                    $('#form_error').removeClass('d-none').text(msg);
                }
            });
        });
    }, 100);
});
</script>
@endsection
