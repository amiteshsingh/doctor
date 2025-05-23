@extends('admin.layout.app')

@section('content')


<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{$title}}</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.specialization.add') }}" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Specialization</a>
            </div>
        </div>
        <div class="row filter-row">
            
            <div class="col-md-5">
                <div class="form-group form-focus">
                    <label class="focus-label">Name</label>
                    <input type="text" class="form-control floating filterSpecialization" id="search" >
                    <input type="hidden" name="sortBy" id="sortBy" value="">
                    <input type="hidden" name="orderBy" id="orderBy" value="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus select-focus">
                    <label class="focus-label">Status</label>
                    <select class="filterSpecialization select floating" id="status">
                        <option value=""> -- Select -- </option>
                        <option value="1">Active</option>
                        <option  value="0">Inactive</option>
                    </select>
                </div>
            </div>

        
           
            <div class="col-md-3">
                <!-- <a href="#" class=""> Reset </a> -->
                <a href="javascript:void(0)" class="btn btn-success btn-block" onclick="FilterReset(1,'specialization','specialization','filterSpecialization')">Clear All Filters</a>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name <i class="fas ajaxSorting fa-sort " data-type="specialization" data-sort_by="name" data-sort_order="asc"></i> </th>
                                <th >Status <i class="fas ajaxSorting fa-sort" data-type="specialization" data-sort_by="status" data-sort_order="asc"></i></th>
                          <th class="text-right">Actions</th>

                            </tr>
                        </thead>
                        <tbody id="data_listing">
                                @if(isset($result['content_html']))
                                <?= $result['content_html'] ?>
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
                <div class="box-footer clearfix">                  
                    <div id="pagination_data">
                        @if(isset($result['pagination_html']))
                            <?= $result['pagination_html'] ?>
                        @endif
                    </div>
                </div>

    </div>
 
</div>


<div id="delete_expense" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="assets/img/sent.png" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Specialization?</h3>
                <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <a href="#" class="btn btn-danger" id="confirmDelete">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteLinks = document.querySelectorAll('[data-toggle="modal"][data-target="#delete_expense"]');
    const confirmDelete = document.getElementById("confirmDelete");

    deleteLinks.forEach(link => {
        link.addEventListener("click", function () {
            const deleteUrl = this.getAttribute("data-url");
            confirmDelete.setAttribute("href", deleteUrl);
        });
    });
});
</script>




@endsection
