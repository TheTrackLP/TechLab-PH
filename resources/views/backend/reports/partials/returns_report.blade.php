<div class="tab-pane fade" id="pills-return-report" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
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