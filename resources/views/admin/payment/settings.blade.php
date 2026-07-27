@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="font-weight:800;color:#1a1a2e;">💳 Payment Settings</h4>
            <small class="text-muted">Razorpay integration aur platform fee manage karein</small>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card" style="border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <div class="card-header" style="background:linear-gradient(135deg,#0f0c29,#302b63);border-radius:16px 16px 0 0;padding:16px 24px;">
                    <h6 class="mb-0 text-white" style="font-weight:700;"><i class="fa fa-credit-card mr-2"></i>Razorpay Configuration</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.payment.update') }}">
                        @csrf

                        {{-- Toggle Switch --}}
                        <div class="d-flex align-items-center justify-content-between p-3 mb-4"
                             style="background:{{ $settings['razorpay_enabled']=='1' ? '#e8f5e9' : '#fff3e0' }};border-radius:12px;border:2px solid {{ $settings['razorpay_enabled']=='1' ? '#a5d6a7' : '#ffcc80' }};">
                            <div>
                                <div style="font-weight:700;font-size:15px;color:#1a1a2e;">
                                    💳 Payment Gateway
                                </div>
                                <div style="font-size:12px;color:#666;margin-top:2px;">
                                    Enable karne par user se ₹{{ $settings['platform_fee'] }} platform fee li jayegi
                                </div>
                            </div>
                            <div class="custom-control custom-switch" style="transform:scale(1.3);">
                                <input type="checkbox" class="custom-control-input" id="razorpay_enabled"
                                       name="razorpay_enabled" {{ $settings['razorpay_enabled']=='1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="razorpay_enabled"></label>
                            </div>
                        </div>

                        {{-- Key ID --}}
                        <div class="form-group">
                            <label style="font-weight:700;font-size:13px;color:#555;">
                                🔑 Razorpay Key ID
                            </label>
                            <input type="text" name="razorpay_key_id" class="form-control"
                                   value="{{ $settings['razorpay_key_id'] }}"
                                   placeholder="rzp_live_xxxxxxxxxx"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:12px;">
                            <small class="text-muted">Test ke liye: rzp_test_... | Live ke liye: rzp_live_...</small>
                        </div>

                        {{-- Key Secret --}}
                        <div class="form-group">
                            <label style="font-weight:700;font-size:13px;color:#555;">
                                🔒 Razorpay Key Secret
                            </label>
                            <input type="password" name="razorpay_key_secret" class="form-control"
                                   placeholder="Naya secret daalne par hi update hoga"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:12px;">
                            <small class="text-muted">Khali chhod dein agar change nahi karna</small>
                        </div>

                        {{-- Platform Fee --}}
                        <div class="form-group">
                            <label style="font-weight:700;font-size:13px;color:#555;">
                                💰 Platform Fee (₹)
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f0f4ff;border:1.5px solid #e0e0e0;font-weight:700;">₹</span>
                                </div>
                                <input type="number" name="platform_fee" class="form-control"
                                       value="{{ $settings['platform_fee'] }}" min="1" max="100"
                                       style="border-radius:0 10px 10px 0;border:1.5px solid #e0e0e0;padding:12px;">
                            </div>
                            <small class="text-muted">Minimum ₹1 — yeh fee appointment booking par user se li jayegi</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block"
                                style="border-radius:12px;padding:14px;font-weight:800;font-size:15px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;">
                            <i class="fa fa-save mr-2"></i> Settings Save Karein
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Status Card --}}
        <div class="col-lg-5">
            <div class="card" style="border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <div class="card-header" style="background:linear-gradient(135deg,#43e97b,#38f9d7);border-radius:16px 16px 0 0;padding:16px 24px;">
                    <h6 class="mb-0 text-white" style="font-weight:700;"><i class="fa fa-info-circle mr-2"></i>Current Status</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3 p-3"
                         style="background:#f8f9fa;border-radius:12px;">
                        <div style="width:14px;height:14px;border-radius:50%;background:{{ $settings['razorpay_enabled']=='1' ? '#00b074' : '#ef4444' }};margin-right:12px;"></div>
                        <div>
                            <div style="font-weight:700;font-size:14px;">Payment Gateway</div>
                            <div style="font-size:12px;color:{{ $settings['razorpay_enabled']=='1' ? '#00b074' : '#ef4444' }};font-weight:700;">
                                {{ $settings['razorpay_enabled']=='1' ? '✅ Active — Users se fee li ja rahi hai' : '❌ Disabled — Free booking chal rahi hai' }}
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mb-3" style="background:#fff8e1;border-radius:12px;border:1.5px solid #ffe082;">
                        <div style="font-size:12px;font-weight:700;color:#f59e0b;margin-bottom:4px;">💰 Platform Fee</div>
                        <div style="font-size:28px;font-weight:800;color:#1a1a2e;">₹{{ $settings['platform_fee'] }}</div>
                        <div style="font-size:11px;color:#888;">Har appointment booking par</div>
                    </div>

                    <div class="p-3" style="background:#e3f2fd;border-radius:12px;border:1.5px solid #90caf9;">
                        <div style="font-size:12px;font-weight:700;color:#1565c0;margin-bottom:6px;">🔑 Key ID</div>
                        <div style="font-size:12px;color:#333;word-break:break-all;font-family:monospace;">
                            {{ $settings['razorpay_key_id'] ?: '—' }}
                        </div>
                        <div class="mt-2" style="font-size:11px;color:#888;">
                            {{ str_starts_with($settings['razorpay_key_id'], 'rzp_test_') ? '🧪 Test Mode' : '🚀 Live Mode' }}
                        </div>
                    </div>

                    {{-- Quick Toggle --}}
                    <div class="mt-3">
                        <button onclick="quickToggle()" id="toggleBtn"
                                class="btn btn-block"
                                style="border-radius:12px;padding:12px;font-weight:700;border:none;background:{{ $settings['razorpay_enabled']=='1' ? '#ffebee' : '#e8f5e9' }};color:{{ $settings['razorpay_enabled']=='1' ? '#c62828' : '#2e7d32' }};">
                            {{ $settings['razorpay_enabled']=='1' ? '🔴 Payment Band Karo' : '🟢 Payment Chalu Karo' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
function quickToggle() {
    fetch('{{ route('admin.payment.toggle') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => { location.reload(); });
}
</script>
@endsection
