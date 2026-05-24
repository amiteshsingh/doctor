@extends('doctor.layouts.app')

@section('content')

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes shimmer {
    0%   { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.05); }
}

.doc-card {
    border: none;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
}
.doc-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.13);
}
.doc-card .card-banner {
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    position: relative;
}
.doc-card .card-banner.green  { background: linear-gradient(135deg, #43e97b, #38f9d7); }
.doc-card .card-banner.pink   { background: linear-gradient(135deg, #f093fb, #f5576c); }
.doc-card .card-banner.blue   { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.doc-card .card-banner.orange { background: linear-gradient(135deg, #fa709a, #fee140); }

.doc-card .doc-avatar {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 4px solid #fff;
    object-fit: cover;
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    transition: transform 0.3s;
}
.doc-card:hover .doc-avatar { transform: translateX(-50%) scale(1.08); }

.doc-card .card-body { padding: 50px 16px 16px; text-align: center; }
.doc-card .doc-name {
    font-size: 15px; font-weight: 700; color: #2d3748;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.doc-card .doc-spec { font-size: 12px; color: #667eea; font-weight: 600; margin-bottom: 4px; }
.doc-card .doc-loc  { font-size: 11px; color: #999; }

.badge-pill-custom {
    padding: 3px 10px; border-radius: 20px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.3px;
}
.badge-active   { background: #e6fff2; color: #00b96b; }
.badge-inactive { background: #fff0f0; color: #e53e3e; }
.badge-approved { background: #e8f4fd; color: #1a73e8; }
.badge-pending  { background: #fff8e1; color: #f59e0b; }
.badge-blocked  { background: #f3f4f6; color: #6b7280; }

.doc-actions { display: flex; gap: 6px; justify-content: center; margin-top: 10px; }
.doc-actions a {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; transition: all 0.2s; text-decoration: none;
}
.btn-edit   { background: #eef2ff; color: #667eea; }
.btn-edit:hover   { background: #667eea; color: #fff; }
.btn-view   { background: #e6fff2; color: #00b96b; }
.btn-view:hover   { background: #00b96b; color: #fff; }
.btn-delete { background: #fff0f0; color: #e53e3e; }
.btn-delete:hover { background: #e53e3e; color: #fff; }

/* Skeleton loader */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 400px 100%;
    animation: shimmer 1.2s infinite;
    border-radius: 8px;
}

/* Search bar */
.search-bar {
    border-radius: 12px; border: 2px solid #eef2ff;
    padding: 10px 16px; font-size: 14px;
    transition: border-color 0.2s;
    width: 100%;
}
.search-bar:focus { border-color: #667eea; outline: none; box-shadow: 0 0 0 3px rgba(102,126,234,0.15); }
</style>

<div class="page-wrapper">
    <div class="content">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap:10px;">
            <div>
                <h4 class="mb-0 fw-bold">My Doctors</h4>
                <small class="text-muted">Manage your registered doctors</small>
            </div>
            <a href="{{ route('doctor.mydoctor.add') }}" class="btn btn-primary btn-rounded">
                <i class="fa fa-plus mr-1"></i> Add Doctor
            </a>
        </div>

        <!-- Search -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="position-relative">
                    <i class="fa fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#aaa;"></i>
                    <input type="text" id="searchInput" class="search-bar" placeholder="Search by name..." style="padding-left:38px;">
                </div>
            </div>
        </div>

        <!-- Doctor Cards Grid -->
        <div class="row" id="data_listing">
            @if(isset($result['content_html']))
                <?= $result['content_html'] ?>
            @endif
        </div>

        <!-- Pagination -->
        <div id="pagination_data" class="mt-3">
            @if(isset($result['pagination_html']))
                <?= $result['pagination_html'] ?>
            @endif
        </div>

    </div>
</div>

<!-- Delete Confirm Modal -->
<div id="delete_expense" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center p-4">
                <div style="width:64px;height:64px;background:#fff0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fa fa-trash" style="color:#e53e3e;font-size:26px;"></i>
                </div>
                <h5 class="fw-bold mb-1">Delete Doctor?</h5>
                <p class="text-muted mb-4" style="font-size:13px;">This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-light px-4" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger px-4" id="confirmDelete">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Delete modal
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-toggle="modal"][data-target="#delete_expense"]').forEach(function(link) {
        link.addEventListener("click", function () {
            document.getElementById("confirmDelete").setAttribute("href", this.getAttribute("data-url"));
        });
    });
});

// Live search filter
document.getElementById('searchInput').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.doc-card-wrap').forEach(function(card) {
        var name = card.querySelector('.doc-name').textContent.toLowerCase();
        card.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>

@endsection
