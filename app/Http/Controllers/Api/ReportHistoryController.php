<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportHistory;
use Illuminate\Http\Request;

class ReportHistoryController extends Controller {

    // GET /api/v1/report-history
    public function index(Request $request) {
        $reports = ReportHistory::where('user_id', $request->auth_user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id,
                'report_type'   => $r->report_type,
                'patient_info'  => $r->patient_info,
                'summary'       => $r->summary,
                'normal_count'  => $r->normal_count,
                'abnormal_count'=> $r->abnormal_count,
                'sections'      => json_decode($r->sections_json, true),
                'date'          => $r->created_at->format('d M Y, h:i A'),
                'image_url'     => $r->image_path ? url($r->image_path) : null,
            ]);

        return response()->json(['status' => 200, 'data' => $reports]);
    }

    // POST /api/v1/report-history
    public function store(Request $request) {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'report_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/reports'), $filename);
            $imagePath = 'uploads/reports/' . $filename;
        }

        $report = ReportHistory::create([
            'user_id'       => $request->auth_user->id,
            'report_type'   => $request->report_type,
            'patient_info'  => $request->patient_info,
            'summary'       => $request->summary,
            'normal_count'  => $request->normal_count ?? 0,
            'abnormal_count'=> $request->abnormal_count ?? 0,
            'sections_json' => $request->sections ?? '{}',
            'image_path'    => $imagePath,
        ]);

        return response()->json(['status' => 200, 'msg' => 'Saved', 'id' => $report->id]);
    }

    // DELETE /api/v1/report-history/{id}
    public function destroy(Request $request, $id) {
        $report = ReportHistory::where('id', $id)
            ->where('user_id', $request->auth_user->id)
            ->first();

        if (!$report) {
            return response()->json(['status' => 404, 'msg' => 'Not found'], 404);
        }

        $report->delete();
        return response()->json(['status' => 200, 'msg' => 'Deleted']);
    }
}
