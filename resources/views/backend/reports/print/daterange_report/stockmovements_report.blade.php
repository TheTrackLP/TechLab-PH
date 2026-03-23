<!DOCTYPE html>
<html lang="en">
@php
$i = 1;
@endphp

<head>
    <meta charset="UTF-8">
    <title>Stock Movement Report</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    /* LANDSCAPE PRINT */
    @page {
        size: A4 landscape;
        margin: 15mm;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 4px !important;
        }
    }

    body {
        font-size: 13px;
    }

    .report-title {
        font-size: 22px;
        font-weight: 600;
    }

    .badge-sale {
        background-color: #dc3545;
    }

    .badge-restock {
        background-color: #198754;
    }

    .badge-adjustment {
        background-color: #ffc107;
        color: #000;
    }

    .header-line {
        border-top: 2px solid #000;
        margin: 10px 0 20px 0;
    }
    </style>
</head>

<body>

    <div class="container-fluid mt-3">

        <!-- HEADER -->
        <div class="row align-items-center">
            <div class="col-8">
                <div class="report-title">TechLab PH</div>
                <div>Inventory System</div>
                <div class="fw-semibold">Stock Movement Report</div>
            </div>
            <div class="col-4 text-end">
                <div><strong>Date Range:</strong> {{ date('F d, Y', strtotime($fromDate)) }} -
                    {{ date('F d, Y', strtotime($toDate)) }}</div>
                <div><strong>Movement Type:</strong> {{ ucfirst($type) }}</div>
                <div><strong>Generated:</strong> Feb 21, 2026</div>
            </div>
        </div>

        <div class="header-line"></div>

        <!-- PRINT BUTTON -->
        <div class="text-end mb-2 no-print">
            <button onclick="window.print()" class="btn btn-dark btn-sm">
                🖨 Print
            </button>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Type</th>
                        <th>Reference No</th>
                        <th>Performed By</th>
                        <th class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($StockMovements as $data)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td class="text-center">{{ date('M d, Y H:i:s A', strtotime($data->created_at)) }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->sku }}</td>
                        <td class="text-center">
                            @if($data->type == 'sale')
                            <span class="badge bg-danger">Sale</span>
                            @elseif($data->type == 'restock')
                            <span class="badge bg-success">Restock</span>
                            @elseif($data->type == 'adjustment')
                            <span class="badge bg-warning">Adjustment</span>
                            @elseif($data->type == 'return')
                            <span class="badge bg-primary">Return</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $data->reference_no }}</td>
                        <td class="text-center">Admin</td>
                        <td class="text-center text-danger fw-bold">
                            @if($data->type == 'sale')
                            <span class="text-danger fw-bold">{{ $data->quantity }}</span>
                            @elseif($data->type == 'restock')
                            <span class="text-success fw-bold">{{ $data->quantity }}</span>
                            @elseif($data->type == 'return')
                            <span class="text-primary fw-bold">{{ $data->quantity }}</span>
                            @elseif($data->type == 'adjustment')
                            <span class="text-warning fw-bold">{{ $data->quantity }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-4">
            <div class="col-6">
                <div class="border p-3 rounded">
                    <div><strong>Total Sale Movements:</strong> {{ $totalSale }}</div>
                    <div><strong>Total Restock Movements:</strong> {{ $totalRestock }}</div>
                    <div><strong>Total Adjustments:</strong> {{ $totalAdjustment }}</div>
                    <div><strong>Total Returns:</strong> {{ $totalReturn }}</div>
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="border p-3 rounded">
                    <div class="fs-5">
                        <strong>Net Movement:</strong>
                        <span class="text-success fw-bold">{{ $netMovement }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>