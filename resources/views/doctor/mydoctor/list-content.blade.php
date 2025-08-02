<?php

if(isset($res) && count($res)>0){
  
        $i=0;
        if(!empty($page)){
            $i= ($page - 1) * $page_size;
        }


foreach($res as $doctor){

?>



    <div class="col-md-4 col-sm-4  col-lg-3">
        <div class="profile-widget">
            <div class="doctor-img">
                @php
                $profileImage = isset($doctor->profile_pic) && file_exists(public_path('uploads/doctor/'.$doctor->profile_pic))
                                ? asset('uploads/doctor/'.$doctor->profile_pic)
                                : asset('uploads/doctor/user.jpg'); // default image path
                @endphp
                <a class="avatar" href="profile.html"><img alt="" src="{{ $profileImage }}"></a>
            </div>
            <div class="dropdown profile-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('doctor.mydoctor.add') }}?id={{$doctor->id}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_expense" data-id="{{$doctor->id}}" data-url="/admin/doctor/delete/{{ $doctor->id}}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                </div>
            </div>
            <h4 class="doctor-name text-ellipsis"><a href="profile.html">{{$doctor->name}}</a></h4>
            <div class="doc-prof">Gynecologist</div>
            <div class="user-country">
                <i class="fa fa-map-marker"></i> United States, San Francisco
            </div>
            
            <div class="d-flex justify-content-between mt-2">
            @if ($doctor->status == 1)
                <a class="custom-badge status-green " href="#" data-toggle="dropdown" aria-expanded="false">
                    Active
                </a>
            @else
                <a class="custom-badge status-red " href="#" data-toggle="dropdown" aria-expanded="false">
                Inactive
                </a>
            @endif

           &nbsp;
            
            @if ($doctor->approval_status == 1)

                <a class="custom-badge status-green " href="#" data-toggle="dropdown" aria-expanded="false">
                    Approved
                </a>
            @elseif($doctor->approval_status == 2 )
            <a class="custom-badge btn-dark " href="#" data-toggle="dropdown" aria-expanded="false">
                    Block
                </a>
            @else
                <a class="custom-badge status-red " href="#" data-toggle="dropdown" aria-expanded="false">
                    Pending
                </a>

            @endif
            </div>

        </div>
    </div>
   
    


   
   <?php
   }
  
}else{
?>

    <div class="col-md-4 col-sm-4  col-lg-3">
       
    </div>
   
<?php  } ?>