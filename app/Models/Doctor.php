<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;



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
                ->leftjoin('doctor_locations', 'doctors.id', '=', 'doctor_locations.doctor_id')
                ->select(
                    'doctors.*',
                    'doctor_locations.practice_name',
                    'doctor_locations.address',
                    'doctor_locations.city',
                    'doctor_locations.state',
                    'doctor_locations.zip_code'
                )
                ->offset($offset)
                ->limit($page_size);

                // Middleware 'doctor' restriction
                $user = Auth::user();
                $userRole = UserRole::where('user_id', $user->id)->first();
                if (isset($userRole->role) && $userRole->role == 'doctor') {
                    $query->where('added_by', $userRole->user_id);
                }

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
        // echo $query->toSql();
        return $data;
    }

    /**
    * Get total count data according to filter.
    * param  parameter filter array mixed type
    * return Result fetching data
    */
    public static function getTotalResult($filter=[]){
        $query = DB::table('doctors')
        ->leftjoin('doctor_locations', 'doctors.id', '=', 'doctor_locations.doctor_id')
        ->select('doctors.id') // Only selecting ID to optimize count
        ->orderBy('doctors.id', 'desc');


        // Middleware 'doctor' restriction
        $user = Auth::user();
        $userRole = UserRole::where('user_id', $user->id)->first();
        if (isset($userRole->role) && $userRole->role == 'doctor') {
            $query->where('added_by', $userRole->user_id);
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
        // echo $data = $query->toSQL(); die;
        $data = $query->get()->toArray();
        return count($data);
    }



    public static function allDoctor()
    {
        return collect([
            [
                "id" => 1,
                "name" => "Dr. Amit Singh",
                "specialization" => "Cardiologist",
                "phone" => "9876543210",
                "address" => "Delhi",
                "experience" => 12,
                "image" => "img/blog-1.jpg",
                "social_links" => [
                    "facebook" => "https://facebook.com/amit",
                    "twitter" => "https://twitter.com/amit",
                    "linkedin" => "https://linkedin.com/in/amit",
                    "instagram" => "https://instagram.com/amit",
                    "whatsapp" => "https://wa.me/9876543210",
                ]
            ],
            [
                "id" => 2,
                "name" => "Dr. Neha Sharma",
                "specialization" => "Dermatologist",
                "phone" => "9123456789",
                "address" => "Gurgaon",
                "experience" => 7,
                "image" => "img/blog-2.jpg",
                "social_links" => [
                    "facebook" => "https://facebook.com/neha",
                    "linkedin" => "https://linkedin.com/in/neha",
                ]
            ],
        ]);
    }
}
