<!DOCTYPE html>
<html>
<head>
    <title>Prescription Invoice</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h2>Prescription Invoice</h2>
</div>

<table>
    <tr>
        <th>Invoice Number</th>
        <td>{{ $invoice->invoice_number }}</td>
    </tr>
    <tr>
        <th>Doctor/Hospital</th>
        <td>{{ $invoice_master->doctor->name ?? '' }} / {{ $invoice_master->hospital_clinic_name }}</td>
    </tr>
    <tr>
        <th>Consultation Fee</th>
        <td>₹ {{ number_format($invoice_master->consultation_fee, 2) }}</td>
    </tr>
    <tr>
        <th>Patient Name</th>
        <td>{{ $invoice->patient_name }}</td>
    </tr>
    <tr>
        <th>Patient Address</th>
        <td>{{ $invoice->patient_address }}</td>
    </tr>
    <tr>
        <th>Patient Phone</th>
        <td>{{ $invoice->patient_phone_no }}</td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ $invoice->created_at }}</td>
    </tr>
</table>

</body>
</html>
