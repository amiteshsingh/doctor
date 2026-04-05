<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Doctor;

class InvoiceMasterController extends Controller
{
    /**
     * List invoices (with AJAX + pagination support)
     */
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

                $records = InvoiceMaster::join('doctors', 'doctors.id', '=', 'invoice_master.doctor_id')
                    ->where('doctors.added_by', $request->session()->get('user_id'))
                    ->orderBy('invoice_master.id', 'DESC')
                    ->skip(($page - 1) * $page_size)
                    ->take($page_size)
                    ->select('invoice_master.*', 'doctors.name as doctor_name')
                    ->get();

                $total = InvoiceMaster::count();

                $content_html = view('doctor.invoice_master.list-content')
                    ->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])
                    ->render();

                $pagination_html = view('pagination.pagination')
                    ->with([
                        'url' => 'invoice-master',
                        'recTotal' => $total,
                        'pageSize' => $page_size,
                        'curPage' => $page,
                        'filterAjax' => 'ajaxSearching',
                        'filterType' => 'invoice-master'
                    ])->render();

                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';

                return response()->json($result);
            } else {
                $records = InvoiceMaster::join('doctors', 'doctors.id', '=', 'invoice_master.doctor_id')
                    ->where('doctors.added_by', $request->session()->get('user_id'))
                    ->orderBy('invoice_master.id', 'DESC')
                    ->skip(($page - 1) * $page_size)
                    ->take($page_size)
                    ->select('invoice_master.*', 'doctors.name as doctor_name')
                    ->get();

                $result['total_count'] = InvoiceMaster::count();
                $result['page'] = $page;
                $result['page_size'] = $page_size;

                $pagination_html = view('pagination.pagination')
                    ->with([
                        'url' => 'invoice-master',
                        'recTotal' => $result['total_count'],
                        'pageSize' => $page_size,
                        'curPage' => $page,
                        'filterAjax' => 'ajaxSearching',
                        'filterType' => 'invoice-master'
                    ])->render();

                $result['pagination_html'] = $pagination_html;
                $content_html = view('doctor.invoice_master.list-content')
                    ->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])
                    ->render();

                $result['content_html'] = $content_html;
            }
        } catch (\Exception $e) {
            return redirect()->back()->withError('Something went wrong: ' . $e->getMessage());
        }

        $title = "Invoice Master List";
        return view('doctor.invoice_master.index', compact('result', 'title'));
    }

    /**
     * Add or Update Invoice
     */
    public function add(Request $request)
    {
        $data = $request->all();

        if ($request->isMethod('post') && $request->ajax()) {
            try {
                $request->validate([
                    'doctor_id'            => 'required|integer',
                    'hospital_clinic_name' => 'required|string|max:255',
                    'consultation_fee'     => 'required|numeric',
                    'start_time'           => 'nullable|date_format:H:i',
                    'end_time_slot'        => 'nullable|date_format:H:i',
                    'duration_time_slot'   => 'nullable|integer|min:1',
                ]);

                // Update
                if (isset($data['id']) && $data['id'] != "") {
                    $update['doctor_id']            = $data['doctor_id'];
                    $update['hospital_clinic_name'] = $data['hospital_clinic_name'];
                    $update['consultation_fee']     = $data['consultation_fee'];
                    $update['address']              = $data['address'];
                    $update['phone_no']             = $data['phone_no'];
                    $update['email']                = $data['email'];
                    $update['booking_mode']         = $data['booking_mode'] ?? 'OFFLINE';
                    $update['start_time']           = $data['start_time'] ?? null;
                    $update['end_time_slot']        = $data['end_time_slot'] ?? null;
                    $update['duration_time_slot']   = $data['duration_time_slot'] ?? null;
                    $update['updated_at']           = now();
                    $update['updated_by']           = Session::get('user_id');

                    if (DB::table('invoice_master')->where('id', $data['id'])->update($update)) {
                        return response()->json(["status" => 200, "msg" => "Invoice updated successfully."]);
                    } else {
                        return response()->json(["status" => 403, "msg" => "Invoice not updated."]);
                    }
                } else {
                    // Insert
                    $invoice = new InvoiceMaster;
                    $invoice->doctor_id            = $data['doctor_id'];
                    $invoice->hospital_clinic_name = $data['hospital_clinic_name'];
                    $invoice->consultation_fee     = $data['consultation_fee'];
                    $invoice->address              = $data['address'];
                    $invoice->phone_no             = $data['phone_no'];
                    $invoice->email                = $data['email'];
                    $invoice->booking_mode         = $data['booking_mode'] ?? 'OFFLINE';
                    $invoice->start_time           = $data['start_time'] ?? null;
                    $invoice->end_time_slot        = $data['end_time_slot'] ?? null;
                    $invoice->duration_time_slot   = $data['duration_time_slot'] ?? null;
                    $invoice->created_at           = now();
                    $invoice->updated_at           = now();
                    $invoice->added_by             = Session::get('user_id');
                    $invoice->updated_by           = Session::get('user_id');

                    if ($invoice->save()) {
                        return response()->json(["status" => 200, "msg" => "Invoice saved successfully."]);
                    } else {
                        return response()->json(["status" => 403, "msg" => "Invalid request"]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json(["status" => 402, "msg" => $e->getMessage()]);
            }
        } else {
            $invoice = (object)[];
            if (isset($data['id']) && !empty($data['id'])) {
                $invoice = InvoiceMaster::find($data['id']);
            }
            $doctors = Doctor::where('added_by', auth()->id())->pluck('name', 'id');
            return view('doctor.invoice_master.add', compact('invoice', 'doctors'));
        }
    }

    /**
     * Delete Invoice
     */
    public function delete(Request $request, $id)
    {
        if (empty(Session::get('user_id'))) {
            return redirect('/');
        }

        DB::table('invoice_master')->where('id', '=', $id)->delete();
        $request->session()->flash('msg', 'Invoice deleted successfully.');
        return redirect('doctor/invoice-master');
    }
}
