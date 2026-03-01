@extends('admin.layout.app')

@section('content')


<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{$title}}</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.hospital.add') }}" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Hospital</a>
            </div>
        </div>
        <div class="row filter-row">
            
            <div class="col-md-5">
                <div class="form-group form-focus">
                    <label class="focus-label">Name, Phone, City, State, Pin Code</label>
                    <input type="text" class="form-control floating filterHospital" id="search" >
                    <input type="hidden" name="sortBy" id="sortBy" value="">
                    <input type="hidden" name="orderBy" id="orderBy" value="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus select-focus">
                    <label class="focus-label">Status</label>
                    <select class="filterHospital select floating" id="status">
                        <option value=""> -- Select -- </option>
                        <option value="1">Active</option>
                        <option  value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-focus select-focus">
                    <label class="focus-label">Approve Status</label>
                    <select class="filterHospital select floating"  id="approval_status">
                        <option value=""> -- Select -- </option>
                        <option value="1">Active</option>
                        <option  value="0">Inactive</option>
                        <option  value="2">Block</option>
                    </select>
                </div>
            </div>
          
        
           
            <div class="col-md-3">
                <!-- <a href="#" class=""> Reset </a> -->
                <a href="javascript:void(0)" class="btn btn-success btn-block" onclick="FilterReset(1,'hospital','hospital','filterHospital')">Clear All Filters</a>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name <i class="fas ajaxSorting fa-sort " data-type="hospital" data-sort_by="name" data-sort_order="asc"></i> </th>
                                <th>Phone <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="phone_no" data-sort_order="asc"></i></th>
                                <!-- <th>Email</th> -->
                                <th>City <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="city" data-sort_order="asc"></i></th>
                                <th>State <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="state" data-sort_order="asc"></i></th>
                                <th>Pin Code <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="pin_code" data-sort_order="asc"></i></th>
                                <th>Visits <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="visit_count" data-sort_order="asc"></i></th>
                                <th >Status <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="status" data-sort_order="asc"></i></th>
                                <th>Approve <i class="fas ajaxSorting fa-sort" data-type="hospital" data-sort_by="approval_status" data-sort_order="asc"></i></th>
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
    <div class="notification-box">
        <div class="msg-sidebar notifications msg-noti">
            <div class="topnav-dropdown-header">
                <span>Messages</span>
            </div>
            <div class="drop-scroll msg-list-scroll" id="msg_list">
                <ul class="list-box">
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Richard Miles </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item new-message">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">John Doe</span>
                                    <span class="message-time">1 Aug</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Tarah Shropshire </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Mike Litorus</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Catherine Manseau </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">D</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Domenic Houston </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">B</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Buster Wigton </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">R</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Rolland Webber </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">C</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author"> Claire Mapes </span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">M</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Melita Faucher</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">J</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Jeffery Lalor</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">L</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Loren Gatlin</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="chat.html">
                            <div class="list-item">
                                <div class="list-left">
                                    <span class="avatar">T</span>
                                </div>
                                <div class="list-body">
                                    <span class="message-author">Tarah Shropshire</span>
                                    <span class="message-time">12:28 AM</span>
                                    <div class="clearfix"></div>
                                    <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <a href="chat.html">See all messages</a>
            </div>
        </div>
    </div>
</div>


<div id="delete_expense" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="assets/img/sent.png" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Hospital?</h3>
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
