@extends('doctor.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ url('doctor/invoice-master/add') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add Invoice
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session()->has('msg'))
            <div class="alert alert-success">{{ session('msg') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">Invoice List</h4>

                    {{-- Content --}}
                    <div id="invoiceContent">
                        {!! $result['content_html'] !!}
                    </div>

                    {{-- Pagination --}}
                    <div id="pagination" class="mt-3">
                        {!! $result['pagination_html'] !!}
                    </div>
                </div> <!-- /.card-box -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.content -->
</div> <!-- /.page-wrapper -->
@endsection

@push('scripts')
<script>
    function ajaxSearching(page = 1) {
        $.ajax({
            url: "{{ url('doctor/invoice-master') }}",
            type: "POST",
            data: {
                page: page,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if(res.error === 0){
                    $("#invoiceContent").html(res.content_html);
                    $("#pagination").html(res.pagination_html);
                }
            },
            error: function(){
                alert("Something went wrong.");
            }
        });
    }
</script>
@endpush
