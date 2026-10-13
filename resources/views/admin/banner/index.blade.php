@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="font-weight:800;color:#1a1a2e;">🖼️ Banner Management</h4>
            <small class="text-muted">Home screen ke scrolling banners manage karein</small>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    <div class="row">

        {{-- Add Banner Form --}}
        <div class="col-lg-4">
            <div class="card" style="border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,.08);position:sticky;top:20px;">
                <div class="card-header" style="background:linear-gradient(135deg,#0f0c29,#302b63);border-radius:16px 16px 0 0;padding:16px 24px;">
                    <h6 class="mb-0 text-white" style="font-weight:700;"><i class="fa fa-plus mr-2"></i>Naya Banner Add Karein</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Jaise: Stay Hydrated"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:10px;">
                        </div>

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="Jaise: Roz 8 glass paani piyein"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:10px;">
                        </div>

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Icon (Emoji)</label>
                            <input type="text" name="icon" class="form-control" placeholder="💧 🏃 😴 ❤️"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:10px;font-size:20px;">
                            <small class="text-muted">Koi bhi emoji paste karein</small>
                        </div>

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Background Color *</label>
                            <div class="d-flex align-items-center gap-2" style="gap:10px;">
                                <input type="color" name="color" value="#1A73E8" id="colorPicker"
                                       style="width:50px;height:40px;border-radius:8px;border:1.5px solid #e0e0e0;cursor:pointer;padding:2px;">
                                <input type="text" id="colorText" value="#1A73E8"
                                       style="flex:1;border-radius:10px;border:1.5px solid #e0e0e0;padding:10px;font-family:monospace;"
                                       oninput="document.getElementById('colorPicker').value=this.value">
                            </div>
                            {{-- Quick colors --}}
                            <div class="d-flex flex-wrap mt-2" style="gap:6px;">
                                @foreach(['#1A73E8','#00BFA5','#9C27B0','#FF6B6B','#FF9800','#4CAF50','#E91E8C','#F44336'] as $c)
                                <div onclick="setColor('{{ $c }}')"
                                     style="width:28px;height:28px;border-radius:6px;background:{{ $c }};cursor:pointer;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.2);"></div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Banner Image (Optional)</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*"
                                   style="border:1.5px solid #e0e0e0;border-radius:10px;padding:8px;">
                            <small class="text-muted">Agar image nahi denge toh sirf color background dikhega</small>
                        </div>

                        <div class="form-group">
                            <label style="font-weight:700;font-size:12px;color:#555;">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0"
                                   style="border-radius:10px;border:1.5px solid #e0e0e0;padding:10px;">
                            <small class="text-muted">Chhota number pehle dikhega</small>
                        </div>

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active" style="font-weight:700;">Active Rakhen</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block"
                                style="border-radius:12px;padding:12px;font-weight:800;background:linear-gradient(135deg,#667eea,#764ba2);border:none;">
                            <i class="fa fa-plus mr-2"></i> Banner Add Karein
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Banner List --}}
        <div class="col-lg-8">
            @forelse($banners as $banner)
            <div class="card mb-3" style="border-radius:16px;border:none;box-shadow:0 4px 16px rgba(0,0,0,.07);overflow:hidden;">
                <div class="d-flex align-items-stretch">

                    {{-- Color Preview --}}
                    <div style="width:8px;background:{{ $banner->color }};flex-shrink:0;"></div>

                    {{-- Banner Preview --}}
                    <div style="background:{{ $banner->color }};padding:16px 20px;min-width:200px;display:flex;align-items:center;gap:12px;flex-shrink:0;">
                        @if($banner->image)
                            <img src="{{ $banner->image }}"
                                 style="width:60px;height:60px;border-radius:10px;object-fit:cover;">
                        @else
                            <span style="font-size:36px;">{{ $banner->icon ?? '🏥' }}</span>
                        @endif
                        <div>
                            <div style="font-weight:800;color:#fff;font-size:14px;">{{ $banner->title }}</div>
                            @if($banner->subtitle)
                            <div style="font-size:11px;color:rgba(255,255,255,.85);margin-top:2px;">{{ $banner->subtitle }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Info + Actions --}}
                    <div class="d-flex align-items-center justify-content-between flex-1 px-3 py-2" style="flex:1;">
                        <div>
                            <div style="font-size:12px;color:#888;">Sort: <strong>{{ $banner->sort_order }}</strong></div>
                            <div class="mt-1">
                                <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:700;
                                    background:{{ $banner->is_active ? '#e8f5e9' : '#ffebee' }};
                                    color:{{ $banner->is_active ? '#2e7d32' : '#c62828' }};">
                                    {{ $banner->is_active ? '✅ Active' : '❌ Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex" style="gap:8px;">
                            <form method="POST" action="{{ route('admin.banner.toggle', $banner->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm"
                                        style="border-radius:8px;font-weight:700;border:none;
                                        background:{{ $banner->is_active ? '#fff3e0' : '#e8f5e9' }};
                                        color:{{ $banner->is_active ? '#e65100' : '#2e7d32' }};">
                                    {{ $banner->is_active ? '🔴 Band Karo' : '🟢 Chalu Karo' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.banner.delete', $banner->id) }}"
                                  onsubmit="return confirm('Is banner ko delete karna chahte hain?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm"
                                        style="border-radius:8px;font-weight:700;border:none;background:#ffebee;color:#c62828;">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5" style="background:#fff;border-radius:16px;box-shadow:0 4px 16px rgba(0,0,0,.07);">
                <div style="font-size:48px;margin-bottom:12px;">🖼️</div>
                <div style="font-size:16px;font-weight:700;color:#1a1a2e;">Koi banner nahi hai</div>
                <div style="font-size:13px;color:#888;margin-top:4px;">Left form se pehla banner add karein</div>
            </div>
            @endforelse
        </div>

    </div>
</div>
</div>

<script>
function setColor(hex) {
    document.getElementById('colorPicker').value = hex;
    document.getElementById('colorText').value = hex;
}
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
</script>
@endsection
