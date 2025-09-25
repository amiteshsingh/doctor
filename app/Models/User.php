<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->hasOne(UserRole::class);
    }

    public function isAdmin()
    {
        return $this->role && $this->role->role === 'admin';
    }

    public function isDoctor()
    {
        return $this->role && $this->role->role === 'doctor';
    }

        public static function getResult($page = 1, $page_size = 10, $filter = []){
        $offset = ($page - 1) * $page_size;
        $query = DB::table('users')
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->select('users.*', 'user_roles.role', 'user_roles.user_id')
                ->where('user_roles.role', '=', 'doctor')
                ->offset($offset)
                ->limit($page_size);

                if(isset($filter['sortBy']) && $filter['sortBy'] !="" && isset($filter['orderBy']) && $filter['orderBy'] != ""){
                    $sortBy = $filter['sortBy'];
                    $orderBy = $filter['orderBy']; 
                    $query->orderBy($sortBy,$orderBy);
                }else{
                    $query->orderBy('users.id', 'desc');
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
        $query = DB::table('users')
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->select('users.id') // Only selecting ID to optimize count
        ->where('user_roles.role', '=', 'doctor')
        ->orderBy('users.id', 'desc');

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
