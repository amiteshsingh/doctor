@extends('doctor.layouts.app')

@section('content')

<style>
.hosp-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

/* ── TOP BAR ── */
.hosp-topbar {
    background: linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border-radius: 16px; padding: 20px 26px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; color: #fff;
    animation: heroSlide .5s ease both;
    position: relative; overflow: hidden;
}
.hosp-topbar::before {
    content:''; position:absolute; top:-40px; right:-40px;
    width:160px; height:160px; background:rgba(255,255,255,.07); border-radius:50%;
}
@keyframes heroSlide { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }
.hosp-topbar h4 { margin:0; font-size:20px; font-weight:800; }
.hosp-topbar small { opacity:.8; font-size:12px; }
.btn-add-hosp {
    background: rgba(255,255,255,.2); color: #fff;
    border: 1.5px solid rgba(255,255,255,.5); border-radius: 10px;
    padding: 9px 20px; font-size: 13px; font-weight: 700;
    text-decoration: none; transition: background .2s, transform .2s;
    backdrop-filter: blur(4px); white-space: nowrap;
}
.btn-add-hosp:hover { background: rgba(255,255,255,.35); color:#fff; text-decoration:none; transform:scale(1.04); }

/* ── FILTER CARD ── */
.filter-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    padding: 18px 22px; margin-bottom: 20px;
    animation: cardFade .4s ease .1s both;
}
@keyframes cardFade { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.filter-label { font-size:11px; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; display:block; }
.filter-input {
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    padding: 9px 13px; font-size: 13px; width: 100%;
    transition: border-color .25s, box-shadow .25s; background: #fafbff;
}
.filter-input:focus { border-color:#0a6ebd; box-shadow:0 0 0 3px rgba(10,110,189,.1); outline:none; }
select.filter-input { appearance: auto; }
.btn-clear {
    background: linear-gradient(135deg,#f0f4ff,#e8f5f0);
    color: #0a6ebd; border: 1.5px solid #d0e4ff;
    border-radius: 9px; padding: 9px 18px; font-size: 13px;
    font-weight: 700; cursor: pointer; transition: all .2s; width: 100%;
}
.btn-clear:hover { background: #dbeafe; }

/* ── TABLE CARD ── */
.table-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07); overflow: hidden;
    animation: cardFade .4s ease .2s both;
}
.table-card table { width:100%; border-collapse:collapse; }
.table-card thead tr {
    background: linear-gradient(135deg,#0a6ebd,#00b074);
}
.table-card thead th {
    padding: 13px 14px; color: #fff; font-size: 12px;
    font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    white-space: nowrap; border: none;
}
.table-card thead th i.ajaxSorting { cursor:pointer; opacity:.7; margin-left:4px; }
.table-card thead th i.ajaxSorting:hover { opacity:1; }
.table-card tbody tr {
    border-bottom: 1px solid #f0f4ff;
    transition: background .2s, transform .15s;
    animation: rowFade .35s ease both;
}
@keyframes rowFade { from{opacity:0;transform:translateX(-8px)} to{opacity:1;transform:translateX(0)} }
.table-card tbody tr:hover { background: #f8fbff; }
.table-card tbody td { padding: 12px 14px; font-size: 13.5px; vertical-align: middle; }

/* Hospital name cell */
.hosp-name-cell { display:flex; align-items:center; gap:10px; }
.hosp-avatar {
    width:36px; height:36px; border-radius:10px; flex-shrink:0;
    background: linear-gradient(135deg,#0a6ebd,#00b074);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-weight:800; font-size:14px;
}

/* Status badges */
.s-badge {
    display:inline-block; border-radius:20px; padding:3px 12px;
    font-size:11px; font-weight:700; white-space:nowrap;
}
.s-active   { background:#e6fff5; color:#00b074; border:1px solid #b3f0d8; }
.s-inactive { background:#fff0f0; color:#ef4444; border:1px solid #fecaca; }
.s-pending  { background:#fff8e6; color:#f59e0b; border:1px solid #fde68a; }
.s-block    { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }
.s-approved { background:#e8f3ff; color:#0a6ebd; border:1px solid #bfdbfe; }

/* Action dropdown */
.action-btn {
    width:32px; height:32px; border-radius:8px;
    background:#f0f4ff; color:#0a6ebd; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    cursor:pointer; transition:background .2s;
}
.action-btn:hover { background:#dbeafe; }
</style>

<div class="page-wrapper hosp-page">
<div class="content">

    {{-- Top Bar --}}
    <div class="hosp-topbar">
        <div style="position:relative;z-index:1;">
            <h4><i class="fa fa-hospital-o mr-2"></i>{{ $title }}</h4>
            <small>Manage your hospitals & clinics</small>
        </div>
        <a href="{{ route('doctor.myhospital.add') }}" class="btn-add-hosp" style="position:relative;z-index:1;">
            <i class="fa fa-plus mr-1"></i> Add Hospital
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card">
        <div class="row" style="row-gap:12px;align-items:flex-end;">
            <div class="col-md-4">
                <label class="filter-label">Search</label>
                <input type="text" class="filter-input filterHospital" id="search"
                       placeholder="Name, Phone, City, State, Pin...">
                <input type="hidden" id="sortBy" value="">
                <input type="hidden" id="orderBy" value="">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Status</label>
                <select class="filter-input filterHospital" id="status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="filter-label">Approval</label>
                <select class="filter-input filterHospital" id="approval_status">
                    <option value="">All</option>
                    <option value="1">Approved</option>
                    <option value="0">Pending</option>
                    <option value="2">Block</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn-clear" onclick="FilterReset(1,'hospital','hospital','filterHospital')">
                    <i class="fa fa-times mr-1"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hospital
                            <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="name" data-sort_order="asc"></i>
                        </th>
                        <th>Phone
                            <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="phone_no" data-sort_order="asc"></i>
                        </th>
                        <th>City
                            <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="city" data-sort_order="asc"></i>
                        </th>
                        <th>State
                            <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="state" data-sort_order="asc"></i>
                        </th>
                        <th>Pin Code</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="data_listing">
                    @if(isset($result['content_html']))
                        {!! $result['content_html'] !!}
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3" id="pagination_data">
        @if(isset($result['pagination_html']))
            {!! $result['pagination_html'] !!}
        @endif
    </div>

</div>
</div>

{{-- Delete Modal --}}
<div id="delete_expense" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
            <div class="modal-body text-center" style="padding:30px;">
                <div style="width:60px;height:60px;border-radius:50%;background:#fff0f0;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fa fa-trash fa-2x" style="color:#ef4444;"></i>
                </div>
                <h5 style="font-weight:700;margin-bottom:8px;">Delete Hospital?</h5>
                <p style="color:#888;font-size:13px;">This action cannot be undone.</p>
                <div class="mt-3 d-flex justify-content-center gap-2" style="gap:10px;">
                    <a href="#" class="btn btn-light" data-dismiss="modal" style="border-radius:8px;min-width:90px;">Cancel</a>
                    <a href="#" class="btn btn-danger" id="confirmDelete" style="border-radius:8px;min-width:90px;">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function(e) {
        var el = e.target.closest('[data-target="#delete_expense"]');
        if (el) document.getElementById('confirmDelete').setAttribute('href', el.getAttribute('data-url'));
    });

    var t;
    document.getElementById('search').addEventListener('keyup', function() {
        clearTimeout(t);
        t = setTimeout(function(){ ajaxSearching(1,'hospital','hospital'); }, 400);
    });
    document.getElementById('status').addEventListener('change', function(){ ajaxSearching(1,'hospital','hospital'); });
    document.getElementById('approval_status').addEventListener('change', function(){ ajaxSearching(1,'hospital','hospital'); });
});
</script>

@endsection
