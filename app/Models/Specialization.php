<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Specialization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'status'
     ];
    protected  $table = 'specializations';
    public $timestamps = false;  
    protected $primaryKey = 'id';

    public static function getResult($page = 1, $page_size = 10, $filter = []){
        $offset = ($page - 1) * $page_size;
        $query = DB::table('specializations')
                ->offset($offset)
                ->limit($page_size);
                if(isset($filter['sortBy']) && $filter['sortBy'] !="" && isset($filter['orderBy']) && $filter['orderBy'] != ""){
                    $sortBy = $filter['sortBy'];
                    $orderBy = $filter['orderBy']; 
                    $query->orderBy($sortBy,$orderBy);
                }else{
                    $query->orderBy('specializations.id', 'desc');
                }
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('specializations.status','=', $filter['status']);
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
        $query = DB::table('specializations')
                ->orderBy('specializations.id','desc');
                
                if(isset($filter['status']) && $filter['status'] != ""){
                    $query->where('specializations.status','=', $filter['status']);
                }
              
                if(isset($filter['search']) && $filter['search'] !=""){
                    $search = $filter['search'];
                    $query->where(function($query) use ($search){
                        $query->Where('name', 'LIKE','%' . $search . '%');
                    });   
                }
                // echo $data = $query->toSQL(); die;
                $data = $query->get()->toArray();
                return count($data);
    }
}
