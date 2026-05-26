<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrescriptionInvoice;
use App\Models\InvoiceMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\FirebaseNotification;
use App\Models\User;
use App\Models\Doctor;
use App\Models\UserDoctorRoleMembership;
use PDF;
use Illuminate\Support\Facades\Auth;

class PrescriptionInvoiceController extends Controller
{
    /**
     * Send FCM notification to booking's user
     */
    private function notifyUser(PrescriptionInvoice $inv, string $title, string $body, array $data = []): void
    {
        if (!$inv->user_id) return;
        $user = User::find($inv->user_id);
        if (!$user || !$user->fcm_token) return;
        FirebaseNotification::send($user->fcm_token, $title, $body, $data);
    }

    public function index(Request $request)
    {
        $mem = UserDoctorRoleMembership::where('user_id', Auth::id())->first();
        if (!$mem || !$mem->invoice_permission) {
            return view('doctor.prescription_invoice.index', [
                'result'  => [],
                'title'   => 'Prescription Invoice List',
                'blocked' => true,
            ]);
        }

        try {
            $page = 1;
            $page_size = 10;
            $filter = [];
            $result = [];

            if ($request->isMethod('post') && $request->ajax() && $request->session()->has('user_id')) {
                $filter = $request->all();
                $page = isset($filter['page']) ? $filter['page'] : $page;

                $query = PrescriptionInvoice::with('invoiceMaster.doctor')
                    ->whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    });

                if (!empty($filter['filter_date'])) {
                    $query->whereDate('booking_date', $filter['filter_date']);
                }
                if (!empty($filter['search'])) {
                    $s = $filter['search'];
                    $query->where(function($q) use ($s) {
                        $q->where('invoice_number', 'like', "%$s%")
                          ->orWhere('patient_name', 'like', "%$s%")
                          ->orWhere('patient_phone_no', 'like', "%$s%");
                    });
                }

                $total   = $query->count();
                $records = $query->orderBy('id', 'DESC')->skip(($page-1)*$page_size)->take($page_size)->get();

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
                    ->whereDate('booking_date', today())
                    ->orderBy('id', 'DESC')
                    ->skip(($page - 1) * $page_size)
                    ->take($page_size)
                    ->get();

                $result['total_count'] = PrescriptionInvoice::whereHas('invoiceMaster', function($q) use ($request) {
                        $q->where('added_by', $request->session()->get('user_id'));
                    })->whereDate('booking_date', today())->count();
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
                        $inv = PrescriptionInvoice::find($data['id']);
                        if ($inv) {
                            $date = \Carbon\Carbon::parse($data['booking_date'])->format('d M Y');
                            $time = $data['booking_time'];
                            $this->notifyUser(
                                $inv,
                                '✏️ अपॉइंटमेंट अपडेट',
                                "आपकी अपॉइंटमेंट बदलकर {$date} को {$time} कर दी गई है। कृपया समय पर अस्पताल/क्लिनिक पहुँचें।",
                                ['type' => 'update', 'invoice_id' => (string)$data['id']]
                            );
                        }
                        return response()->json(["status" => 200, "msg" => "Prescription invoice updated successfully."]);
                    } else {
                        return response()->json(["status" => 403, "msg" => "Prescription invoice not updated."]);
                    }
                } else {
                    // Duplicate slot check (exclude cancelled)
                    $alreadyBooked = DB::table('prescription_invoice')
                        ->where('invoice_master_id', $data['invoice_master_id'])
                        ->where('booking_date', $data['booking_date'])
                        ->whereRaw('LOWER(booking_time) = ?', [strtolower($data['booking_time'] ?? '')])
                        ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
                        ->exists();

                    if ($alreadyBooked) {
                        return response()->json(["status" => 409, "msg" => "This slot is already booked. Please select another time slot."]);
                    }

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

    public function getSlots(Request $request)
    {
        $invoiceMasterId = $request->invoice_master_id;
        $date            = $request->booking_date;

        $master = InvoiceMaster::find($invoiceMasterId);
        if (!$master || !$master->start_time || !$master->end_time_slot || !$master->duration_time_slot) {
            return response()->json(['status' => 404, 'slots' => []]);
        }

        // Generate all slots — store as minutes from midnight for easy comparison
        $startMin = (int)date('H', strtotime($master->start_time)) * 60 + (int)date('i', strtotime($master->start_time));
        $endMin   = (int)date('H', strtotime($master->end_time_slot)) * 60 + (int)date('i', strtotime($master->end_time_slot));
        $duration = (int) $master->duration_time_slot;
        $allSlots = [];
        for ($m = $startMin; $m + $duration <= $endMin; $m += $duration) {
            $h    = intdiv($m, 60);
            $min  = $m % 60;
            $ampm = $h >= 12 ? 'PM' : 'AM';
            $h12  = $h % 12 ?: 12;
            $allSlots[] = ['label' => sprintf('%02d:%02d %s', $h12, $min, $ampm), 'minutes' => $m];
        }

        // Get booked slot labels for this date (exclude cancelled)
        $booked = DB::table('prescription_invoice')
            ->where('invoice_master_id', $invoiceMasterId)
            ->where('booking_date', $date)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->pluck('booking_time')
            ->toArray();
        // Normalize booked times to h:i A format
        $bookedLabels = array_map(fn($t) => date('h:i A', strtotime($t)), $booked);

        $now        = now();
        $isToday    = ($date === $now->format('Y-m-d'));
        $nowMin     = $isToday ? ($now->hour * 60 + $now->minute) : -1;

        $slots = array_map(function($slot) use ($bookedLabels, $nowMin) {
            $isBooked = in_array($slot['label'], $bookedLabels);
            return [
                'time'    => $slot['label'],
                'minutes' => $slot['minutes'],
                'booked'  => $isBooked,
            ];
        }, $allSlots);

        return response()->json([
            'status'  => 200,
            'slots'   => $slots,
            'isToday' => ($date === now()->format('Y-m-d')),
        ]);
    }

    public function newBookingCount(Request $request)
    {
        $since = $request->query('since'); // timestamp
        $userId = Session::get('user_id');

        $query = PrescriptionInvoice::whereHas('invoiceMaster', function($q) use ($userId) {
            $q->where('added_by', $userId);
        });

        if ($since) {
            $query->where('created_at', '>', date('Y-m-d H:i:s', $since));
        }

        $newBookings = $query->with('invoiceMaster.doctor')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($b) => [
                'id'           => $b->id,
                'patient_name' => $b->patient_name,
                'booking_date' => $b->booking_date,
                'booking_time' => $b->booking_time,
                'doctor'       => $b->invoiceMaster?->doctor?->name ?? 'N/A',
            ]);

        return response()->json([
            'count'    => $newBookings->count(),
            'bookings' => $newBookings,
            'now'      => time(),
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $invoice = PrescriptionInvoice::find($id);
        if (!$invoice) {
            return response()->json(['status' => 404, 'msg' => 'Not found']);
        }

        $invoice->status     = 'cancelled';
        $invoice->updated_at = now();
        $invoice->save();

        $date = \Carbon\Carbon::parse($invoice->booking_date)->format('d M Y');
        $this->notifyUser(
            $invoice,
            '❌ अपॉइंटमेंट रद्द',
            "{$date} को {$invoice->booking_time} बजे की आपकी अपॉइंटमेंट डॉक्टर द्वारा रद्द कर दी गई है। यह स्लॉट अब बुकिंग के लिए उपलब्ध है।",
            ['type' => 'cancel', 'invoice_id' => (string)$invoice->id]
        );

        return response()->json(['status' => 200, 'msg' => 'Appointment cancelled successfully.']);
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
