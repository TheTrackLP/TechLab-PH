<!DOCTYPE html>
<html>
@php
$i = 1;
@endphp

<head>
    <title>Product Sales Report - TechLab PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background: #ffffff;
    }

    .report-container {
        max-width: 1000px;
        margin: auto;
        padding: 40px;
    }

    .report-header h3 {
        font-weight: 700;
    }

    .summary-box {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
    }

    .grand-total {
        font-size: 18px;
        font-weight: 600;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            margin: 0;
        }
    }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="text-center mb-4 report-header">
            <h3>TechLab PH</h3>
            <p class="mb-1 text-muted">Product Sales Report</p>
            <p class="mb-0">
                <strong>From:</strong> {{ date('F j, Y', strtotime($fromDate)) }}
                &nbsp; | &nbsp;
                <strong>To:</strong> {{ date('F j, Y', strtotime($toDate)) }}
            </p>
        </div>
        <hr>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <small class="text-muted">Total Revenue</small>
                    <div class="grand-total">₱
                        {{ number_format($dateFilter_productSale->sum('total_amount'), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <small class="text-muted">Total Profit</small>
                    <div class="grand-total text-success">₱
                        {{ number_format($dateFilter_productSale->sum('total_profit'), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <small class="text-muted">Total Quantity Sold</small>
                    <div class="grand-total">{{ $dateFilter_productSale->sum('total_sold') }} pcs</div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-start">Product</th>
                        <th class="text-center">Qty Sold</th>
                        <th class="text-end">Total Revenue</th>
                        <th class="text-end">Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dateFilter_productSale as $data)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ $data->name }}</td>
                        <td class="text-center">{{ $data->total_sold }}</td>
                        <td class="text-end">₱ {{ number_format($data->total_amount, 2) }}</td>
                        <td class="text-end text-success">₱ {{ number_format($data->total_profit, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="text-center mt-4">
            <small class="text-muted">
                Generated on February 28, 2026
            </small>
        </div>
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-dark px-4">
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