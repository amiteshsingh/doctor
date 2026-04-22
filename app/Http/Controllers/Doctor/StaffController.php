<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $staff = DB::table('doctor_staff')
            ->where('added_by', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('doctor.staff.index', compact('staff'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $request->validate([
                'name'   => 'required|string|max:255',
                'status' => 'required',
            ]);

            $data = [
                'added_by'     => Auth::id(),
                'name'         => $request->name,
                'role'         => $request->role,
                'phone'        => $request->phone,
                'email'        => $request->email,
                'address'      => $request->address,
                'salary'       => $request->salary,
                'joining_date' => $request->joining_date ?: null,
                'status'       => $request->status,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            if ($request->filled('id')) {
                unset($data['added_by'], $data['created_at']);
                DB::table('doctor_staff')->where('id', $request->id)->where('added_by', Auth::id())->update($data);
                return response()->json(['status' => 200, 'msg' => 'Staff updated successfully.']);
            }

            DB::table('doctor_staff')->insert($data);
            return response()->json(['status' => 200, 'msg' => 'Staff added successfully.']);
        }

        $member = (object)[];
        if ($request->filled('id')) {
            $member = DB::table('doctor_staff')->where('id', $request->id)->where('added_by', Auth::id())->first();
        }

        return view('doctor.staff.add', compact('member'));
    }

    public function delete($id)
    {
        DB::table('doctor_staff')->where('id', $id)->where('added_by', Auth::id())->delete();
        return redirect()->route('doctor.staff.index')->with('msg', 'Staff deleted.');
    }
}
