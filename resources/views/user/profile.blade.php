@extends('user.layouts.app')
@section('title', 'RogiSewa - My Profile')

@section('user_content')
<div class="card shadow border-0 rounded-4 p-4 bg-white">
    <h5 class="fw-bold border-bottom pb-2 mb-4"><i class="fa fa-user me-2 text-primary"></i>My Profile</h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select</option>
                    @foreach(['Male','Female','Other'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $user->dob) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control @error('profile_pic') is-invalid @enderror" accept="image/*">
            @error('profile_pic') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary fw-bold px-4">Update Profile</button>
    </form>
</div>
@endsection
