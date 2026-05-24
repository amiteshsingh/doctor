<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with(['specializations.specialization'])
            ->where('status', 1)
            ->where('approval_status', 1);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->whereHas('specialization', function ($sp) use ($request) {
                    $sp->where('name', 'like', '%' . $request->specialization . '%');
                });
            });
        }

        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('zip_code')) {
            $query->where('zip_code', 'like', '%' . $request->zip_code . '%');
        }

        $hospitals = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 200,
            'data'   => $hospitals->items(),
            'meta'   => [
                'current_page' => $hospitals->currentPage(),
                'last_page'    => $hospitals->lastPage(),
                'per_page'     => $hospitals->perPage(),
                'total'        => $hospitals->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $hospital = Hospital::with(['specializations.specialization'])
            ->where('status', 1)
            ->where('approval_status', 1)
            ->findOrFail($id);

        $gallery = \DB::table('hospital_images')
            ->where('hospital_id', $id)
            ->get()
            ->map(fn($img) => [
                'id'  => $img->id,
                'url' => asset('uploads/hospital_gallery/' . $img->image),
            ]);

        return response()->json([
            'status'  => 200,
            'data'    => $hospital,
            'gallery' => $gallery,
        ]);
    }
}
