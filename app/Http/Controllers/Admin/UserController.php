<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = 1;
            $page_size = 10;
            $filter = [];
            $result = [];

            if ($request->isMethod('post') && $request->ajax() && $request->session()->has('user_id')) {
                $filter = $request->all();
                $page = isset($filter['page']) ? $filter['page'] : $page;
                $records = User::getUserResult($page, $page_size, $filter);
                $total   = User::getUserTotalResult($filter);

                $content_html    = view('admin.user.list-content')->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])->render();
                $pagination_html = view('pagination.pagination')->with(['url' => 'user', 'recTotal' => $total, 'pageSize' => $page_size, 'curPage' => $page, 'filterAjax' => 'ajaxSearching', 'filterType' => 'user'])->render();

                $result['pagination_html'] = $pagination_html;
                $result['content_html']    = $content_html;
                $result['error'] = 0;
                $result['msg']   = 'Fetch data successfully';
                return response()->json($result);
            } else {
                $records = User::getUserResult($page, $page_size, $filter);
                $result['total_count'] = User::getUserTotalResult($filter);
                $result['page']      = $page;
                $result['page_size'] = $page_size;

                $pagination_html = view('pagination.pagination')->with(['url' => 'user', 'recTotal' => $result['total_count'], 'pageSize' => $page_size, 'curPage' => $page, 'filterAjax' => 'ajaxSearching', 'filterType' => 'user'])->render();
                $result['pagination_html'] = $pagination_html;
                $content_html = view('admin.user.list-content')->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])->render();
                $result['content_html'] = $content_html;
            }
        } catch (\Exception $e) {
            return redirect()->back()->withError('Something went wrong: ' . $e->getMessage());
        }

        $title = "User List";
        return view('admin.user.index', compact('result', 'title'));
    }

    public function add(Request $request)
    {
        $data = $request->all();

        if ($request->isMethod('post')) {
            try {
                if (!empty($data['id'])) {
                    // Validate for update
                    $validator = \Validator::make($data, [
                        'name'  => 'required|string|max:255',
                        'email' => 'required|email|unique:users,email,' . $data['id'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status' => 422, 'msg' => $validator->errors()->first()]);
                    }

                    $update = [
                        'name'     => $data['name'],
                        'email'    => $data['email'],
                        'phone_no' => $data['phone_no'] ?? '',
                        'gender'   => $data['gender'] ?? '',
                        'address'  => $data['address'] ?? '',
                    ];
                    if (!empty($data['password'])) {
                        $update['password'] = Hash::make($data['password']);
                    }
                    User::where('id', $data['id'])->update($update);
                    return response()->json(['status' => 200, 'msg' => 'User updated successfully.']);

                } else {
                    // Validate for insert
                    $validator = \Validator::make($data, [
                        'name'     => 'required|string|max:255',
                        'email'    => 'required|email|unique:users,email',
                        'password' => 'required|min:6',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status' => 422, 'msg' => $validator->errors()->first()]);
                    }

                    $user = User::create([
                        'name'     => $data['name'],
                        'email'    => $data['email'],
                        'password' => Hash::make($data['password']),
                        'phone_no' => $data['phone_no'] ?? '',
                        'gender'   => $data['gender'] ?? '',
                        'address'  => $data['address'] ?? '',
                    ]);
                    UserRole::create(['user_id' => $user->id, 'role' => 'user']);
                    return response()->json(['status' => 200, 'msg' => 'User added successfully.']);
                }
            } catch (\Exception $e) {
                return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
            }
        }

        $user = (object)[];
        if (!empty($data['id'])) {
            $user = User::find($data['id']);
        }
        return view('admin.user.add', compact('user'));
    }

    public function view($id)
    {
        $user = User::find($id);
        if (!$user) return redirect()->route('admin.user')->with('msg', 'User not found.');
        return view('admin.user.view', compact('user'));
    }

    public function delete(Request $request, $id)
    {
        if (empty(Session::get('user_id'))) return redirect('/');

        DB::table('user_roles')->where('user_id', $id)->delete();
        User::where('id', $id)->delete();

        $request->session()->flash('msg', 'User deleted successfully.');
        return redirect('admin/user');
    }
}
