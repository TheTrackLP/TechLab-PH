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
                <table class="table table-bordered table-hover align-middle">
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
                <div class="col-md-4">
                    <label class="form-label">Return Reason</label>
                    <select class="form-select select2" id="reason">
                        <option value=""></option>
                        <option value="defective">Defective</option>
                        <option value="wrong_item">Wrong Item</option>
                        <option value="customer_change_mind">Customer Changed Mind</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Return Type</label>
                    <select class="form-select select2" id="reason_type">
                        <option value=""></option>
                        <option class="refund">Refund</option>
                        <option class="exchange">Exchange</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Notes</label>
                    <input type="text" class="form-control" id="notes" placeholder="Optional notes">
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
                    <strong><span id="returnTotalAmount"></span></strong>
                </div>
            </div>
        </div>
    </div>
    <!-- ACTION BUTTON -->
    <div class="text-end">
        <button class="btn btn-danger me-2">
            Cancel
        </button>
        <button class="btn btn-success" id="confirmReturnItems">
            Confirm Return
        </button>
    </div>
</div>

<script>
let totalReturnAmount = 0;
let returnItems = [];
let sale_id = null;
let returnItemID = null;
let returnItemPrice = null;
let returnItemName = null;
let returnItemQTY = 0

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
                sale_id = res.sale.id;
                $("#invoice_no").text(res.sale.invoice_no);
                $("#invoice_customer").text(res.sale.customer_name);
                $("#invoice_date").text(res.sale.completed_at);

                $.each(res.items, function(key, item) {
                    returnBody.append(`
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="checkboxItem" data-id="${item.product_id}" data-name="${item.name}" data-price="${item.selling_price_snapshot}">
                            </td>
                            <td>${item.name}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">₱${item.selling_price_snapshot.toLocaleString('en-PH')}</td>
                            <td class="text-end">₱${item.subtotal.toLocaleString('en-PH')}</td>
                            <td>
                                <input type="number" class="form-control text-center itemQTY" min="0" max="${item.quantity}" placeholder="Qty" disabled>
                            </td>
                        </tr>
                        `)
                });

                $(document).on('change', '.checkboxItem', function() {
                    let qtyInput = $(this).closest('tr').find('.itemQTY');

                    if (this.checked) {
                        $(qtyInput).prop("disabled", false);
                    } else {
                        returnItems = [];
                        $(qtyInput).prop("disabled", true);
                    }
                });
                $(document).on('input', ".itemQTY", function() {
                    let returnItemQTY = parseInt($(this).val()) || 0;

                    $('.checkboxItem:checked').each(function() {
                        returnItemID = $(this).data('id');
                        returnItemName = $(this).data('name');
                        returnItemPrice = parseFloat($(this).data(
                            'price')) || 0;

                        let row = $(this).closest('tr');
                    });

                    if (returnItemQTY > 0) {
                        let subTotal = returnItemPrice * returnItemQTY;
                        totalReturnAmount += subTotal;

                        returnItems.push({
                            product_id: returnItemID,
                            name: returnItemName,
                            price: returnItemPrice,
                            quantity: returnItemQTY
                        });
                    }

                    $("#returnTotalAmount").text(
                        totalReturnAmount.toLocaleString(
                            'en-PH', {
                                style: 'currency',
                                currency: 'PHP'
                            })
                    );
                });

            },
            error: function(res) {
                Swal.fire({
                    icon: "error",
                    title: "Invoice " + invoice + " not found",
                    text: "Please check the invoice number!",
                });
            }
        });
    });
});
$(document).on('click', '#confirmReturnItems', function() {
    $.ajax({
        type: "post",
        url: "/admin/returns/store",
        data: {
            returnItems: returnItems,
            totalReturnAmount: totalReturnAmount,
            reason: $("#reason").val(),
            notes: $("#notes").val(),
            reason_type: $("#reason_type").val(),
            sale_id: sale_id,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(res) {
            Swal.fire({
                title: "Return Complete",
                text: "Return No is " + res
                    .return_no,
                icon: "success",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
            returnItems = [];
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});
</script>
@endsection