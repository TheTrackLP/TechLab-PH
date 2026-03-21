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
                            <td class="text-center"><span class="badge bg-primary">{{ $sale->payment_type }}</span>
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