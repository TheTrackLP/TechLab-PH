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
                            <td class="text-center">
                                {{ !empty($repair->pickup_deadline) ? date('M d, Y' ,strtotime($repair->pickup_deadline)) : '' }}
                            </td>
                            <td class="text-center">
                                <!-- View Button -->
                                <button class="btn btn-sm btn-info text-white me-1" id="viewRepair"
                                    value="{{ $repair->id }}">
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
                        <button class="nav-link active repairTab" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Repair Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link diagnosisTab" id="pills-profile-tab" data-bs-toggle="pill"
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
                                            <span class="badge" id="badgeColor"></span>
                                        </p>
                                        <p><strong>Received:</strong> <span id="date_create"></span></p>
                                        <p><strong>Pickup Deadline:</strong> <span id="date_released"></span></p>
                                    </div>
                                </div>
                                <hr>
                                <h6>Issue Description</h6>
                                <p class="text-muted"><span id="issue_desc"></span></p>
                                <h6>Diagnosis</h6>
                                <p class="text-muted"><span class="diagnosis"></span></p>
                                <hr>
                                <h6>Parts Used</h6>
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="repairDetailsTable">

                                    </tbody>
                                </table>
                                <div class="border-top pt-3 mt-3">

                                    <div class="row">

                                        <!-- LEFT: COST BREAKDOWN -->
                                        <div class="col-md-6">
                                            <div class="bg-light p-3 rounded border">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Labor Fee</span>
                                                    <span class="labor_fee fw-semibold"></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Parts Total</span>
                                                    <span class="parts_amount fw-semibold"></span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Change</span>
                                                    <span class="changeDisplayAmount fw-semibold">P 0.00</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fs-5">
                                                    <strong>Total Amount</strong>
                                                    <strong class="overallAmount text-success"></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-white p-3 rounded border">
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Type</label>
                                                    <select class="form-select" id="payment_type">
                                                        <option value="cash">Cash</option>
                                                        <option value="gcash">GCash</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount Paid</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="amount_paid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-xmark me-1"></i> Close
                                        </button>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <button type="button" class="btn btn-danger btnChangeStatus"
                                            data-value="cancelled" id="btnCancelRepair">
                                            <i class="fa-solid fa-ban me-1"></i> Cancel Repair
                                        </button>
                                        <button type="button" class="btn btn-primary btnChangeStatus"
                                            data-value="in_progress" id="btnApproveRepair">
                                            <i class="fa-solid fa-check me-1"></i> Approve Repair
                                        </button>
                                        <button type="button" class="btn btn-success btnChangeStatus"
                                            data-value="completed" id="btnMarkCompleted">
                                            <i class="fa-solid fa-circle-check me-1"></i> Mark as Completed
                                        </button>
                                        <button type="button" class="btn btn-warning text-dark btnChangeStatus"
                                            data-value="generate_sale" id="btnGenerateSale" disabled>
                                            <i class="fa-solid fa-receipt me-1"></i> Generate Sale
                                        </button>
                                        <button type="button" class="btn btn-info text-white btnChangeStatus"
                                            data-value="released" id="btnReleaseUnit">
                                            <i class="fa-solid fa-box-open me-1"></i> Release Unit
                                        </button>
                                        <button type="button" class="btn btn-secondary btnChangeStatus"
                                            data-value="abandoned" id="btnMarkAbandoned">
                                            <i class="fa-solid fa-clock me-1"></i> Mark as Abandoned
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">
                        <div class="card">
                            <input type="hidden" name="id" id="repairId">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Diagnosis</label>
                                    <textarea class="form-control diagnosis" name="diagnosis" id="diagnosis"
                                        rows="3"></textarea>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Labor Fee</label>
                                        <input type="number" name="labor_fee" id="labor_fee"
                                            class="form-control labor_fee">
                                        <small class="text-muted">
                                            Diagnostic fee may apply once unit is opened.
                                        </small>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="mb-3">Add Parts</h6>
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fa-solid fa-screwdriver-wrench me-2 text-primary"></i>
                                                Add / Modify Parts
                                            </h6>
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                id="currentPartsPreview">
                                                <i class="fa-solid fa-eye me-1"></i>
                                                View Current Parts
                                            </button>
                                        </div>
                                        <!-- Add New Part Row -->
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Category</label>
                                                <select class="form-select modalSelect2" id="pickCategory">
                                                    <option></option>
                                                    @foreach ($categories as $row)
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->category_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Product</label>
                                                <select class="form-select modalSelect2" id="getProducts"></select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Qty</label>
                                                <input type="number" id="quantity" class="form-control" min="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Unit Price</label>
                                                <input type="text" id="unit_price" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-2 d-grid">
                                                <button type="button" class="btn btn-dark" id="addRepairParts">
                                                    <i class="fa-solid fa-plus me-1"></i> Add Part
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="repairEditTable">

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="row mt-4 g-4">
                                    <!-- Current Saved Total -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm bg-light h-100">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-muted mb-3">
                                                    <i class="fa-solid fa-receipt me-2 text-secondary"></i>
                                                    Current Saved Amount
                                                </h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Labor Fee:</span>
                                                    <span class="fw-semibold text-dark labor_fee">

                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Parts Total:</span>
                                                    <span class="fw-semibold text-dark parts_amount">

                                                    </span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fs-5">
                                                    <strong>Total Amount:</strong>
                                                    <strong class="text-secondary overallAmount">

                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- New Estimated Total -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-success mb-3">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>
                                                    New Estimated Amount
                                                </h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Labor Fee:</span>
                                                    <span class="fw-semibold" id="laborFeePreview">

                                                    </span>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Parts Total:</span>
                                                    <span class="fw-semibold" id="partsTotalPreview">

                                                    </span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fs-5">
                                                    <strong>Total Amount:</strong>
                                                    <strong class="text-success" id="overallTotalPreview">

                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="saveRepair" class="btn btn-success"> Save
                                    Diagnosis</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
let repairParts = [];
let total = 0;
let totalOverallPay = 0;
let change = 0;
let fee = 0;
let SelectedProductId = null;
let selectedProductName = null;

$(document).ready(function() {
    $(document).on('click', '#viewRepair', function() {
        var repair_id = $(this).val();

        $("#viewRepairModal").modal('show');
        let tbodyRepair = $("#repairDetailsTable");
        tbodyRepair.empty();

        $.ajax({
            type: "get",
            url: '/admin/repairs/view/' + repair_id,
            success: function(res) {
                let rawDate = res.repair.created_at;
                let formattedDate = new Date(rawDate.replace(' ', 'T'));
                let fee = parseFloat(res.repair.labor_fee) || 0;
                let parts = parseFloat(res.repair.total_parts_amount) || 0;
                let overallAmount = fee + parts;

                let format_date = formattedDate.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: true
                });

                let badgeColor = $("#badgeColor");
                badgeColor.attr("class", "badge text-white");
                badgeColor.removeClass(
                    "bg-secondary bg-warning bg-primary bg-success bg-dark bg-warning bg-danger"
                );

                let status = res.repair.status;
                switch (status) {
                    case 'pending_diagnosis':
                        badgeColor.addClass("bg-secondary");
                        badgeColor.addClass("text-white");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "none");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "block");
                        break;
                    case 'awaiting_approval':
                        badgeColor.addClass("bg-warning");
                        $('#btnApproveRepair').css("display", "block");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "none");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "block");
                        break;
                    case 'in_progress':
                        badgeColor.addClass("bg-primary");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "block");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "block");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "none");
                        break;
                    case 'completed':
                        badgeColor.addClass("bg-success");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "block");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "none");
                        if (res.repair.sale_id == null) {
                            $('#btnGenerateSale').css("display", "block");
                        } else {
                            $('#btnGenerateSale').css("display", "none");
                            $('#btnReleaseUnit').css("display", "block");
                            $('#btnCancelRepair').css("display", "none");
                        }
                        break;
                    case 'released':
                        badgeColor.addClass("bg-success");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "none");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "none");
                        break;
                    case 'cancelled':
                        badgeColor.addClass("bg-danger");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "none");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "none");
                        break;
                    case 'abandoned':
                        badgeColor.addClass("bg-dark");
                        $('#btnApproveRepair').css("display", "none");
                        $('#btnMarkCompleted').css("display", "none");
                        $('#btnGenerateSale').css("display", "none");
                        $('#btnReleaseUnit').css("display", "none");
                        $('#btnCancelRepair').css("display", "none");
                        $('#btnMarkAbandoned').css("display", "none");
                        $('.diagnosisTab').css("display", "none");
                }
                //Global Display (Both tabs)
                $(".labor_fee").text(
                    fee.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                );
                $(".parts_amount").text(
                    parts.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                );
                $(".overallAmount").text(
                    overallAmount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                );

                //Displat Repair Details Tab
                $(".repair_no").text(res.repair.repair_no);
                $("#customer_name").text(res.repair.customer_name);
                $("#customer_number").text(res.repair.contact_number);
                $("#device_type").text(res.repair.device_type);
                $("#badgeColor").text(res.repair.status);
                $("#date_create").text(format_date);
                $("#date_released").text(res.repair.date_released);
                $(".diagnosis").text(res.repair.diagnosis);
                $("#issue_desc").text(res.repair.issue_description);

                $(".overallAmount").text(
                    overallAmount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                );

                //Display Data on Diagnosis Tab
                $("#repairId").val(repair_id);
                $(".labor_fee").val(res.repair.labor_fee);
                $(".diagnosis").val(res.repair.diagnosis);

                $(document).on('click', '#currentPartsPreview', function() {
                    //Recall parts for Diagnosis Tab
                    repairParts = [];
                    res.parts.forEach(part => {
                        repairParts.push({
                            product_id: part.product_id,
                            name: part.name,
                            quantity: part.quantity,
                            selling_price: parseFloat(part
                                .selling_price_snapshot)
                        });
                    });
                    addRepairTable();
                });

                $(document).on('input', '#amount_paid', function() {
                    let amountPaid = parseFloat($(this).val()) || 0;
                    let totalAmount = res.repair.total_amount;

                    change = amountPaid - totalAmount;

                    if (change < 0) {
                        $(".changeDisplayAmount")
                            .removeClass("text-success")
                            .addClass("text-danger")
                            .text("₱0.00");
                    } else {
                        $(".changeDisplayAmount")
                            .removeClass("text-danger")
                            .addClass("text-success")
                            .text(
                                change.toLocaleString('en-PH', {
                                    style: 'currency',
                                    currency: 'PHP'
                                })
                            );
                    }
                    if (amountPaid < totalAmount) {
                        $("#btnGenerateSale").prop("disabled", true);
                    } else {
                        $("#btnGenerateSale").prop("disabled", false);
                    }
                });


                //Display Parts in Repair Details Tab
                $.each(res.parts, function(key, item) {
                    tbodyRepair.append(`
                     <tr class="text-center">
                        <td class="text-start fw-semibold">${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>Unit Price</td>
                        <td class="fw-semibold">₱${item.subtotal.toLocaleString('en-PH')}</td>
                    </tr>
                    `);
                });

                $(document).on('click', '.btnChangeStatus', function() {
                    let repairStatus_id = repair_id;
                    let statusChange = $(this).data('value');
                    let generateSaleParts = res.parts;
                    let sale_id = res.repair.sale_id;
                    let labor_fee = res.repair.labor_fee;

                    $.ajax({
                        type: "post",
                        url: '/admin/repairs/changeRepair-status/' +
                            repairStatus_id,
                        data: {
                            generateSaleParts: generateSaleParts,
                            statusChange: statusChange,
                            change: change,
                            labor_fee: labor_fee,
                            amountPaid: $("#amount_paid").val() || 0,
                            _token: $('meta[name="csrf-token"]').attr(
                                'content'),
                        },
                        success: function(res) {
                            Swal.fire({
                                title: "Repair Status Updated",
                                text: "Repair has been " +
                                    res
                                    .status + " | " +
                                    res.repair_no,
                                icon: "success",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    })
                });
            }
        });
    });

    $(document).on('change', '#pickCategory', function() {
        var category = $(this).val();

        $.ajax({
            type: "GET",
            url: '/admin/products/category/' + category,
            success: function(res) {
                let showProducts = $("#getProducts");
                showProducts.empty();

                $.each(res.products, function(key, product) {
                    showProducts.append(`
                        <option value=""></option>
                        <option value="${product.id}" data-id="${product.id}" data-name="${product.name}" data-price="${product.selling_price}" data-stock="${product.stock_quantity}">${product.name}</option>
                    `);
                });

            }
        });
    });

    $(document).on('change', '#getProducts', function() {
        var price = $(this).find(':selected').data('price') || 0;
        var productMame = $(this).find(':selected').data('name') || 0;
        var product_id = $(this).find(':selected').data('id') || 0;
        var stock = $(this).find(':selected').data('stock') || 0;

        $("#unit_price").val(parseFloat(price).toFixed(2));
        SelectedProductId = product_id;
        selectedProductName = productMame;

    });

    $(document).on('click', '#addRepairParts', function() {
        let id = SelectedProductId;
        let name = selectedProductName;
        let selling_price = $("#unit_price").val();
        let quantity = $("#quantity").val();

        let existing = repairParts.find(item => item.product_id === id);

        if (existing) {
            if (existing.quantity < stock) {
                existing.quantity += 1;
            } else {
                toastr.error("Error", "Stock limit reached");
            }
        } else {

            if (!SelectedProductId) {
                repairParts = [];
                toastr.error('Error, Select Item to picked');
            } else {
                repairParts.push({
                    product_id: id,
                    name: name,
                    selling_price: selling_price,
                    quantity: quantity,
                });
            }
        }
        addRepairTable();
    });
});

function addRepairTable() {
    let tbodyRepair = $("#repairEditTable");
    tbodyRepair.empty();
    total = 0;

    repairParts.forEach((item, index) => {
        let subTotal = item.selling_price * item.quantity;
        total += subTotal;
        tbodyRepair.append(`
        <tr>
            <td class="text-start">${item.name}</td>
            <td class="text-center">${item.quantity}</td>
            <td class="text-center">₱${item.selling_price.toLocaleString('en-PH')}</td>
            <td class="text-center">₱${subTotal.toLocaleString('en-PH')}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick='removePart(${index})'>
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        `);
    });

    $("#partsTotalPreview").text(
        total.toLocaleString('en-PH', {
            style: 'currency',
            currency: 'PHP'
        })
    );

    calculateTotalRepair();
}

function removePart(index) {
    repairParts.splice(index, 1);
    addRepairTable();
}

function calculateTotalRepair() {
    let fee = parseFloat($("#labor_fee").val()) || 0;
    totalOverallPay = fee + total;

    $("#laborFeePreview").text(fee.toLocaleString('en-PH'));

    $("#overallTotalPreview").text(totalOverallPay.toLocaleString('en-PH'));
}

$(document).on('input', '.labor_fee', function() {
    calculateTotalRepair();
});

$(document).on('click', '#saveRepair', function() {
    $.ajax({
        type: "post",
        url: '/admin/repairs/update',
        data: {
            repairParts: repairParts,
            totalOverallPay: totalOverallPay,
            diagnosis: $("#diagnosis").val(),
            labor_fee: $("#labor_fee").val() || 0,
            repairId: $("#repairId").val() || 0,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(res) {
            Swal.fire({
                title: "Repair Updated",
                text: "Repair No. is " + res.repair_no,
                icon: "success",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
            repairParts = [];
            addRepairTable();
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    })
});
</script>
@endsection