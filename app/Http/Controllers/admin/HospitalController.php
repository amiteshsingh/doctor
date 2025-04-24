<?php

namespace App\Http\Controllers\admin;
use App\Models\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
                    'pin_code' => 'required',
                    'status' => 'required',
                    'Approval_status' => 'required',
                ]);
                
                if(isset($data['id']) && $data['id'] !=""){
                    //Update hospital
                    $update['name'] = $data['name'];
                    $update['phone_no'] = $data['phone_no'];
                    $update['email'] = $data['email'];
                    $update['address'] = $data['address'];
                    $update['city'] = $data['city'];
                    $update['pin_code'] = $data['pin_code'];
                    $update['state'] = $data['state'];
                    $update['Approval_status'] = $data['Approval_status'];
                    $update['latitude'] = $data['latitude'];
                    $update['longitude'] = $data['longitude'];
                    $update['update_on'] = date('Y-m-d H:i:s');
                    
                   
                    if(DB::table('hospitals')->where('id', $data['id'])->update($update)){
                        return response()->json(["status"=>200,"msg"=>"hospitals updated successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'hospitals not updated.']);
                    }
                }else{
                    //Save hospital 
                    $hospital = new Hospital;
                    $hospital->name = isset($data['name'])?$data['name']:'';
                    $hospital->phone_no = isset($data['phone_no'])?$data['phone_no']:'';
                    $hospital->address = isset($data['naddressame'])?$data['address']:'';
                    $hospital->email = isset($data['email'])?$data['email']:'';
                    $hospital->city = isset($data['city'])?$data['city']:'';
                    $hospital->state = isset($data['state'])?$data['state']:'';
                    $hospital->zip_code = isset($data['zip_code'])?$data['zip_code']:'';
                    $hospital->latitude = isset($data['latitude'])?$data['latitude']:'';
                    $hospital->longitude = isset($data['longitude'])?$data['longitude']:'';
                    $hospital->status = isset($data['status'])?$data['status']:'';
                    $hospital->approval_status = isset($data['approval_status'])?$data['approval_status']:'';
                    $hospital->added_on = date('Y-m-d H:i:s');
                    if($hospital->save()){
                        return response()->json(["status"=>200,"msg"=>"banner saved successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Invalid request']);
                    }
                }
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }
            
        }else{
            $hospital = (object)[];
            if(isset($data['id']) && !empty($data['id'])){
                $id = $data['id'];
                $hospital = Hospital::find($id);
            }
            // dd($hospital); die;
            // $service_categories = DB::table('service_categories')->where('is_active', 1)->get()->toArray();
            return view('admin.hospital.add', compact('hospital'));
        }
    }

    public function delete(Request $request){
        echo "Hospital delete"; die;

        if(empty(Session::get('user_id'))){
			return redirect('/');
		}
        $data = DB::table('hospital')->where('id','=',$id)->delete();
        $request->session()->flash('msg','hospital delete successfully.');
        return redirect('banner');  
    }
}
