<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
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
                $records = User::getResult($page, $page_size, $filter);
                $total = User::getTotalResult($filter);
                $content_html =  view('admin.user.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $pagination_html = view('pagination.pagination')->with(['url'=> 'user', 'recTotal' => $total, 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'user'])->render();
                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';
                return response()->json($result);

            }else{
                $records = User::getResult($page,$page_size, $filter);
                $result['total_count'] = User::getTotalResult($filter);
                $result['page'] = $page;
                $result['page_size'] = $page_size;
                $pagination_html = view('pagination.pagination')->with(['url'=> 'user', 'recTotal' => $result['total_count'], 'pageSize' => $page_size, 'curPage' => $page,  'filterAjax' => 'ajaxSearching', 'filterType' => 'user'])->render();
                $result['pagination_html'] = $pagination_html;
                $content_html =  view('admin.user.list-content')->with(['res'=> $records,'page'=>$page, 'page_size' => $page_size])->render();
                $result['content_html'] = $content_html;
            }
        }catch(\Exception $e){
            var_dump($e->getMessage()); die;
            return redirect()->back()->withError('Something went wrong');
        }
        $title = "User List";
        return view('admin.user.index', compact('result', 'title')); 

    }
}
