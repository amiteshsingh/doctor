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

                    if ($request->hasFile('profile_pic')) {
                        $image = $request->file('profile_pic');
                        $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        $image->move(public_path('uploads/doctor'), $imageName);
                        $update['profile_pic'] = $imageName;
                    }

                    $update['name'] = $data['name'];
                    $update['phone_no'] = $data['phone_no'];
                    $update['email'] = $data['email'];
                    $update['status'] = $data['status'];
                    $update['approval_status'] = $data['approval_status'];
                    $update['latitude'] = $data['latitude'];
                    $update['longitude'] = $data['longitude'];
                    $update['updated_on'] = date('Y-m-d H:i:s');  
                    $update['updated_by'] = Session::get('user_id');                  
                    $update['hospital_id'] = $data['hospital_id'];                  
                   
                    if(DB::table('doctors')->where('id', $data['id'])->update($update)){
                        return response()->json(["status"=>200,"msg"=>"Doctor updated successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Doctor not updated.']);
                    }
                }else{
                    //Save doctor 
                    $doctor = new Doctor;

                    if ($request->hasFile('profile_pic')) {
                        $image = $request->file('profile_pic');
                        $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        $image->move(public_path('uploads/doctor'), $imageName);
                        $doctor->profile_pic = $imageName;
                    }
                    $doctor->name = isset($data['name'])?$data['name']:'';
                    $doctor->phone_no = isset($data['phone_no'])?$data['phone_no']:'';
                    $doctor->email = isset($data['email'])?$data['email']:'';
                    $doctor->status = isset($data['status'])?$data['status']:'';
                    $doctor->approval_status = isset($data['approval_status'])?$data['approval_status']:'';
                    $doctor->added_on = date('Y-m-d H:i:s');
                    $doctor->added_by = Session::get('user_id');
                    $doctor->updated_by = Session::get('user_id');
                    
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
                
                $doctor_availability = DB::table('doctor_availability')->where('doctor_id', $data['id'])->get();
                $availability = [];
                foreach ($doctor_availability as $entry) {
                    $availability[$entry->day] = [
                        'start_time' => $entry->start_time,
                        'end_time' => $entry->end_time,
                    ];
                }

                // Attach to doctor object (or array)
                $doctor->availability = $availability;
            }
            $specializations = DB::table('specializations')->where('status', 1)->get()->toArray();
            $languages = DB::table('languages')->get()->toArray();
            $hospitals = DB::table('hospitals')->where('updated_by', Session::get('user_id'))->get()->toArray();
            $states = DB::table('states')->get()->toArray();

            return view('admin.doctor.add', compact('doctor', 'specializations', 'languages', 'states', 'hospitals'));
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

    public function doctorLocation(Request $request)
    {
        try {
            if (empty(Session::get('user_id'))) {
                return redirect('/');
            }

            $data = $request->all();

            if ($request->isMethod('post') && $request->ajax()) {
                $request->validate([
                    'practice_name'      => 'required',
                    'address'            => 'required',
                    'city'               => 'required',
                    'state'              => 'required',
                    'pin_code'           => 'required|numeric',
                    'location_phone'     => 'required',
                    'degree_type'        => 'required',
                    'institution_name'   => 'required',
                    'graduation_year'    => 'required',
                    'education_details'  => 'required',
                    'languages'          => 'required|array|min:1',
                ]);

                if (empty($data['id'])) {
                    return response()->json(["status" => 403, "msg" => "Invalid doctor ID."]);
                }

                $doctor_id = $data['id'];

                // Insert or update doctor location
                $location_data = [
                    'practice_name' => $data['practice_name'],
                    'address'       => $data['address'],
                    'city'          => $data['city'],
                    'state'         => $data['state'],
                    'zip_code'      => $data['pin_code'],
                    'phone'         => $data['location_phone'],
                    'updated_at'    => now(),
                ];

                $existing = DB::table('doctor_locations')->where('doctor_id', $doctor_id)->first();
                if ($existing) {
                    DB::table('doctor_locations')->where('doctor_id', $doctor_id)->update($location_data);
                } else {
                    $location_data['doctor_id']  = $doctor_id;
                    $location_data['created_at'] = now();
                    DB::table('doctor_locations')->insert($location_data);
                }

                // Insert doctor education (one record)
                DB::table('doctor_educations')->where('doctor_id', $doctor_id)->delete();
                DB::table('doctor_educations')->insert([
                    'doctor_id'         => $doctor_id,
                    'degree_type'       => $data['degree_type'],
                    'institution_name'  => $data['institution_name'],
                    'graduation_year'   => $data['graduation_year'],
                    'details'           => $data['education_details'],
                ]);

                if(isset($data['experience']) && !empty($data['experience'])){
                    // Update doctor experience if provided
                   DB::table('doctors')->where('id', $doctor_id)->update(['experience' => $data['experience']]);     
                }

                // Insert doctor languages (multiple)
                DB::table('doctor_languages')->where('doctor_id', $doctor_id)->delete();
                foreach ($data['languages'] as $lang_id) {
                    DB::table('doctor_languages')->insert([
                        'doctor_id'   => $doctor_id,
                        'language_id' => $lang_id,
                    ]);
                }

                return response()->json([
                    "status"     => 200,
                    "msg"        => "Doctor location, education, and languages updated successfully.",
                    "doctor_id"  => $doctor_id
                ]);
            }

            return response()->json(["status" => 405, "msg" => "Invalid request."]);

        } catch (\Exception $e) {
            // Optional: Log the error to Laravel log
            \Log::error('Doctor Location Update Error: ' . $e->getMessage());

            return response()->json([
                "status" => 500,
                "msg"    => "Server Error: " . $e->getMessage()
            ]);
        }
    }


    public function doctorAvailability(Request $request)
    {
        try {
            $request->validate([
                'id'           => 'required|integer|exists:doctors,id',
                'availability' => 'required|array',
            ]);

            $doctorId    = $request->input('id');
            $availability = $request->input('availability');
            // Delete existing availability
            DB::table('doctor_availability')->where('doctor_id', $doctorId)->delete();

            $inserts = [];
            foreach ($availability as $day => $time) {
                if (!empty($time['start_time']) && !empty($time['end_time'])) {
                    $inserts[] = [
                        'doctor_id'  => $doctorId,
                        'day'        => $day,
                        'start_time' => $time['start_time'],
                        'end_time'   => $time['end_time'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }else{
                    $inserts[] = [
                        'doctor_id'  => $doctorId,
                        'day'        => $day,
                        'start_time' => 'Closed',
                        'end_time'   => 'Closed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($inserts)) {
                DB::table('doctor_availability')->insert($inserts);
            }

            return response()->json([
                'status'     => 200,
                'msg'    => 'Availability saved successfully!',
                'doctor_id'  => $doctorId,
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'status'  => 422,
                'msg' => 'Validation failed.',
                'errors'  => $ve->errors()
            ]);
        } catch (\Exception $e) {
            \Log::error('Doctor Availability Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 500,
                'msg' => 'Server Error: ' . $e->getMessage()
            ]);
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
