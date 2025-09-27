<!DOCTYPE html>
<html>
<head>
    <title>Prescription Purja</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            margin: 0px;
            background: #f9f9f9;
        }
        .purja-box {
            border: 2px solid #007bff;
            margin: auto;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #007bff;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            font-size: 14px;
            color: #555;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-details td {
            padding: 10px;
            font-size: 14px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .invoice-details tr:nth-child(even) {
            background: #f8faff;
        }
        .invoice-details strong {
            color: #333;
        }
        .footer {
            text-align: center;
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 13px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="purja-box">

    <!-- Header -->
    <div class="header">
        <h2>{{ $invoice_master->hospital_clinic_name }}</h2>
        <p>123 Health Street, Gurugram</p>
        <p>Phone: +91-9876543210 | Email: info@hospital.com</p>
    </div>

    <!-- Invoice / Purja Details -->
    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</td>
                <td><strong>Date:</strong> {{ $invoice->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Doctor:</strong> {{ $invoice_master->doctor->name ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Patient Name:</strong> {{ $invoice->patient_name }}</td>
                <td><strong>Age / Gender:</strong> {{ $invoice->age }} / {{ $invoice->gender }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong> {{ $invoice->patient_phone_no }}</td>
                <td><strong>Address:</strong> {{ $invoice->patient_address }}</td>
            </tr>
        </table>
    </div>
    <!-- Prescription Details -->
    <div class="prescription-details" style="height:672px; overflow:auto; margin-bottom:20px; position: relative;">

    <!-- Your content goes here -->

    <!-- Watermark -->
    <div style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 50px;
            color: rgba(0,0,0,0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        ">
            {{ $invoice_master->hospital_clinic_name }}
        </div>

        <!-- Actual content should have z-index > 1 -->
        <div style="position: relative; z-index: 1;">
            <!-- Your table or prescription content -->
        </div>

    </div>


    <!-- Footer -->
    <div class="footer">
        <div style="text-align:center;">
            <span style="display:inline-block; padding:8px 20px; border-radius:5px; font-weight:bold; text-decoration:none;">
                Thank you for visiting. Get well soon!
            </span>

            
        </div>
    </div>

</div>
    <span style="font-size:11px; color:#777; float:right;">
        Powered by <strong>rogisewa.com</strong>
    </span>

</body>
</html>
