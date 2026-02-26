@extends('admin.body.header')
@section('admin')
@php
$i = 1;
@endphp

<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Repairs Module</h1>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addRepairModal">
                <i class="fa-solid fa-plus me-1"></i> Add Repair
            </button>
        </div>
        <div>
            <select class="form-select">
                <option value="">All Status</option>
                <option>Pending Diagnosis</option>
                <option>Awaiting Approval</option>
                <option>In Progress</option>
                <option>Completed</option>
                <option>Released</option>
                <option>Cancelled</option>
                <option>Abandoned</option>
            </select>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Repair No</th>
                            <th>Customer</th>
                            <th>Device</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Pickup Deadline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repairs as $repair)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center">{{ $repair->repair_no }}</td>
                            <td>
                                <div class="fw-semibold">{{ $repair->customer_name }}</div>
                                <small class="text-muted">{{ $repair->customer_number }}</small>
                            </td>
                            <td>{{ $repair->device_brand }}</td>
                            <td class="text-end">₱ {{ number_format($repair->total_amount, 2) }}</td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">Awaiting Approval</span>
                            </td>
                            <td class="text-center">{{ $repair->pickup_deadline }}</td>
                            <td class="text-center">
                                <!-- View Button -->
                                <button class="btn btn-sm btn-info text-white me-1" id="viewRepair"
                                    value="{{ $repair->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <!-- Diagnose Button -->
                                <!-- <button class="btn btn-sm btn-warning text-dark me-1" id="diagnoseRepair"
                                    value="{{ $repair->id }}">
                                    <i class="fa-solid fa-stethoscope"></i>
                                </button> -->
                                <!-- Complete Button -->
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <!-- Cancel Button -->
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-xmark"></i>
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

<!-- Add Repair Modal -->
<div class="modal fade" id="addRepairModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('generate.repair') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-tools me-2"></i> Add Repair
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Device Type</label>
                            <select class="form-select" name="device_type">
                                <option value="system_unit">System Unit</option>
                                <option value="laptop">Laptop</option>
                                <option value="printer">Printer</option>
                                <option value="monitor">Monitor</option>
                                <option value="router">Router</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Device Brand / Model</label>
                            <input type="text" name="device_brand" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Issue Description</label>
                            <textarea class="form-control" name="issue_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-dark" type="submit">Save Repair</button>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- View Repair Modal -->
<div class="modal fade" id="viewRepairModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    Repair Details - <span class="repair_no"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Repair Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Diagnose</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <p><strong>Customer:</strong> <span id="customer_name"></span></p>
                                        <p><strong>Contact:</strong> <span id="customer_number"></span></p>
                                        <p><strong>Device:</strong> <span id="device_type"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong>
                                            <span class="badge text-dark badgeColor" id="status"></span>
                                        </p>
                                        <p><strong>Received:</strong> <span id="date_create"></span></p>
                                        <p><strong>Pickup Deadline:</strong> <span id="date_released"></span></p>
                                    </div>
                                </div>
                                <hr>
                                <h6>Issue Description</h6>
                                <p class="text-muted"><span id="issue_desc"></span></p>
                                <h6>Diagnosis</h6>
                                <p class="text-muted"><span id="diag_desc"></span></p>
                                <hr>
                                <h6>Parts Used</h6>
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>PSU 650W</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">₱ 2,500.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-end">
                                    <p><strong>Labor Fee:</strong> ₱ 500.00</p>
                                    <p class="fs-5"><strong>Total:</strong> ₱ 3,000.00</p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-success">Await Approval</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Diagnosis</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>

                                <!-- Labor Fee -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Labor Fee</label>
                                        <input type="number" class="form-control">
                                        <small class="text-muted">
                                            Diagnostic fee may apply once unit is opened.
                                        </small>
                                    </div>
                                </div>

                                <hr>

                                <!-- Add Parts -->
                                <h6 class="mb-3">Add Parts</h6>

                                <div class="row g-3 align-items-end mb-3">

                                    <!-- Category -->
                                    <div class="col-md-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select modalSelect2">
                                            <option>Select Category</option>
                                            <option>Power Supply</option>
                                            <option>Motherboard</option>
                                            <option>RAM</option>
                                            <option>Storage</option>
                                        </select>
                                    </div>

                                    <!-- Product -->
                                    <div class="col-md-3">
                                        <label class="form-label">Product</label>
                                        <select class="form-select modalSelect2">
                                            <option>Select Product</option>
                                            <option>PSU 650W Bronze</option>
                                            <option>PSU 750W Gold</option>
                                        </select>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-2">
                                        <label class="form-label">Qty</label>
                                        <input type="number" class="form-control" min="1">
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="col-md-2">
                                        <label class="form-label">Unit Price</label>
                                        <input type="text" class="form-control" readonly>
                                    </div>

                                    <!-- Add Button -->
                                    <div class="col-md-2 d-grid">
                                        <button class="btn btn-dark">
                                            <i class="fa-solid fa-plus me-1"></i> Add
                                        </button>
                                    </div>

                                </div>

                                <!-- Parts Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>PSU 650W</td>
                                                <td class="text-center">1</td>
                                                <td class="text-end">₱ 2,500.00</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr>

                                <!-- Totals -->
                                <div class="row mt-3">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="border p-3 rounded bg-light">
                                            <div><strong>Labor Fee:</strong> ₱ 500.00</div>
                                            <div><strong>Parts Total:</strong> ₱ 2,500.00</div>
                                            <hr>
                                            <div class="fs-5">
                                                <strong>Total Amount:</strong>
                                                <span class="fw-bold text-success">₱ 3,000.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success">
                                    <i class="fa-solid fa-check me-1"></i> Approve & Generate Sale
                                </button>
                                <button class="btn btn-secondary">
                                    <i class="fa-solid fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '#viewRepair', function() {
        var repair_id = $(this).val();

        $("#viewRepairModal").modal('show');

        $.ajax({
            type: "get",
            url: '/admin/repairs/view/' + repair_id,
            success: function(res) {
                let rawDate = res.repair.created_at;
                let formattedDate = new Date(rawDate.replace(' ', 'T'));

                let format_date = formattedDate.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: true
                });

                let badgeColor = $(".badgeColor");

                let status = res.repair.status;
                if (status == "pending_diagnosis") {
                    badgeColor.addClass("bg-secondary");
                    badgeColor.addClass("text-white");
                } else if (status == "awaiting_approval") {
                    badgeColor.addClass("bg-warning");
                } else if (status == "in_progress") {
                    badgeColor.addClass("bg-primary");
                } else if (status == "completed") {
                    badgeColor.addClass("bg-success");
                } else if (status == "released") {
                    badgeColor.addClass("bg-success");
                } else if (status == "cancelled") {
                    badgeColor.addClass("bg-danger");
                } else if (status == "abandoned") {
                    badgeColor.addClass("bg-dark");
                }

                $(".repair_no").text(res.repair.repair_no);
                $("#customer_name").text(res.repair.customer_name);
                $("#customer_number").text(res.repair.contact_number);
                $("#device_type").text(res.repair.device_type);
                $("#status").text(res.repair.status);
                $("#date_create").text(format_date);
                $("#date_released").text(res.repair.date_released);
                $("#issue_desc").text(res.repair.issue_description);
            }
        });
    });

    $(document).on('click', '#diagnoseRepair', function() {
        var repair_id = $(this).val();

        $('#diagnoseModal').modal('show');

        $.ajax({
            type: "get",
            url: '' + repair_id,
            success: function() {

            }
        })
    });
});
</script>
@endsection