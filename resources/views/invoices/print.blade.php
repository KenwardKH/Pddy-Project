<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->InvoiceID }} - {{ config('app.company_name', 'SPL') }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 10mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            background-color: #ffffff;
            width: 100%;
            height: 100%;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 10pt;
            width: 210mm;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 3%
        }
        .invoice-container {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }
        .company-info {
            font-size: 8pt;
            color: #666;
        }
        .invoice-title {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #ffffff;
        }
      
        .invoice-details p {
            margin-bottom: 5px;
            font-size: 9pt;
        }
        .items-table {
            width: 94%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th {
            background-color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        .items-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            background-color: #ffffff;
            border-top: 1px solid #e0e0e0;
        }
        .total-amount {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
        }
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 
            }
            .invoice-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <table width="100%">
            <tr>
                <td width="25%">
                    <div class="company-info">
                    <h2>{{ config('app.company_name', 'Toko Sinar Pelangi') }}</h2>
                    
                    </div>
                </td>
                <td width="40%"></td>
                <td>
                    <p><strong>No.Invoice</strong> </p>
                </td>
                <td width="30%">
                    : {{ $invoice->InvoiceID }}
                </td>
                    
            </tr>
            <tr>
                <td><p>{{ config('app.company_address', 'Jl Bilal No A4, Medan') }}</p></td>
                <td></td>
                <td>
                    <p><strong>Tanggal</strong></p>
                </td>
                <td>
                    : {{ $invoice->InvoiceDate }}
                </td>
            </tr>
        </table>
        <div class="invoice-header">
            
            <div>
                
            </div>
        </div>

        {{-- <div class="invoice-details">
            <div class="invoice-details-left">
                
                <p><strong>Invoice Type:</strong> {{ $invoice->type == 'delivery' ? 'Delivery' : 'Pickup' }}</p>
                
                <p><strong>Customer Contact:</strong> {{ $invoice->customerContact ?? 'N/A' }}</p>
            </div>
            <div class="invoice-details-right">
                
                <p></p>
                <p><strong>Payment Method:</strong> </p>
            </div>
        </div> --}}
        <br>
        <table width="90%">
            <tr width="100%">
                <td><strong>Kepada Yth. :</strong> {{ $invoice->customerName ?? 'N/A' }}</td>
                <td align="right"><strong>Status:</strong> {{ $invoice->deliveryStatus->status ?? $invoice->pickupStatus->status ?? 'Pending' }}</td>
            </tr>
            <tr>
                <td><strong>No.Telepon :</strong>{{ $invoice->customerContact ?? 'N/A' }}</td>
                <td align="right"><strong>Metode Pembayaran: </strong>{{ $invoice->payment_option ?? 'Not Specified' }}</td>
            </tr>
        </table>
        <br>
        <table class="items-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($invoice->invoiceDetails as $detail)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{ $detail->productName }}</td>
                        <td>{{ $detail->Quantity }}</td>
                        <td>Rp{{ number_format($detail->price, 2, ',', '.') }}</td>
                        <td>Rp{{ number_format($detail->Quantity * $detail->price, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total Amount:</td>
                    <td>Rp{{ number_format($invoice->totalAmount, 2, ',', '.') }}.-</td>
                </tr>
            </tbody>
        </table>

      
    </div>
</body>
</html>