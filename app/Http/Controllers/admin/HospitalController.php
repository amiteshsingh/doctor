<?php

namespace App\Http\Controllers\admin;
use App\Models\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class HospitalController extends Controller
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
                $records = Hospital::getResult($page, $page_size, $filter);
                $total = Hospital::getTotalResult($filter);
                $content_html =  view('admin.hospital.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $pagination_html = view('pagination.pagination')->with(['url'=> 'hospital', 'recTotal' => $total, 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'hospital'])->render();
                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';
                return response()->json($result);

            }else{
                $records = Hospital::getResult($page,$page_size, $filter);
                $result['total_count'] = Hospital::getTotalResult($filter);
                $result['page'] = $page;
                $result['page_size'] = $page_size;
                $pagination_html = view('pagination.pagination')->with(['url'=> 'hospital', 'recTotal' => $result['total_count'], 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'hospital'])->render();
                $result['pagination_html'] = $pagination_html;
                $content_html =  view('admin.hospital.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $result['content_html'] = $content_html;
            }
        }catch(\Exception $e){
            var_dump($e->getMessage()); die;
            return redirect()->back()->withError('Something went wrong');
        }
        $title = "Hospital List";
        return view('admin.hospital.index', compact('result', 'title')); 


        // return view('admin.hospital.index');
    }


    public function add(Request $request){
        // echo "Hospital add";

        $data = $request->all();
        if($request->isMethod('post') && $request->ajax()){
            try{
                $request->validate([
                    'name' => 'required',
                    'phone_no' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'zip_code' => 'required|numeric',
                    'status' => 'required',
                    'approval_status' => 'required',
                ]);
                
                if(isset($data['id']) && $data['id'] !=""){
                     //Update hospital
                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        // $image->move(public_path('uploads/hopital'), $imageName);
                        $image->storeAs('upload/hopital', $imageName, 'public');
                        $update['image'] = $imageName;
                    }
                   
                    $update['name'] = $data['name'];
                    $update['phone_no'] = $data['phone_no'];
                    $update['email'] = $data['email'];
                    $update['address'] = $data['address'];
                    $update['city'] = $data['city'];
                    $update['state'] = $data['state'];
                    $update['zip_code'] = $data['zip_code'];
                    $update['status'] = $data['status'];
                    $update['approval_status'] = $data['approval_status'];
                    $update['latitude'] = $data['latitude'];
                    $update['longitude'] = $data['longitude'];
                    $update['updated_on'] = date('Y-m-d H:i:s');
                    $update['updated_by'] = Session::get('user_id');
                    
                   
                    if(DB::table('hospitals')->where('id', $data['id'])->update($update)){
                        return response()->json(["status"=>200,"msg"=>"Hospitals updated successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'hospitals not updated.']);
                    }
                }else{
                    //Save hospital 
                    $hospital = new Hospital;

                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                        // $image->move(public_path('uploads/hopital'), $imageName);
                        $image->storeAs('upload/hopital', $imageName, 'public');
                        $doctor->image = $imageName;
                    }
                    $hospital->name = isset($data['name'])?$data['name']:'';
                    $hospital->phone_no = isset($data['phone_no'])?$data['phone_no']:'';
                    $hospital->address = isset($data['address'])?$data['address']:'';
                    $hospital->email = isset($data['email'])?$data['email']:'';
                    $hospital->city = isset($data['city'])?$data['city']:'';
                    $hospital->state = isset($data['state'])?$data['state']:'';
                    $hospital->zip_code = isset($data['zip_code'])?$data['zip_code']:'';
                    // $hospital->latitude = isset($data['latitude'])?$data['latitude']:'';
                    // $hospital->longitude = isset($data['longitude'])?$data['longitude']:'';
                    $hospital->status = isset($data['status'])?$data['status']:'';
                    $hospital->approval_status = isset($data['approval_status'])?$data['approval_status']:'';
                    $hospital->added_on = date('Y-m-d H:i:s');
                    $hospital->added_by = Session::get('user_id');
                    $hospital->updated_by = Session::get('user_id');

                    
                    if($hospital->save()){
                        return response()->json(["status"=>200,"msg"=>"Hospitals saved successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Invalid request']);
                    }
                }
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }
            
        }else{
            $hospital = (object)[];
            $selected_specializations = [];
            if(isset($data['id']) && !empty($data['id'])){
                $id = $data['id'];
                $hospital = Hospital::find($id);

                $selected_specializations = DB::table('hospital_specializations')
                ->where('hospital_id', $data['id'])
                ->pluck('specialization_id')
                ->toArray();
                $hospital->specialization_data = $selected_specializations;
            }
            $specializations = DB::table('specializations')->where('status', 1)->get()->toArray();
            return view('admin.hospital.add', compact('hospital', 'specializations'));
        }
    }

    public function hospitalSpecializations(Request $request){

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
                    return response()->json(["status"=>403,"msg"=>"Invalid hospital id."]);
                }
                $hospital_specialization = DB::table('hospital_specializations')->where('hospital_id', $data['id'])->delete();
                if(isset($data['specialization_ids']) && !empty($data['specialization_ids'])){
                    foreach($data['specialization_ids'] as $spec){
                        $insert = [
                            'hospital_id' => $data['id'],
                            'specialization_id' => $spec,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        DB::table('hospital_specializations')->insert($insert);
                    }
                }
                return response()->json(["status"=>200,"msg"=>"Hospital specializations updated successfully.", "hospital_id" => $data['id']]);
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }  
        }
    }


    public function delete(Request $request, $id){
        if(empty(Session::get('user_id'))){
			return redirect('/');
		}
        $data = DB::table('hospitals')->where('id','=',$id)->delete();
        $request->session()->flash('msg','hospital delete successfully.');
        return redirect('admin/hospital');  
    }
}
