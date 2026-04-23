@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Breadcrumb --}}
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">User Details</h4>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('admin.user') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.user.add') }}?id={{ $user->id }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil"></i> Edit
                </a>
            </div>
        </div>

        <div class="row mt-3">

            {{-- Left: Profile Card --}}
            <div class="col-md-4">
                <div class="card-box text-center" style="padding: 30px 20px;">
                    @php
                        $pic = $user->profile_image
                            ? asset('storage/upload/profile_images/' . $user->profile_image)
                            : asset('admin/assets/img/user.jpg');
                    @endphp
                    <img src="{{ $pic }}" alt="{{ $user->name }}"
                         class="rounded-circle"
                         style="width:100px;height:100px;object-fit:cover;border:3px solid #009efb;">

                    <h4 class="mt-3 mb-1" style="font-weight:700;">{{ $user->name }}</h4>
                    <p class="text-muted mb-1"><i class="fa fa-envelope-o"></i> {{ $user->email }}</p>
                    <p class="text-muted mb-2"><i class="fa fa-phone"></i> {{ $user->phone_no ?: '—' }}</p>

                    @php
                        $role = DB::table('user_roles')->where('user_id', $user->id)->value('role');
                    @endphp
                    <span class="badge badge-pill"
                          style="background:{{ $role === 'admin' ? '#ff5b5b' : ($role === 'doctor' ? '#009efb' : '#00b074') }};
                                 color:#fff; font-size:12px; padding:5px 14px;">
                        {{ ucfirst($role ?? 'user') }}
                    </span>

                    <hr>

                    <div class="text-left" style="font-size:13px;">
                        <p><strong>Gender:</strong> {{ $user->gender ?: '—' }}</p>
                        <p><strong>DOB:</strong> {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '—' }}</p>
                        <p><strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}</p>
                        <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Right: Details --}}
            <div class="col-md-8">

                {{-- Personal Info --}}
                <div class="card-box mb-3">
                    <h5 class="card-title" style="border-bottom:2px solid #009efb; padding-bottom:8px;">
                        <i class="fa fa-user text-primary"></i> Personal Information
                    </h5>
                    <table class="table table-borderless mb-0" style="font-size:14px;">
                        <tbody>
                            <tr>
                                <td style="width:35%;color:#888;font-weight:600;">Full Name</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Phone</td>
                                <td>{{ $user->phone_no ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Gender</td>
                                <td>{{ $user->gender ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Date of Birth</td>
                                <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '—' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Address</td>
                                <td>{{ $user->address ?: '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Account Info --}}
                <div class="card-box">
                    <h5 class="card-title" style="border-bottom:2px solid #00b074; padding-bottom:8px;">
                        <i class="fa fa-info-circle text-success"></i> Account Information
                    </h5>
                    <table class="table table-borderless mb-0" style="font-size:14px;">
                        <tbody>
                            <tr>
                                <td style="width:35%;color:#888;font-weight:600;">User ID</td>
                                <td>#{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Role</td>
                                <td>
                                    <span class="badge badge-pill"
                                          style="background:{{ $role === 'admin' ? '#ff5b5b' : ($role === 'doctor' ? '#009efb' : '#00b074') }};
                                                 color:#fff; padding:4px 12px;">
                                        {{ ucfirst($role ?? 'user') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Email Verified</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="text-success"><i class="fa fa-check-circle"></i>
                                            {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-danger"><i class="fa fa-times-circle"></i> Not Verified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Registered On</td>
                                <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="color:#888;font-weight:600;">Last Updated</td>
                                <td>{{ $user->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
