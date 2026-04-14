<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrescriptionInvoice;
use App\Models\InvoiceMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Doctor;
use PDF;

class PrescriptionInvoiceController extends Controller
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

                $records = PrescriptionInvoice::with('invoiceMaster.doctor')
                    ->whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    })
                    ->orderBy('id', 'DESC')
                    ->skip(($page - 1) * $page_size)
                    ->take($page_size)
                    ->get();

                $total = PrescriptionInvoice::whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    })->count();

                $content_html = view('doctor.prescription_invoice.list-content')
                    ->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])
                    ->render();

                $pagination_html = view('pagination.pagination')
                    ->with([
                        'url' => 'prescription-invoice',
                        'recTotal' => $total,
                        'pageSize' => $page_size,
                        'curPage' => $page,
                        'filterAjax' => 'ajaxSearching',
                        'filterType' => 'prescription-invoice'
                    ])->render();

                $result['pagination_html'] = $pagination_html;
                $result['content_html'] = $content_html;
                $result['error'] = 0;
                $result['msg'] = 'Fetch data successfully';

                return response()->json($result);
            } else {
                $records = PrescriptionInvoice::with('invoiceMaster.doctor')
                    ->whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    })
                    ->orderBy('id', 'DESC')
                    ->skip(($page - 1) * $page_size)
                    ->take($page_size)
                    ->get();

                $result['total_count'] = PrescriptionInvoice::whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    })->count();
                $result['page'] = $page;
                $result['page_size'] = $page_size;

                $pagination_html = view('pagination.pagination')
                    ->with([
                        'url' => 'prescription-invoice',
                        'recTotal' => $result['total_count'],
                        'pageSize' => $page_size,
                        'curPage' => $page,
                        'filterAjax' => 'ajaxSearching',
                        'filterType' => 'prescription-invoice'
                    ])->render();

                $result['pagination_html'] = $pagination_html;
                $content_html = view('doctor.prescription_invoice.list-content')
                    ->with(['res' => $records, 'page' => $page, 'page_size' => $page_size])
                    ->render();

                $result['content_html'] = $content_html;
            }
        } catch (\Exception $e) {
            return redirect()->back()->withError('Something went wrong: ' . $e->getMessage());
        }

        $title = "Prescription Invoice List";
        return view('doctor.prescription_invoice.index', compact('result', 'title'));
    }

    public function add(Request $request)
    {
        $data = $request->all();

        if ($request->isMethod('post') && $request->ajax()) {
            try {
                $request->validate([
                    'invoice_master_id' => 'required|integer',
                    'patient_name' => 'required|string|max:255',
                    'patient_address' => 'nullable|string|max:255',
                    'patient_phone_no' => 'nullable|string|max:20',
                ]);

                if (isset($data['id']) && $data['id'] != "") {
                    // Update
                    $update = [
                        'invoice_master_id' => $data['invoice_master_id'],
                        'patient_name' => $data['patient_name'],
                        'patient_address' => $data['patient_address'] ?? '',
                        'patient_phone_no' => $data['patient_phone_no'] ?? '',
                        'age' => $data['age'] ?? '',
                        'gender' => $data['gender'] ?? '',
                        'booking_date' => $data['booking_date'] ?? null,
                        'booking_time' => $data['booking_time'] ?? null,
                        'updated_at' => now(),
                    ];

                    if (DB::table('prescription_invoice')->where('id', $data['id'])->update($update)) {
                        return response()->json(["status" => 200, "msg" => "Prescription invoice updated successfully."]);
                    } else {
                        return response()->json(["status" => 403, "msg" => "Prescription invoice not updated."]);
                    }
                } else {
                    // Insert
                    $now = now(); 
                    $invoice = new PrescriptionInvoice;
                    $invoice->invoice_master_id = $data['invoice_master_id'];
                    $invoiceNumber = 'INV-' . $now->format('YmdHis');
                    $invoice->invoice_number = $invoiceNumber;
                    $invoice->patient_name = $data['patient_name'];
                    $invoice->patient_address = $data['patient_address'] ?? '';
                    $invoice->patient_phone_no = $data['patient_phone_no'] ?? '';
                    $invoice->age = $data['age'] ?? '';
                    $invoice->gender = $data['gender'] ?? '';
                    $invoice->booking_date = $data['booking_date'] ?? null;
                    $invoice->booking_time = $data['booking_time'] ?? null;
                    $invoice->created_at = now();
                    $invoice->updated_at = now();

                    if ($invoice->save()) {
                        return response()->json(["status" => 200, "msg" => "Prescription invoice saved successfully."]);
                    } else {
                        return response()->json(["status" => 403, "msg" => "Invalid request"]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json(["status" => 402, "msg" => $e->getMessage()]);
            }
        } else {
            $prescription = (object)[];
            if (isset($data['id']) && !empty($data['id'])) {
                $prescription = PrescriptionInvoice::find($data['id']);
            }

            // === provide variable name that Blade expects: $invoiceMasters ===
            // (You can use pluck if you just want hospital_clinic_name, or build labels with fee)
            $invoiceMasters = InvoiceMaster::orderBy('hospital_clinic_name')
             ->where('added_by', $request->session()->get('user_id'))
            ->get()
                ->mapWithKeys(function($inv){
                    $doctorName = $inv->doctor->name ?? 'N/A';
                    $label = $doctorName . ' - ' . $inv->hospital_clinic_name . ' (₹' . number_format($inv->consultation_fee, 2) . ')';
                    return [$inv->id => $label];
                })->toArray();

            return view('doctor.prescription_invoice.add', compact('prescription', 'invoiceMasters'));
        }
    }

    public function delete(Request $request, $id)
    {
        if (empty(Session::get('user_id'))) {
            return redirect('/');
        }

        DB::table('prescription_invoice')->where('id', '=', $id)->delete();
        $request->session()->flash('msg', 'Prescription invoice deleted successfully.');
        return redirect('doctor/prescription-invoice');
    }

    public function generatePdf($id)
    {
        // Get the invoice
        $invoice = PrescriptionInvoice::with('invoiceMaster')->find($id);

        if (!$invoice) {
            return redirect()->back()->with('msg', 'Invoice not found.');
        }

        // Prepare data for PDF
        $data = [
            'invoice' => $invoice,
            'invoice_master' => $invoice->invoiceMaster,
        ];

        // Load view and generate PDF
        $pdf = PDF::loadView('doctor.prescription_invoice.pdf', $data);

        // Stream PDF to browser
        return $pdf->stream('Prescription_Invoice_' . $invoice->invoice_number . '.pdf');
    }

}
