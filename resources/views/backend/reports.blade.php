@extends('admin.body.header')
@section('admin')

@php
$low_table = 1;
$prod_table = 1;
$date_table = 1;
$stockMovenum = 1;
$returnNum = 1;
$repairNum = 1;
@endphp
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Reports Module</h1>
    <hr>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Sales Reports Filter</h5>
                <small class="text-muted">Applies to Invoice & Product Sales Reports</small>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="fromDate" id="fromDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="toDate" id="toDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Report Type</label>
                    <select class="select2" id="reportType">
                        <option></option>
                        <option value="sales">Sales Report</option>
                        <option value="returns">Returns Report</option>
                        <option value="repairs">Repair Report</option>
                        <option value="inventory">Inventory Report</option>
                        <option value="stock_movements">Stock Movement Report</option>
                        <option value="low_stock">Low Stock Report</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label d-block invisible">Actions</label>
                    <button class="btn btn-dark w-100" onclick="GenerateDateRangeInvoice()">
                        <i class="fa-solid fa-chart-column me-1"></i>
                        Product Sales
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- DAILY SUMMARY -->
    <h5 class="mb-3">Daily Summary</h5>
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 bg-dark">
                <div class="card-body">
                    <p class="text-white small mb-1">Total Revenue Today</p>
                    <h4 class="fw-bold text-white">₱ {{ number_format($totalRevenue, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 bg-success">
                <div class="card-body">
                    <p class="text-white small mb-1">Total Profit Today</p>
                    <h4 class="fw-bold text-white">₱ {{ number_format($totalProfit, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 bg-dark">
                <div class="card-body">
                    <p class="text-white small mb-1">Total Transactions</p>
                    <h4 class="fw-bold text-white">{{ $totalTransactions }}</h4>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-pills mb-3 nav-fill fw-bold justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                data-bs-target="#pills-sale-report" type="button" role="tab" aria-controls="pills-home"
                aria-selected="true">Invoice Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                data-bs-target="#pills-product-sale-report" type="button" role="tab" aria-controls="pills-contact"
                aria-selected="false">Product Sale Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-return-report"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Returns Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-repair-report"
                type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Repair Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-inventory-report"
                type="button" role="tab" aria-controls="pills-home" aria-selected="false">Inventory Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-stock-report"
                type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Stock Movement
                Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                data-bs-target="#pills-lowstock-report" type="button" role="tab" aria-controls="pills-contact"
                aria-selected="false">Low Stock Report</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <!-- Invoice Report -->
        <div class="tab-pane fade show active" id="pills-sale-report" role="tabpanel" aria-labelledby="pills-home-tab"
            tabindex="0">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Invoice Report</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="dataRangeTable">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Profit</th>
                                    <th class="text-center">Payment</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_sales as $sale)
                                <tr>
                                    <td class="text-center">{{ $date_table++ }}</td>
                                    <td>{{ $sale->invoice_no }}</td>
                                    <td class="text-center">{{ date('M d, Y', strtotime($sale->completed_at)) }}</td>
                                    <td class="text-end">₱ {{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="text-end text-success">₱ {{ number_format($sale->total_profit, 2) }}</td>
                                    <td class="text-center"><span
                                            class="badge bg-primary">{{ $sale->payment_type }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-info text-white" id="openInvoiceModal"
                                            value="{{ $sale->id }}"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Sales Report -->
        <div class="tab-pane fade" id="pills-product-sale-report" role="tabpanel" aria-labelledby="pills-contact-tab"
            tabindex="0">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Product Sales Report</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle Datables">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Quantity Sold</th>
                                    <th class="text-center">Total Revenue</th>
                                    <th class="text-center">Total Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product_sale as $data)
                                <tr>
                                    <td class="text-center">{{ $prod_table++ }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td class="text-center">{{ $data->total_sold }}</td>
                                    <td class="text-end">₱ {{ number_format($data->total_amount, 2) }}</td>
                                    <td class="text-end text-success">₱ {{ number_format($data->total_profit, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Return Report -->
        <div class="tab-pane fade" id="pills-return-report" role="tabpanel" aria-labelledby="pills-profile-tab"
            tabindex="0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Returns Report</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle Datables">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Return No</th>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Refund Amount</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returns as $data)
                                <tr>
                                    <td class="text-center">{{ $returnNum++ }}</td>
                                    <td class="text-center">{{ $data->return_no }}</td>
                                    <td class="text-center">{{ $data->invoice_no }}</td>
                                    <td class="text-center">₱{{ number_format($data->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        @php
                                        $reason = str_replace("_", " ", $data->reason)
                                        @endphp
                                        {{ ucwords($reason) }}
                                    </td>
                                    <td class="text-center">{{ date('M d, Y', strtotime($data->created_at)) }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info text-white" value="{{ $data->id }}"
                                            id="openReturnModal">
                                            <i class="fa-solid fa-eye"></i> </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Repair Report -->
        <div class="tab-pane fade" id="pills-repair-report" role="tabpanel" aria-labelledby="pills-contact-tab"
            tabindex="0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Repair Report</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle Datables">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Repair No</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Device</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repairs as $repair)
                                <tr>
                                    <td class="text-center">{{ $repairNum++ }}</td>
                                    <td>{{ $repair->repair_no }}</td>
                                    <td>{{ $repair->customer_name }}</td>
                                    <td class="text-center">
                                        @php
                                        $device_type = str_replace("_", " ", $repair->device_type);
                                        @endphp
                                        {{ ucwords($device_type) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($repair->status == 'pending_diagnosis')
                                        <span class="badge bg-secondary text-white">Pending Diagnosis</span>
                                        @elseif ($repair->status == 'awaiting_approval')
                                        <span class="badge bg-warning text-dark">Awaiting Approval</span>
                                        @elseif ($repair->status == 'in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                        @elseif ($repair->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @elseif ($repair->status == 'released')
                                        <span class="badge bg-dark text-white">Released</span>
                                        @elseif ($repair->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @elseif ($repair->status == 'abandoned')
                                        <span class="badge bg-dark">Abandoned</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $repair->invoice_no }}</td>
                                    <td class="text-end fw-bold">₱ {{ number_format($repair->total_amount, 2) }}</td>
                                    <td class="text-center">{{ date('M d, Y', strtotime($repair->created_at)) }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-info text-white" value="{{ $repair->id }}"
                                            id="openRepairModal">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Inventory Report -->
        <div class="tab-pane fade" id="pills-inventory-report" role="tabpanel" aria-labelledby="pills-home-tab"
            tabindex="0">
            <div class="container mt-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Inventory Report</h5>
                    </div>

                    <div class="card-body">
                        <!-- Summary Section -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <strong>Total Products:</strong> 120
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <strong>Total Inventory Value:</strong> ₱ 245,500.00
                                </div>
                            </div>

                            <div class="col-md-4 text-end">
                                <button class="btn btn-outline-dark">
                                    <i class="fa-solid fa-print"></i> Print Report
                                </button>
                            </div>
                        </div>
                        <!-- Inventory Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">

                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Stock Qty</th>
                                        <th>Minimum Stock</th>
                                        <th>Cost Price</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Ryzen 5 5600</td>
                                        <td>CPU-R5600</td>
                                        <td>Processors</td>
                                        <td class="text-center">12</td>
                                        <td class="text-center">5</td>
                                        <td class="text-end">₱ 7,500.00</td>
                                        <td class="text-end">₱ 90,000.00</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Normal</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>Logitech G102 Mouse</td>
                                        <td>MSE-001</td>
                                        <td>Mouse</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-end">₱ 500.00</td>
                                        <td class="text-end">₱ 1,500.00</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>Brother Ink Black</td>
                                        <td>INK-B001</td>
                                        <td>Printer Ink</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">5</td>
                                        <td class="text-end">₱ 250.00</td>
                                        <td class="text-end">₱ 0.00</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">Out of Stock</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>Kingston 8GB DDR4</td>
                                        <td>RAM-K8GB</td>
                                        <td>RAM</td>
                                        <td class="text-center">20</td>
                                        <td class="text-center">5</td>
                                        <td class="text-end">₱ 900.00</td>
                                        <td class="text-end">₱ 18,000.00</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Normal</span>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stock Movements Report -->
        <div class="tab-pane fade" id="pills-stock-report" role="tabpanel" aria-labelledby="pills-contact-tab"
            tabindex="0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Stock Movements Report</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center Datables">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-start">Product</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Reference</th>
                                    <th class="text-center">Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockMove as $move)
                                <tr>
                                    <td>{{ $stockMovenum++ }}</td>
                                    <td>{{ date('M d, Y', strtotime($move->created_at)) }}</td>
                                    <td class="text-start">{{ $move->name }}</td>
                                    <td>
                                        @if($move->type == 'sale')
                                        <span class="badge bg-danger">Sale</span>
                                        @elseif($move->type == 'restock')
                                        <span class="badge bg-success">Restock</span>
                                        @elseif($move->type == 'adjustment')
                                        <span class="badge bg-warning">Adjustment</span>
                                        @elseif($move->type == 'return')
                                        <span class="badge bg-primary">Return</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($move->type == 'sale')
                                        <span class="text-danger fw-bold">{{ $move->quantity }}</span>
                                        @else
                                        <span class="text-success fw-bold">{{ $move->quantity }}</span>
                                        @endif
                                    </td>
                                    <td>Reference:
                                        <span class="fw-bold">{{ $move->reference_no }}</span>
                                    </td>
                                    <td>Admin</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Low Stock Report -->
        <div class="tab-pane fade" id="pills-lowstock-report" role="tabpanel" aria-labelledby="pills-contact-tab"
            tabindex="0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Low Stock Report</h5>
                        <a class="btn btn-sm btn-outline-dark px-5" href="{{ route('generate.lowStocks') }}">
                            <i class="fa-solid fa-print me-1"></i> Print
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle Datables">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Minimum Stock</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_low_stocks as $low)
                                <tr>
                                    <td class="text-center">{{ $low_table++ }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <div class="fw-semibold">{{ $low->name }}</div>
                                                <small class="text-muted">{{ $low->category_name }} • SKU:
                                                    {{ $low->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Preview Modal -->
<div class="modal fade" id="invoicePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>
                    Invoice Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h2 class="fw-bold mb-0">TechLab PH</h2>
                        <small>Computer Services Center</small><br>
                        <small>Manila, Philippines</small>
                    </div>
                    <div class="col-md-6 text-md-end mb-3">
                        <h2 class="fw-bold">Invoice</h2>
                        <p class="mb-1"><strong>Invoice No:</strong> <span id="invoice_no"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="invoice_date"></span></p>
                        <p class="mb-0"><strong>Status:</strong>
                            <span class="badge bg-success" id="invoice_status"></span>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Customer:</strong> Walk-in Customer
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="itemsProductBody">

                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Total:</th>
                                <td class="text-end fw-bold">₱<span id="invoice_total"></span></td>
                            </tr>
                            <tr>
                                <th>Amount Paid:</th>
                                <td class="text-end">₱<span id="invoice_paid"></span></td>
                            </tr>
                            <tr>
                                <th>Change:</th>
                                <td class="text-end">₱<span id="invoice_change"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <small class="text-muted">Thank you for your business!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger px-5" data-bs-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-success px-5" onclick="PrintInvoice()">
                    Print
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Return Preview Modal -->
<div class="modal fade" id="viewReturnModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-rotate-left me-2"></i>
                    Return Details
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1 fw-bold">Return No: <span id="return_no"></span></h5>
                        <small class="text-muted">Linked Invoice: <span id="invoice_no"></span></small>
                    </div>
                    <span class="badge bg-danger fs-6">
                        Returned
                    </span>
                </div>
                <hr>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Return Date</small>
                            <div class="fw-semibold"><span id="return_date"></span></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Reason</small>
                            <div class="fw-semibold"><span id="return_reason"></span></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Processed By</small>
                            <div class="fw-semibold">null</div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">#</th>
                                <th class="text-start">Product</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="returnTable">

                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <strong>Return Notes</strong>
                            <p class="mb-0 text-muted" id="return_notes">.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-end">
                            <div class="mb-1">
                                <strong>Total Items:</strong> <span id="totalItems"></span>
                            </div>
                            <div class="fs-5">
                                <strong>Total Refund:</strong>
                                <span class="fw-bold text-danger" id="totalAmount"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Repair Preview Modal -->
<div class="modal fade" id="viewRepairModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-screwdriver-wrench me-2"></i>
                    Repair Details
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Repair No: <span id="repair_no"></span></h5>
                        <div class="small text-muted">
                            <div>Invoice: <span class="fw-semibold" id="repairSale_no"></span></div>
                        </div>
                    </div>
                    <span class="badge bg-warning fs-6">
                        In Progress
                    </span>
                </div>
                <hr>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Customer Name</small>
                            <div class="fw-semibold" id="repairCustomerName"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Device Type</small>
                            <div class="fw-semibold" id="repairDevice_type"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Date Received</small>
                            <div class="fw-semibold" id="repairDateCreated"></div>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 mb-4">
                    <strong>Diagnosis</strong>
                    <p class="mb-0 text-muted">
                        Motherboard and PSU are fried due to power surge.
                        Recommended replacement of PSU and motherboard.
                    </p>
                </div>
                <h6 class="mb-3">Parts Used</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">#</th>
                                <th class="text-start">Product</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-start">Corsair 650W PSU</td>
                                <td class="text-center">1</td>
                                <td class="text-end">₱2,500</td>
                                <td class="text-end">₱2,500</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="text-start">ASUS B450 Motherboard</td>
                                <td class="text-center">1</td>
                                <td class="text-end">₱4,000</td>
                                <td class="text-end">₱4,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <strong>Repair Notes</strong>
                            <p class="text-muted mb-0">
                                Customer approved replacement of damaged components.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-end">
                            <div class="mb-2">
                                <strong>Labor Fee:</strong>
                                <span>₱500</span>
                            </div>
                            <div class="mb-2">
                                <strong>Parts Total:</strong>
                                <span>₱6,500</span>
                            </div>
                            <hr>
                            <div class="fs-5">
                                <strong>Total Amount:</strong>
                                <span class="fw-bold text-success">₱7,000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function GenerateDateRangeReports() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const reportType = document.getElementById('reportType').value;
    if (!fromDate || !toDate && reportType) {
        toastr.error("Error, Both Date are empty!");
        return;
    }
    console.log(fromDate);
    console.log(toDate);
    console.log(reportType);

}

function printNewWindow(url) {
    const printWindow = window.open(url, '_blank');
    printWindow.addEventListener('load', function() {
        printWindow.print();
    }, true);
}
$(document).ready(function() {
    function PrintInvoice(id) {
        // const url = `?id=${id}`;
        // window.open(url, '_blank');
    }

    $(document).on('click', '#openRepairModal', function() {
        $("#viewRepairModal").modal('show');
        var repair_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/admin/reports/view-repair/info/" + repair_id,
            success: function(res) {
                console.log(res);
                let device_type = res.repair_info.device_type;
                let formatDevice = device_type.replaceAll("_", " ");

                $("#repair_no").text(res.repair_info.repair_no);
                $("#repairSale_no").text(res.repair_info.invoice_no);
                $("#repairDevice_type").text(formatDevice);
                $("#repairCustomerName").text(res.repair_info.customer_name);
                $("#repairDateCreated").text(res.repair_info.created_at);
            }
        });
    });

    $(document).on('click', '#openReturnModal', function() {
        $("#viewReturnModal").modal('show');
        var return_id = $(this).val();

        $.ajax({
            type: "GET",
            url: '/admin/reports/view-return/' + return_id,
            success: function(res) {
                let rawDate = res.return_info.created_at;
                let reason = res.return_info.reason;
                let totalAmount = res.return_info.total_amount;

                let formattedReason = reason.replaceAll("_", " ");
                // Convert to valid Date object
                let formattedDate = new Date(rawDate.replace(' ', 'T'));

                let format_date = formattedDate.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                //Display Info
                $("#return_no").text(res.return_info.return_no);
                $("#invoice_no").text(res.return_info.invoice_no);
                $("#return_date").text(format_date);
                $("#return_reason").text(formattedReason);
                $("#return_notes").text(res.return_info.notes);
                $("#totalItems").text(res.return_info.total_items);
                $("#totalAmount").text(
                    totalAmount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                );

                let returnTable = $("#returnTable");
                returnTable.empty();

                $.each(res.return_items, function(key, item) {
                    returnTable.append(`
                    <tr>
                        <td class="text-center">${key + 1}</td>
                        <td class="text-start">${item.name}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">₱${item.selling_price_snapshot.toLocaleString('en-PH')}</td>
                        <td class="text-end">₱${item.subtotal.toLocaleString('en-PH')}</td>
                    </tr>
                    `)
                });
            }
        });
    });

    $(document).on('click', '#openInvoiceModal', function() {
        var invoice_id = $(this).val();
        $("#invoicePreviewModal").modal('show');
        let tbody = $("#itemsProductBody");
        tbody.empty();
        $.ajax({
            type: "GET",
            url: "/admin/reports/view-invoice/" + invoice_id,
            success: function(res) {
                $("#invoice_no").text(res.invoice.invoice_no);
                let rawDate = res.invoice.completed_at;
                // Convert to valid Date object
                let formattedDate = new Date(rawDate.replace(' ', 'T'));

                let format_date = formattedDate.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                $("#invoice_date").text(format_date);
                $("#invoice_status").text(res.invoice.status);
                $("#invoice_total").text(res.invoice.total_amount.toLocaleString(
                    'en-PH'));
                $("#invoice_paid").text(res.invoice.amount_paid.toLocaleString(
                    'en-PH'));
                $("#invoice_change").text(res.invoice.change_amount.toLocaleString(
                    'en-PH'));
                $.each(res.items, function(key, item) {
                    tbody.append(`
                     <tr class="text-center">
                        <td>${key + 1}</td>
                        <td class="text-start fw-semibold">${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>₱${item.selling_price_snapshot.toLocaleString('en-PH')}</td>
                        <td class="fw-semibold">₱${item.subtotal.toLocaleString('en-PH')}</td>
                    </tr>
                    `)
                });
                PrintInvoice(res.invoice.id);
            }
        })
    });
    $("#dataRangeTable").DataTable();
    // This is Date Filter in the DataTables (This is comment for Educational for the future)
    // $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    //     if (
    //         settings.nTable.id !== "dataRangeTable") {
    //         return true;
    //     }

    //     var SelectedfromDate = $(".fromDate").val().substr(0, 7) || "";
    //     var SelectedtoDate = $(".toDate").val().substr(0, 7) || "";
    //     var theDate = data[2].substr(0, 7) || "";

    //     if (
    //         (SelectedfromDate === "" && SelectedtoDate === "") ||
    //         (SelectedfromDate === "" && date <= SelectedtoDate) ||
    //         (SelectedfromDate <= theDate && SelectedtoDate === "") ||
    //         (SelectedfromDate <= theDate && theDate <= SelectedtoDate)
    //     ) {
    //         return true;
    //     }
    //     return false;
    // });
    // $(".fromDate, .toDate").on("change", function() {
    //     dateRange.draw();
    // });

});

// $(document).on('click', "#generateDate", function() {
//     var fromDate = $("#fromDate").val();
//     var toDate = $("#toDate").val();
// });
</script>
@endsection