<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'phone_no', 'address', 'email', 'city', 'state', 'zip_code', 'latitude', 'longitude',
        'longitude', 'status', 'approval_status'
     ];
    protected  $table = 'hospitals';
    public $timestamps = false;  
    protected $primaryKey = 'id';

    public static function getResult($page = 1, $page_size = 10, $filter = []){
        $offset = ($page - 1) * $page_size;
        $query = DB::table('hospitals')
                ->offset($offset)
                ->limit($page_size);
                if(isset($filter['sortBy']) && $filter['sortBy'] !="" && isset($filter['orderBy']) && $filter['orderBy'] != ""){
                    $sortBy = $filter['sortBy'];
                    $orderBy = $filter['orderBy']; 
                    $query->orderBy($sortBy,$orderBy);
                }else{
                    $query->orderBy('hospitals.id', 'desc');
                }
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('hospitals.status','=', $filter['status']);
                }
                if(isset($filter['search']) && $filter['search'] !=""){
                    $search = $filter['search'];
                    $query->where(function($query) use ($search){
                        $query->Where('name', 'LIKE','%' . $search . '%');
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
        $query = DB::table('hospitals')
                ->orderBy('hospitals.id','desc');
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('hospitals.status','=', $filter['status']);
                }
                if(isset($filter['search']) && $filter['search'] !=""){
                    $search = $filter['search'];
                    $query->where(function($query) use ($search){
                        $query->Where('name', 'LIKE','%' . $search . '%');
                    });   
                }
        $data = $query->get()->toArray();
        return count($data);
    }
}
