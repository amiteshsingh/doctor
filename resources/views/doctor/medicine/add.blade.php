@extends('doctor.layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h4 class="page-title">{{ isset($medicine->id) ? 'Edit' : 'Add' }} Medicine</h4>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <form id="medicine_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $medicine->id ?? '' }}">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Medicine Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $medicine->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Antibiotic, Painkiller" value="{{ $medicine->category ?? '' }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Unit</label>
                            <select name="unit" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Powder','Sachet'] as $u)
                                    <option value="{{ $u }}" {{ (isset($medicine->unit) && $medicine->unit == $u) ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Stock (Qty)</label>
                            <input type="number" name="stock" class="form-control" min="0" value="{{ $medicine->stock ?? 0 }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Price (₹)</label>
                            <input type="number" name="price" class="form-control" min="0" step="0.01" value="{{ $medicine->price ?? 0 }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $medicine->description ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ (!isset($medicine->status) || $medicine->status == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (isset($medicine->status) && $medicine->status == 0) ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div id="form_msg"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('doctor.medicine.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var interval = setInterval(function () {
    if (typeof $ === 'undefined') return;
    clearInterval(interval);
    $('#medicine_form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('button[type=submit]');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#form_msg').html('');
        $.ajax({
            url: '{{ route("doctor.medicine.add") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html('Save');
                $('#form_msg').html('<div class="alert alert-' + (res.status == 200 ? 'success' : 'danger') + '">' + res.msg + '</div>');
                if (res.status == 200) setTimeout(function () { window.location = '{{ route("doctor.medicine.index") }}'; }, 1000);
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
