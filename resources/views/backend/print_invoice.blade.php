<!DOCTYPE html>
<html>

<head>
    <title>Invoice - TechLab PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: #ffffff;
    }

    .invoice-container {
        max-width: 900px;
        margin: auto;
        padding: 50px 30px;
    }

    .invoice-header h2 {
        font-weight: 700;
    }

    .table thead {
        border-bottom: 2px solid #000;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .totals-section {
        font-size: 18px;
    }

    .grand-total {
        font-size: 22px;
        font-weight: bold;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            margin: 0;
        }
    }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="invoice-header">
                <h2 class="mb-0">TechLab PH</h2>
                <small class="text-muted">Computer Services & Parts Center</small>
            </div>
            <div class="text-end">
                <h5 class="fw-bold mb-1">INVOICE</h5>
                <div>Invoice No: <strong>{{ $invoice->invoice_no }}</strong></div>
                <div>Date: February 19, 2026 - 1:30 PM</div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="text-muted">Customer</div>
                <div class="fw-semibold fs-5">Walk-in Customer</div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="text-muted">Payment Type</div>
                <div class="fw-semibold fs-5">Cash</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="fw-semibold">Kingston DDR4 8GB</td>
                        <td class="text-center">2</td>
                        <td class="text-end">₱1,200.00</td>
                        <td class="text-end fw-semibold">₱2,400.00</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="fw-semibold">Logitech Mouse</td>
                        <td class="text-center">1</td>
                        <td class="text-end">₱600.00</td>
                        <td class="text-end fw-semibold">₱600.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr class="my-4">
        <div class="row justify-content-end">
            <div class="col-md-5 totals-section">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total</span>
                    <span class="fw-semibold">₱3,000.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Amount Paid</span>
                    <span>₱3,000.00</span>
                </div>
                <div class="d-flex justify-content-between grand-total">
                    <span>Change</span>
                    <span>₱0.00</span>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <p class="text-muted">Thank you for choosing TechLab PH.</p>
        </div>
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-success px-4 me-2">
                Print
            </button>
            <button onclick="window.close()" class="btn btn-outline-danger px-4">
                Close
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