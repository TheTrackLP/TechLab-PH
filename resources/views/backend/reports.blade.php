@extends('admin.body.header')
@section('admin')

@php
$low_table = 1;
$prod_table = 1;
$date_table = 1;
@endphp
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Reports Module</h1>
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
    <!--  DATE RANGE REPORT -->
    <h5 class="mb-3">Date Range Report</h5>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <!-- Date Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date" name="fromDate" id="fromDate" class="form-control fromDate">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date" name="toDate" id="toDate" class="form-control toDate">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-dark w-100" id="generateDate">
                        Generate Report
                    </button>
                </div>
            </div>
            <!-- Date Range Summary -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded border">
                        <strong>Total Revenue:</strong> ₱ 120,500.00
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded border">
                        <strong>Total Profit:</strong> ₱ 32,100.00
                    </div>
                </div>
            </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_sales as $sale)
                        <tr>
                            <td class="text-center">{{ $date_table++ }}</td>
                            <td>{{ $sale->invoice_no }}</td>
                            <td class="text-center">{{ date('Y-m-d', strtotime($sale->completed_at)) }}</td>
                            <td class="text-end">₱ {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end text-success">₱ {{ number_format($sale->total_profit, 2) }}</td>
                            <td class="text-center"><span class="badge bg-primary">{{ $sale->payment_type }}</span></td>
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

<script>
$(document).ready(function() {
    var dateRange = $("#dataRangeTable").DataTable({
        ordering: false,
        searching: true,
    });
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (
            settings.nTable.id !== "dataRangeTable") {
            return true;
        }

        var SelectedfromDate = $(".fromDate").val() || "";
        var SelectedtoDate = $(".toDate").val() || "";
        var theDate = data[2] || "";
        console.log(SelectedfromDate.substr(0, 7));
        console.log(SelectedtoDate.substr(0, 7));


        if (
            (SelectedfromDate.substr(0, 7) === "" || theDate.includes(SelectedfromDate.substr(0, 7))) ||
            (SelectedtoDate.substr(0, 7) === "" || theDate.includes(SelectedtoDate.substr(0, 7)))
        ) {
            return true;
        }
        return false;
    });
    $(".fromDate, .toDate").on("change", function() {
        dateRange.draw();
    });

});

// $(document).on('click', "#generateDate", function() {
//     var fromDate = $("#fromDate").val();
//     var toDate = $("#toDate").val();

// });
</script>
@endsection