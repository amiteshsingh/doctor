<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = DB::table('doctor_medicines')
            ->where('added_by', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('doctor.medicine.index', compact('medicines'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $request->validate([
                'name'   => 'required|string|max:255',
                'status' => 'required',
            ]);

            $data = [
                'added_by'    => Auth::id(),
                'name'        => $request->name,
                'category'    => $request->category,
                'unit'        => $request->unit,
                'stock'       => $request->stock ?? 0,
                'price'       => $request->price ?? 0,
                'description' => $request->description,
                'status'      => $request->status,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if ($request->filled('id')) {
                unset($data['added_by'], $data['created_at']);
                DB::table('doctor_medicines')->where('id', $request->id)->where('added_by', Auth::id())->update($data);
                return response()->json(['status' => 200, 'msg' => 'Medicine updated successfully.']);
            }

            DB::table('doctor_medicines')->insert($data);
            return response()->json(['status' => 200, 'msg' => 'Medicine added successfully.']);
        }

        $medicine = (object)[];
        if ($request->filled('id')) {
            $medicine = DB::table('doctor_medicines')->where('id', $request->id)->where('added_by', Auth::id())->first();
        }

        return view('doctor.medicine.add', compact('medicine'));
    }

    public function delete($id)
    {
        DB::table('doctor_medicines')->where('id', $id)->where('added_by', Auth::id())->delete();
        return redirect()->route('doctor.medicine.index')->with('msg', 'Medicine deleted.');
    }
}
