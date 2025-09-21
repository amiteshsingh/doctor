<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;

use DB;
use Illuminate\Support\Facades\Session;

class SpecializationController extends Controller
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
                $records = Specialization::getResult($page, $page_size, $filter);
                $total = Specialization::getTotalResult($filter);
                $content_html =  view('admin.specialization.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $pagination_html = view('pagination.pagination')->with(['url'=> 'specialization', 'recTotal' => $total, 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'specialization'])->render();
                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';
                return response()->json($result);

            }else{
                $records = Specialization::getResult($page,$page_size, $filter);
                $result['total_count'] = Specialization::getTotalResult($filter);
                $result['page'] = $page;
                $result['page_size'] = $page_size;
                $pagination_html = view('pagination.pagination')->with(['url'=> 'specialization', 'recTotal' => $result['total_count'], 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'specialization'])->render();
                $result['pagination_html'] = $pagination_html;
                $content_html =  view('admin.specialization.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $result['content_html'] = $content_html;
            }
        }catch(\Exception $e){
            var_dump($e->getMessage()); die;
            return redirect()->back()->withError('Something went wrong');
        }
        $title = "Specialization List";
        return view('admin.specialization.index', compact('result', 'title')); 

    }

        public function add(Request $request){

        $data = $request->all();
        if($request->isMethod('post') && $request->ajax()){
            try{
                $request->validate([
                    'name' => 'required',
                    'status' => 'required',
                ]);
                
                if(isset($data['id']) && $data['id'] !=""){
                    //Update specialization
                    $update['name'] = $data['name'];
                    $update['status'] = $data['status'];
                   
                    if(DB::table('specializations')->where('id', $data['id'])->update($update)){
                        return response()->json(["status"=>200,"msg"=>"Specializations updated successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Specializations not updated.']);
                    }
                }else{
                    //Save specialization 
                    $specialization = new Specialization;
                    $specialization->name = isset($data['name'])?$data['name']:'';
                    $specialization->status = isset($data['status'])?$data['status']:'';

                    if($specialization->save()){
                        return response()->json(["status"=>200,"msg"=>"Specialization saved successfully."]);
                    }else{
                        return response()->json(["status"=>403,"msg"=>'Invalid request']);
                    }
                }
            }catch(\Exception $e){
                return response()->json(["status"=>402,"msg"=>$e->getMessage()]); 
            }
            
        }else{
            $specialization = (object)[];
            if(isset($data['id']) && !empty($data['id'])){
                $id = $data['id'];
                $specialization = Specialization::find($id);
            }
            return view('admin.specialization.add', compact('specialization'));
        }
    }

    public function delete(Request $request, $id){
        
        if(empty(Session::get('user_id'))){
			return redirect('/');
		}
        $data = DB::table('specializations')->where('id','=',$id)->delete();
        $request->session()->flash('msg','Specialization delete successfully.');
        return redirect('admin/specialization');  
    }
}
