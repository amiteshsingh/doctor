<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class DoctorController extends Controller
{
    public function index(Request $request){
        
        try{
            $page = 1;
            $page_size = 10;
            $filter = [];
            $result = [];
            if($request->isMethod('post') && $request->ajax() && $request->session()->has('user_id')){
                $filter = $request->all();
                $page =  isset($filter['page'])?$filter['page']:$page;
                $records = Doctor::getResult($page, $page_size, $filter);
                $total = Doctor::getTotalResult($filter);
                $content_html =  view('admin.doctor.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $pagination_html = view('pagination.pagination')->with(['url'=> 'doctor', 'recTotal' => $total, 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'doctor'])->render();
                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';
                return response()->json($result);

            }else{
                $records = Doctor::getResult($page,$page_size, $filter);
                $result['total_count'] = Doctor::getTotalResult($filter);
                $result['page'] = $page;
                $result['page_size'] = $page_size;
                $pagination_html = view('pagination.pagination')->with(['url'=> 'doctor', 'recTotal' => $result['total_count'], 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'doctor'])->render();
                $result['pagination_html'] = $pagination_html;
                $content_html =  view('admin.doctor.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $result['content_html'] = $content_html;
            }
        }catch(\Exception $e){
            var_dump($e->getMessage()); die;
            return redirect()->back()->withError('Something went wrong');
        }
        $title = "Doctor List";
        return view('admin.doctor.index', compact('result', 'title')); 

    }

    public function add(Request $request){
        // echo "Doctor add";
        $data = $request->all();
        if($request->isMethod('post') && $request->ajax()){
            try{
                $request->validate([
                    'name' => 'required',
                    'phone_no' => 'required',
                    'email' => 'required',
                    'status' => 'required',
                    'approval_status' => 'required',
                ]);
                
                if(isset($data['id']) && $data['id'] !=""){
                    //Update doctor
                    $update['name'] = $data['name'];
                    $update['phone_no'] = $data['phone_no'];
                    $update['email'] = $data['email'];
                    $update['status'] = $data['status'];
                    $update['approval_status'] = $data['approval_status'];
                    $update['latitude'] = $data['latitude'];
                    $update['longitude'] = $data['longitude'];
                    $update['updated_on'] = date('Y-m-d H:i:s');
                    
                   
                    if(DB::table('doctors')->where('id', $data['id'])->update($update)){
                        return response()->json(["status"=>200,"msg"=>"Doctor updated successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Doctor not updated.']);
                    }
                }else{
                    //Save doctor 
                    $doctor = new Doctor;
                    $doctor->name = isset($data['name'])?$data['name']:'';
                    $doctor->phone_no = isset($data['phone_no'])?$data['phone_no']:'';
                    $doctor->email = isset($data['email'])?$data['email']:'';
                    // $doctor->latitude = isset($data['latitude'])?$data['latitude']:'';
                    // $doctor->longitude = isset($data['longitude'])?$data['longitude']:'';
                    $doctor->status = isset($data['status'])?$data['status']:'';
                    $doctor->approval_status = isset($data['approval_status'])?$data['approval_status']:'';
                    $doctor->added_on = date('Y-m-d H:i:s');
                    if($doctor->save()){
                        return response()->json(["status"=>200,"msg"=>"Doctors saved successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Invalid request']);
                    }
                }
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }
            
        }else{
            $doctor = (object)[];
            $selected_specializations = [];
            if(isset($data['id']) && !empty($data['id'])){
                $id = $data['id'];
                $doctor = Doctor::find($id);

                $selected_specializations = DB::table('doctor_specializations')
                ->where('doctor_id', $data['id'])
                ->pluck('specialization_id')
                ->toArray();
                $doctor->specialization_data = $selected_specializations;

                $selected_languages = DB::table('doctor_languages')
                ->where('doctor_id', $doctor->id ?? 0)
                ->pluck('language_id')
                ->toArray();
                $doctor->language_data = $selected_languages;

                // 🔹 Get location data
                $location = DB::table('doctor_locations')->where('doctor_id', $data['id'])->first();
                if ($location) {
                    $doctor->practice_name = $location->practice_name;
                    $doctor->address = $location->address;
                    $doctor->city = $location->city;
                    $doctor->state = $location->state;
                    $doctor->zip_code = $location->zip_code;
                    $doctor->location_phone = $location->phone;
                    $doctor->website = $location->website ?? '';
                }

                // 🔹 Get education data
                $education = DB::table('doctor_educations')->where('doctor_id', $data['id'])->first();
                if ($education) {
                    $doctor->degree_type = $education->degree_type;
                    $doctor->institution_name = $education->institution_name;
                    $doctor->graduation_year = $education->graduation_year;
                    $doctor->education_details = $education->details;
                }
                
            }
            $specializations = DB::table('specializations')->where('status', 1)->get()->toArray();
            $languages = DB::table('languages')->get()->toArray();

            return view('admin.doctor.add', compact('doctor', 'specializations', 'languages'));
        }
    }

    public function doctorSpecializations(Request $request){

        if(empty(Session::get('user_id'))){ 
            return redirect('/');
        }
        $data = $request->all();
        if($request->isMethod('post') && $request->ajax()){
            try{
                $request->validate([
                    'specialization_ids' => 'required|array',
                ]);
                if(!isset($data['id']) || empty($data['id'])){
                    return response()->json(["status"=>403,"msg"=>"Invalid doctor id."]);
                }
                $doctor_specialization = DB::table('doctor_specializations')->where('doctor_id', $data['id'])->delete();
                if(isset($data['specialization_ids']) && !empty($data['specialization_ids'])){
                    foreach($data['specialization_ids'] as $spec){
                        $insert = [
                            'doctor_id' => $data['id'],
                            'specialization_id' => $spec,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        DB::table('doctor_specializations')->insert($insert);
                    }
                }
                return response()->json(["status"=>200,"msg"=>"Doctor specializations updated successfully.", "doctor_id" => $data['id']]);
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }  
        }
    }

    public function doctorLocation(Request $request){

        if (empty(Session::get('user_id'))) {
            return redirect('/');
        }

        $data = $request->all();

        if ($request->isMethod('post') && $request->ajax()) {
            try {
                $request->validate([
                    'practice_name'      => 'required',
                    'address'            => 'required',
                    'city'               => 'required',
                    'state'              => 'required',
                    'zip_code'           => 'required',
                    'location_phone'     => 'required',
                    'degree_type'        => 'required',
                    'institution_name'   => 'required',
                    'graduation_year'    => 'required',
                    'education_details'  => 'required',
                    'languages'          => 'required|array|min:1',
                ]);

                if (!isset($data['id']) || empty($data['id'])) {
                    return response()->json(["status" => 403, "msg" => "Invalid doctor ID."]);
                }

                $doctor_id = $data['id'];

                // 🔁 doctor_locations table
                $location_data = [
                    'practice_name' => $data['practice_name'],
                    'address'       => $data['address'],
                    'city'          => $data['city'],
                    'state'         => $data['state'],
                    'zip_code'      => $data['zip_code'],
                    'phone'         => $data['location_phone'],
                    'updated_at'    => now(),
                ];

                // Check if record exists
                $existingLocation = DB::table('doctor_locations')->where('doctor_id', $doctor_id)->first();
                if ($existingLocation) {
                    DB::table('doctor_locations')->where('doctor_id', $doctor_id)->update($location_data);
                } else {
                    $location_data['doctor_id'] = $doctor_id;
                    $location_data['created_at'] = now();
                    DB::table('doctor_locations')->insert($location_data);
                }

                // doctor_educations table (replace with latest)
                DB::table('doctor_educations')->where('doctor_id', $doctor_id)->delete();
                DB::table('doctor_educations')->insert([
                    'doctor_id'         => $doctor_id,
                    'degree_type'       => $data['degree_type'],
                    'institution_name'  => $data['institution_name'],
                    'graduation_year'   => $data['graduation_year'],
                    'details'           => $data['education_details'],
                ]);

                //  doctor_languages table (multi-select)
                DB::table('doctor_languages')->where('doctor_id', $doctor_id)->delete();
                foreach ($data['languages'] as $lang_id) {
                    DB::table('doctor_languages')->insert([
                        'doctor_id'   => $doctor_id,
                        'language_id' => $lang_id,
                    ]);
                }

                return response()->json([
                    "status"     => 200,
                    "msg"        => "Doctor location, education & languages updated successfully.",
                    "doctor_id"  => $doctor_id
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    "status" => 500,
                    "msg"    => "Server Error: " . $e->getMessage()
                ]);
            }
        }
    }




    public function delete(Request $request, $id){
        if(empty(Session::get('user_id'))){
			return redirect('/');
		}
        $data = DB::table('doctors')->where('id','=',$id)->delete();
        $request->session()->flash('msg','doctor delete successfully.');
        return redirect('admin/doctor');  
    }
}
