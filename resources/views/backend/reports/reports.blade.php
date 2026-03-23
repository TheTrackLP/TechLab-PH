@extends('admin.body.header')
@section('admin')

@php
$low_table = 1;
$prod_table = 1;
$date_table = 1;
$stockMovenum = 1;
$returnNum = 1;
$repairNum = 1;
$productNum = 1;
@endphp
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Reports Module</h1>
    <hr>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Date Range Filter Reports</h5>
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
                    <select class="select2" name="reportType" id="reportType">
                        <option></option>
                        <option value="sales">Invoice Report</option>
                        <option value="products">Product Sale Report</option>
                        <option value="returns">Returns Report</option>
                        <option value="repairs">Repair Report</option>
                        <option value="stock_movements">Stock Movement Report</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label d-block invisible">Actions</label>
                    <button class="btn btn-dark w-100" onclick="GenerateDateRangeReports()">
                        <i class="fa-solid fa-chart-column me-1"></i>
                        Genrate Date Range Report
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
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-stock-report"
                type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Stock Movement
                Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-inventory-report"
                type="button" role="tab" aria-controls="pills-home" aria-selected="false">Inventory Report</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <!-- Invoice Report -->
        @include('backend.reports.partials.invoice_report')
        <!-- Product Sales Report -->
        @include('backend.reports.partials.product_sale_report')
        <!-- Return Report -->
        @include('backend.reports.partials.returns_report')
        <!-- Repair Report -->
        @include('backend.reports.partials.repairs_report')
        <!-- Stock Movements Report -->
        @include('backend.reports.partials.stock_movements_report')
        <!-- Inventory Report -->
        @include('backend.reports.partials.inventory_report')
    </div>
</div>
<script>
function GenerateDateRangeReports() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const reportType = document.getElementById('reportType').value;

    const url = `{{ route('daterange.filter') }}?fromDate=${fromDate}&toDate=${toDate}&reportType=${reportType}`;
    printNewWindow(url);
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
                // Convert to valid Date object
                let rawDate = res.repair_info.created_at;
                let formattedDate = new Date(rawDate.replace(' ', 'T'));
                let format_date = formattedDate.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });

                $("#repair_no").text(res.repair_info.repair_no);
                $("#repairSale_no").text(res.repair_info.invoice_no);
                $("#repairDevice_type").text(formatDevice);
                $("#repairCustomerName").text(res.repair_info.customer_name);
                $("#repairDateCreated").text(format_date);
                $("#repairDiagnosis").text(res.repair_info.diagnosis);
                $("#repairNotes").text(res.repair_info.notes);
                $("#repairStatus").text(res.repair_info.status);
                $("#repairLaborFee").text(
                    res.repair_info.labor_fee.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    }));
                $("#repairTotalPartsAmount").text(
                    res.repair_info.total_parts_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    }));
                $("#repairTotalAmount").text(
                    res.repair_info.total_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    }));

                let repairPartsTable = $("#repairPartsTable");
                repairPartsTable.empty();
                $.each(res.repair_items, function(key, item) {
                    repairPartsTable.append(`
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