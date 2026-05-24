<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
                'type'  => 'required|in:doctor,hospital',
                'id'    => 'required|integer',
            ]);

            $type     = $request->type;
            $entityId = (int)$request->id;
            $file     = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $folder   = "{$type}_gallery";

            if (app()->environment('local')) {
                $localFolder = public_path("uploads/{$folder}");
                if (!file_exists($localFolder)) mkdir($localFolder, 0755, true);
                $file->move($localFolder, $filename);
                $url = asset("uploads/{$folder}/{$filename}");
            } else {
                $file->storeAs("uploads/{$folder}", $filename, 'public');
                $url = asset("storage/uploads/{$folder}/{$filename}");
            }

            $table  = $type === 'doctor' ? 'doctor_images' : 'hospital_images';
            $column = $type === 'doctor' ? 'doctor_id' : 'hospital_id';

            $insertData = [$column => $entityId, 'image' => $filename];

            // Add timestamps only if columns exist
            $columns = array_column(DB::select("SHOW COLUMNS FROM `{$table}`"), 'Field');
            if (in_array('created_at', $columns)) {
                $insertData['created_at'] = now();
                $insertData['updated_at'] = now();
            }

            $id = DB::table($table)->insertGetId($insertData);

            return response()->json([
                'status'  => 200,
                'id'      => $id,
                'url'     => $url,
                'message' => 'Image uploaded successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id'   => 'required|integer',
                'type' => 'required|in:doctor,hospital',
            ]);

            $type   = $request->type;
            $table  = $type === 'doctor' ? 'doctor_images' : 'hospital_images';
            $record = DB::table($table)->where('id', $request->id)->first();

            if (!$record) {
                return response()->json(['status' => 404, 'message' => 'Image not found.'], 404);
            }

            $filePath = public_path("uploads/{$type}_gallery/{$record->image}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            DB::table($table)->where('id', $request->id)->delete();

            return response()->json(['status' => 200, 'message' => 'Image deleted successfully.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function images(Request $request)
    {
        try {
            $request->validate([
                'id'   => 'required|integer',
                'type' => 'required|in:doctor,hospital',
            ]);

            $type   = $request->type;
            $table  = $type === 'doctor' ? 'doctor_images' : 'hospital_images';
            $column = $type === 'doctor' ? 'doctor_id' : 'hospital_id';

            $images = DB::table($table)
                ->where($column, (int)$request->id)
                ->get()
                ->map(function ($img) use ($type) {
                    $img->url = app()->environment('local')
                        ? asset("uploads/{$type}_gallery/{$img->image}")
                        : asset("storage/uploads/{$type}_gallery/{$img->image}");
                    return $img;
                });

            return response()->json(['status' => 200, 'images' => $images]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}
