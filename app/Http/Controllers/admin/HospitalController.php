<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request){
        // echo "Hospital index"; die;
        return view('admin.hospital.index');
    }


    public function add(Request $request){
        echo "Hospital add"; die;
    }

    public function delete(Request $request){
        echo "Hospital delete"; die;
    }
}
