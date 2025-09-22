<?php

if(isset($res) && count($res)>0){
  
        $i=0;
        if(!empty($page)){
            $i= ($page - 1) * $page_size;
        }


foreach($res as $doctor){

?>
<tr>
    
    <td>{{ ++$i }}</td>
    <td>{{$doctor->name}}</td>
    <td>{{ $doctor->phone_no }}</td>
    <td>{{ $doctor->email }}</td>
    <td>{{ $doctor->visit_count }}</td>
    <td>
    @if ($doctor->status == 1)
        <a class="custom-badge status-green " href="#" data-toggle="dropdown" aria-expanded="false">
            Active
        </a>
    @else
        <a class="custom-badge status-red " href="#" data-toggle="dropdown" aria-expanded="false">
        Inactive
        </a>
    @endif
    </td>
    <td>
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
    </td>

    <td class="text-right">
        <div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('admin.doctor.add') }}?id={{$doctor->id}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_expense" data-id="{{$doctor->id}}" data-url="/admin/doctor/delete/{{ $doctor->id}}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
            </div>
        </div>
    </td>

</tr>
   
   <?php
   }
  
}else{
?>
<tr>
   <td colspan="8" class="text-center">No record found.</td>
</tr>
<?php  } ?>