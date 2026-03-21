<div class="tab-pane fade" id="pills-repair-report" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
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
                                <button class="btn btn-info text-white" value="{{ $repair->id }}" id="openRepairModal">
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
                    <span class="badge bg-primary fs-6" id="repairStatus">

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
                    <p class="mb-0 text-muted" id="repairDiagnosis">
                        .
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
                        <tbody id="repairPartsTable">

                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <strong>Repair Notes</strong>
                            <p class="text-muted mb-0" id="repairNotes">
                                .
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-end">
                            <div class="mb-2">
                                <strong>Labor Fee:</strong>
                                <span id="repairLaborFee"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Parts Total:</strong>
                                <span id="repairTotalPartsAmount"></span>
                            </div>
                            <hr>
                            <div class="fs-5">
                                <strong>Total Amount:</strong>
                                <span class="fw-bold text-success" id="repairTotalAmount"></span>
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