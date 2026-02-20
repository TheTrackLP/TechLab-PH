<!DOCTYPE html>
<html>
@php
$i = 1;
$curr_date = date('F j, Y');
@endphp

<head>
    <title>Date Range Sales Report - TechLab PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background: #ffffff;
    }

    .report-container {
        max-width: 1000px;
        margin: auto;
        padding: 30px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">TechLab PH</h3>
            <div class="text-muted">Sales Date Range Report</div>
            <div class="mt-2">
                <strong>From:</strong> {{ date('F j, Y', strtotime($fromDate)) }}
                &nbsp; | &nbsp;
                <strong>To:</strong> {{ date('F j, Y', strtotime($toDate)) }}
            </div>
        </div>
        <hr>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3">
                    <strong>Total Revenue:</strong> ₱{{ number_format($dateFilter->sum('total_amount'),2 ) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3">
                    <strong>Total Profit:</strong> ₱{{ number_format($dateFilter->sum('total_profit'), 2) }}
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Invoice</th>
                        <th class="text-center">Date</th>
                        <th class="text-center" class="text-end">Total</th>
                        <th class="text-center" class="text-end">Profit</th>
                        <th class="text-center">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dateFilter as $date)
                    <tr>
                        <td class="align-middle">{{ $i++ }}</td>
                        <td class="align-middle">{{ $date->invoice_no }}</td>
                        <td class="text-center align-middle">{{ date('F j, Y', strtotime($date->completed_at)) }}</td>
                        <td class="text-end align-middle">₱{{ number_format($date->total_amount, 2) }}</td>
                        <td class="text-end align-middle">₱{{ number_format($date->total_profit, 2) }}</td>
                        <td class="text-center align-middle">{{ $date->payment_type }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center mt-5">
            <small class="text-muted">Generated on {{ $curr_date }}</small>
        </div>
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-dark">
                Print Report
            </button>
        </div>
    </div>
    <script>
    window.onload = function() {
        window.print();
    };
    </script>
</body>

</html>