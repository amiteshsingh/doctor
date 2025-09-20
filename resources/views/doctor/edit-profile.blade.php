@extends('doctor.layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Edit Profile</h4>
            </div>
        </div>

        {{-- Success & Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.update-profile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Basic Info --}}
            <div class="card-box">
                <h3 class="card-title">Basic Informations</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-img-wrap">

                            @php
                                $Image = isset( $user->profile_image) && file_exists(public_path('storage/upload/profile_images/'. $user->profile_image))
                                                ? asset('storage/upload/profile_images/'. $user->profile_image)
                                                : asset('storage/upload/profile_images/user.png'); // default image path
                            @endphp

                            <img class="inline-block" src="{{ $Image }}" alt="user">
                            <div class="fileupload btn">
                                <span class="btn-text">edit</span>
                                <input class="upload" type="file" name="profile_image">
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-focus">
                                        <label class="focus-label">Name</label>
                                        <input type="text" name="name" class="form-control floating" value="{{ old('name', $user->name) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-focus">
                                        <label class="focus-label">Birth Date</label>
                                        <input type="date" name="dob" class="form-control floating" value="{{ old('dob', $user->dob) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-focus select-focus">
                                        <label class="focus-label">Gender</label>
                                        <select name="gender" class="select form-control floating">
                                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="card-box">
                <h3 class="card-title">Contact Informations</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">Address</label>
                            <input type="text" name="address" class="form-control floating" value="{{ old('address', $user->address) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">State</label>
                            <input type="text" name="state" class="form-control floating" value="{{ old('state', $user->state) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Country</label>
                            <input type="text" name="country" class="form-control floating" value="{{ old('country', $user->country) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Pin Code</label>
                            <input type="text" name="pin_code" class="form-control floating" value="{{ old('pin_code', $user->pin_code) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Phone Number</label>
                            <input type="text" name="phone_no" class="form-control floating" value="{{ old('phone_no', $user->phone_no) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password Change --}}
            <div class="card-box">
                <h3 class="card-title">Change Password</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">New Password</label>
                            <input type="password" name="password" class="form-control floating">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control floating">
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center m-t-20">
                <button class="btn btn-primary submit-btn" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
