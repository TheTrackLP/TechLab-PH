@extends('admin.body.header')
@section('admin')

@php
$low_table = 1;
$prod_table = 1;
$date_table = 1;
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
                <div class="col-md-6">
                    <label class="form-label d-block invisible">Actions</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark w-100" onclick="GenerateDateRangeInvoice()">
                            <i class="fa-solid fa-file-invoice me-1"></i>
                            Invoice Report
                        </button>
                        <button class="btn btn-dark w-100" onclick="GenerateDateRangeProductSale()">
                            <i class="fa-solid fa-chart-column me-1"></i>
                            Product Sales
                        </button>
                    </div>
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
    <!--  INVOICE REPORT -->
    <h5 class="mb-3">Date Range Report</h5>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <!-- Table -->
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
    <!-- PRODUCT SALES REPORT -->
    <h5 class="mb-3">Product Sales Report</h5>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
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
    <!-- LOW STOCK REPORT -->
    <h5 class="mb-3">Low Stock Report</h5>
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

<script>
function GenerateDateRangeInvoice() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    if (!fromDate || !toDate) {
        toastr.error("Error, Both Date are empty!");
        return;
    }
    const url = `{{ route('dateRange.invoice') }}?fromDate=${fromDate}&toDate=${toDate}`;
    printNewWindow(url);
}

function GenerateDateRangeProductSale() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    if (!fromDate || !toDate) {
        toastr.error("Error, Both Date are empty!");
        return;
    }
    const url = `{{ route('dateRange.productSale') }}?fromDate=${fromDate}&toDate=${toDate}`;
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


    $(document).on('click', '#openInvoiceModal', function() {
        var invoice_id = $(this).val();
        $("#invoicePreviewModal").modal('show');
        let tbody = $("#itemsProductBody");
        tbody.empty();
        $.ajax({
            type: "GET",
            url: "/admin/reports/view-invoice/" + invoice_id,
            success: function(res) {
                console.log(res);

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