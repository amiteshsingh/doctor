@extends('doctor.layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('doctor.update-profile') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $user->dob) }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">Select Gender</option>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>State</label>
            <input type="text" name="state" value="{{ old('state', $user->state) }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Country</label>
            <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Pin Code</label>
            <input type="text" name="pin_code" value="{{ old('pin_code', $user->pin_code) }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}" class="form-control">
        </div>

        <hr>

        <div class="form-group mb-3">
            <label>New Password (leave blank if not changing)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
