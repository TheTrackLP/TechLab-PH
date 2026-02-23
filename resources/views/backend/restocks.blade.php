@extends('admin.body.header')
@section('admin')

<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Restock Products</h1>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <select class="select2" name="supplier_id" id="supplier_id">
                        <option></option>
                        @foreach ($suppliers as $supply)
                        <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference No</label>
                    <input type="text" name="reference_no" id="reference_no" class="form-control"
                        placeholder="Supplier Invoice #">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Notes</label>
                    <input type="text" name="notes" data-="notes" class="form-control" placeholder="Optional notes">
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Add Product</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Product</label>
                    <select class="select2 form-control" id="product_id">
                        <option></option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} | {{ $product->sku }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Stock</label>
                    <input type="text" class="form-control" id="current_stock" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Current Cost</label>
                    <input type="text" class="form-control" id="current_cost_price" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Selling Price</label>
                    <input type="text" class="form-control" id="current_selling_price" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">New Cost</label>
                    <input type="number" class="form-control" id="new_cost_price" step="any">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control" id="quantity">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-dark w-100" id="addRestock">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <div class="alert alert-info py-2 mb-0" id="profit_preview">
                    Estimated Profit Per Unit: <span id="estimated"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Restock Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th class="text-start">Product</th>
                            <th>Qty</th>
                            <th>Cost</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id=restockTable>

                    </tbody>
                </table>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <strong>Total Items:</strong> <span id="totalItems"></span>
                </div>
                <div class="col-md-6 text-end">
                    <strong>Total Cost:</strong> <span id="overAll"></span>
                </div>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-success px-4" id="saveRestock">
                    <i class="fa-solid fa-save me-1"></i>
                    Save Restock
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let productRestock = [];
let total = 0;
let totalItems = 0;
let selectedProductId = null;
let selectedProductName = null;

$(document).ready(function() {
    $(document).on('change', "#product_id", function() {
        var product_id = $(this).val();
        let new_cost_price = $('#new_cost_price');
        let quantity = $('#quantity');
        selectedProductId = product_id;
        new_cost_price.val('');
        quantity.val('');

        let alertBox = $("#profit_preview");

        alertBox.removeClass("alert-danger alert-success");
        alertBox.addClass("alert-info");
        alertBox.text(`Estimated Profit Per Unit: ₱ 0.00`);
        $.ajax({
            type: "Get",
            url: '/admin/product/getData/' + product_id,
            success: function(res) {
                let current_selling_price = res.item.selling_price;
                let total = 0;
                selectedProductName = res.item.name;
                $(document).on('input', '#new_cost_price', function() {
                    new_cost_price = parseFloat($("#new_cost_price").val()) ||
                        0;
                    let selling_price = parseFloat(current_selling_price) || 0;

                    let total = selling_price - new_cost_price;

                    if (total < 0) {
                        alertBox.addClass("alert-danger");
                    } else {
                        alertBox.addClass("alert-success");
                    }

                    alertBox.text(
                        `Estimated Profit Per Unit: ₱ ${total.toFixed(2)}`);
                });
                $("#current_stock").val(res.item.stock_quantity);
                $("#current_cost_price").val(res.item.cost_price);
                $("#current_selling_price").val(current_selling_price);

            }
        });
    });
    $(document).on('click', '#addRestock', function() {
        let id = selectedProductId;
        let name = selectedProductName;
        let cost_price = $('#new_cost_price').val();
        let quantity = $('#quantity').val();

        if (!selectedProductId) {
            toastr.error("Error, Select Item to Restock");
        } else {
            productRestock.push({
                product_id: id,
                name: name,
                cost_price: cost_price,
                quantity: quantity,

            });
        }
        addStock();
    });
});

function addStock() {
    let tbodyRestock = $("#restockTable");
    tbodyRestock.empty();

    total = 0;
    totalItems = 0;

    productRestock.forEach((item, index) => {
        let Subtotal = item.cost_price * item.quantity;
        total += Subtotal;
        totalItems = 1 + index++;
        tbodyRestock.append(`
        <tr>
            <td>${index++}</td>
            <td class="text-start">${item.name}</td>
            <td>${item.quantity}</td>
            <td>₱${item.cost_price.toLocaleString('en-PH')}</td>
            <td>₱${Subtotal.toLocaleString('en-PH')}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick='removeItem(${index})'>
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        `);
    });
    $("#totalItems").text(totalItems);
    $("#overAll").text(
        total.toLocaleString('en-PH', {
            style: 'currency',
            currency: 'PHP'
        })
    );
}

function removeItem(index) {
    productRestock.splice(index, 1);
    addStock();
}

$(document).on('click', '#saveRestock', function() {
    $.ajax({
        type: "Post",
        url: "/admin/product/restocks/save",
        data: {
            productRestock: productRestock,
            totalItems: totalItems,
            supplier_id: $("#supplier_id").val() || 0,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(res) {
            Swal.fire({
                title: "Sale Complete",
                text: "Reference No. is " + res.reference_no,
                icon: "success",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
            productRestock = [];
            addStock();
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});
</script>

@endsection