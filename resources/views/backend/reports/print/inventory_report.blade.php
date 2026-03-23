<!DOCTYPE html>
<html lang="en">
@php
$i = 1;
$curr_date = now();
@endphp

<head>
    <meta charset="UTF-8">
    <title>Inventory Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

    .report-container {
        max-width: 1500px;
        margin: auto;
        padding: 40px;
    }

    .table th,
    .table td {
        padding: 6px;
    }
    </style>
</head>

<body class="p-4">
    <div class="report-container">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-0">TechLab Computer Shop</h4>
            <small>Inventory Report</small><br>
            <small>Date Generated: {{ date('M d, Y', strtotime($curr_date)) }}</small>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th class="text-start">Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Min</th>
                        <th>Cost</th>
                        <th>Stock Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventories as $data)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td class="text-start">{{ $data->name }}</td>
                        <td class="text-center">{{ $data->sku }}</td>
                        <td class="text-center">{{ $data->category_name }}</td>
                        <td class="text-center">{{ $data->stock_quantity }}</td>
                        <td class="text-center">{{ $data->minimum_stock }}</td>
                        <td class="text-end">{{ number_format($data->selling_price, 2) }}</td>
                        <td class="text-end">{{ number_format($data->selling_price * $data->stock_quantity, 2) }}</td>
                        <td class="text-center">
                            @if ($data->stock_quantity == 0)
                            <span class="badge bg-danger">Out of Stock</span>
                            @elseif($data->stock_quantity <= $data->minimum_stock)
                                <span class="badge bg-warning">Low Stocks</span>
                                @elseif($data->stock_quantity > $data->minimum_stock)
                                <span class="badge bg-success">Normal</span>
                                @endif
                        </td>
                    </tr>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <div class="d-flex justify-content-between">
                <small>Prepared by: ____________________</small>
                <small>Signature: ____________________</small>
            </div>
        </div>
        <div class="text-end mt-3 no-print">
            <button class="btn btn-dark" onclick="window.print()">Print</button>
        </div>
    </div>
</body>

</html>