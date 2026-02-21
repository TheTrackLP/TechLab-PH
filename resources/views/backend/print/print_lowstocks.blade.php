<!DOCTYPE html>
<html>
@php
$i = 1;
@endphp

<head>
    <title>Low Stock Report - TechLab PH</title>
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
            <p class="mb-1 text-muted">Low Stock Report</p>
            <p class="mb-0">
                <strong>Generated on:</strong> February 28, 2026
            </p>
        </div>
        <hr>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="summary-box">
                    <small class="text-muted">Total Low Stock Items</small>
                    <div class="fw-bold fs-5">
                        {{ $totalLowStocks }} Products
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="summary-box">
                    <small class="text-muted">Out of Stock Items</small>
                    <div class="fw-bold fs-5 text-danger">
                        {{ $totalOutStocks }} Products
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-start">Product</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Minimum Stock</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_low_stocks as $low)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ $low->name }}</td>
                        <td class="text-center">{{ $low->category_name }}</td>
                        <td class="text-center">{{ $low->stock_quantity }}</td>
                        <td class="text-center">{{ $low->minimum_stock }}</td>
                        <td class="text-center">
                            @if($low->stock_quantity == 0)
                            <span class="badge bg-danger">Out of Stock</span>
                            @else
                            <span class="badge bg-warning text-dark">Low Stock</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="text-center mt-4">
            <small class="text-muted">
                This report shows current inventory status below minimum threshold.
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