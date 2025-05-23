<?php

if(isset($res) && count($res)>0){
  
        $i=0;
        if(!empty($page)){
            $i= ($page - 1) * $page_size;
        }


foreach($res as $specialization){

?>
<tr>
    
    <td>{{ ++$i }}</td>
    <td>{{$specialization->name}}</td>

    <td>
    @if ($specialization->status == 1)
        <a class="custom-badge status-green " href="#" data-toggle="dropdown" aria-expanded="false">
            Active
        </a>
    @else
        <a class="custom-badge status-red " href="#" data-toggle="dropdown" aria-expanded="false">
        Inactive
        </a>
    @endif
    </td>
 

    <td class="text-right">
        <div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('admin.specialization.add') }}?id={{$specialization->id}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_expense" data-id="{{$specialization->id}}" data-url="/admin/specialization/delete/{{ $specialization->id}}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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