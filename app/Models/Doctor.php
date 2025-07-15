<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'phone', 'email', 'latitude', 'longitude','hospital_id', 'status', 'approval_status'
     ];
    protected  $table = 'doctors';
    public $timestamps = false;  
    protected $primaryKey = 'id';

    public static function getResult($page = 1, $page_size = 10, $filter = []){
        $offset = ($page - 1) * $page_size;
        $query = DB::table('doctors')
                ->offset($offset)
                ->limit($page_size);
                if(isset($filter['sortBy']) && $filter['sortBy'] !="" && isset($filter['orderBy']) && $filter['orderBy'] != ""){
                    $sortBy = $filter['sortBy'];
                    $orderBy = $filter['orderBy']; 
                    $query->orderBy($sortBy,$orderBy);
                }else{
                    $query->orderBy('doctors.id', 'desc');
                }
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('doctors.status','=', $filter['status']);
                }
                if(isset($filter['approval_status']) && $filter['approval_status'] != ""){
                    $query->where('doctors.approval_status','=', $filter['approval_status']);
                }
                if(isset($filter['search']) && $filter['search'] !=""){
                    $search = $filter['search'];
                    $query->where(function($query) use ($search){
                        $query->Where('name', 'LIKE','%' . $search . '%')
                        ->orWhere('phone_no', 'LIKE','%' . $search . '%')
                        ->orWhere('email', 'LIKE','%' . $search . '%');
                    });   
                }
        $data = $query->get()->toArray();
        return $data;
    }

    /**
    * Get total count data according to filter.
    * param  parameter filter array mixed type
    * return Result fetching data
    */
    public static function getTotalResult($filter=[]){
        $query = DB::table('doctors')
                ->orderBy('doctors.id','desc');
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('doctors.status','=', $filter['status']);
                }
                if(isset($filter['approval_status']) && $filter['approval_status'] != ""){
                    $query->where('doctors.approval_status','=', $filter['approval_status']);
                }
                if(isset($filter['search']) && $filter['search'] !=""){
                    $search = $filter['search'];
                    $query->where(function($query) use ($search){
                        $query->Where('name', 'LIKE','%' . $search . '%')
                        ->orWhere('phone_no', 'LIKE','%' . $search . '%')
                        ->orWhere('email', 'LIKE','%' . $search . '%');
                    });   
                }
                // echo $data = $query->toSQL(); die;
                $data = $query->get()->toArray();
                return count($data);
    }
}
