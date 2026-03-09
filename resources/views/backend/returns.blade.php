@extends('admin.body.header')
@section('admin')

<div class="container-fluid mt-4">
    <!-- PAGE TITLE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Product Return</h4>
    </div>
    <!-- SEARCH INVOICE -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">Search Invoice</h6>
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" name="getInvoice" id="getInvoice" class="form-control"
                        placeholder="Enter invoice number (ex: TL-2026-00001)">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-dark" id="getInvoiceNo">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- SALE DETAILS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">Invoice Details</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Invoice:</strong> <span id="invoice_no"></span>
                </div>
                <div class="col-md-4">
                    <strong>Customer:</strong> <span id="invoice_customer"></span>
                </div>
                <div class="col-md-4">
                    <strong>Date:</strong> <span id="invoice_date"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- PURCHASED ITEMS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">Purchased Items</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Qty Bought</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Return Qty</th>
                        </tr>
                    </thead>
                    <tbody id="returnItemsTable">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- RETURN DETAILS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">Return Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Return Reason</label>
                    <select class="form-select">
                        <option>Defective</option>
                        <option>Wrong Item</option>
                        <option>Customer Changed Mind</option>
                        <option>Damaged</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Notes</label>
                    <input type="text" class="form-control" placeholder="Optional notes">
                </div>
            </div>
        </div>
    </div>
    <!-- SUMMARY -->
    <div class="row mb-4">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="border rounded p-3 bg-light">
                <div class="d-flex justify-content-between">
                    <span>Total Return Amount:</span>
                    <strong>₱1,300.00</strong>
                </div>
            </div>
        </div>
    </div>
    <!-- ACTION BUTTON -->
    <div class="text-end">
        <button class="btn btn-danger me-2">
            Cancel
        </button>
        <button class="btn btn-success">
            Confirm Return
        </button>
    </div>
</div>

<script>
let totalReturnAmount = 0;
let returnItems = [];
$(document).ready(function() {
    $(document).on('click', '#getInvoiceNo', function() {
        let invoice = $("#getInvoice").val();

        let returnBody = $("#returnItemsTable");
        returnBody.empty();

        $.ajax({
            type: "GET",
            url: "/admin/returns/invoice/" + invoice,
            success: function(res) {
                console.log(res);
                if (res.sale == null) {
                    Swal.fire({
                        icon: "error",
                        title: "Invoice " + invoice + " not found",
                        text: "Please check the invoice number!",
                    });
                } else {
                    $("#invoice_no").text(res.sale.invoice_no);
                    $("#invoice_customer").text(res.sale.customer_name);
                    $("#invoice_date").text(res.sale.completed_at);

                    $.each(res.items, function(key, item) {
                        returnBody.append(`
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" id="checkboxItem" data-id=${item.product_id} data-price=${item.selling_price_snapshot}>
                            </td>
                            <td>${item.name}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">₱${item.selling_price_snapshot.toLocaleString('en-PH')}</td>
                            <td class="text-end">₱${item.subtotal.toLocaleString('en-PH')}</td>
                            <td>
                                <input type="number" class="form-control" min="1" max="2" placeholder="Qty">
                            </td>
                        </tr>
                        `)
                    });
                }

                $(document).on('change', '#checkboxItem', function() {
                    let selectedItem = $(this).data('id');
                    let selectedPrice = parseFloat($(this).data('price'));

                    totalReturnAmount += selectedPrice;

                    console.log(totalReturnAmount);
                });
            }
        });
    });
});
</script>
@endsection