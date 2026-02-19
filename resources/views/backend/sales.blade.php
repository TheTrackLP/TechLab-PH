@extends('admin.body.header');
@section('admin')

<div class="container-fluid">
    <h1 class="">Point of Sale</h1>
    <div class="row">
        <!-- LEFT SIDE - PRODUCT LIST -->
        <div class="col-lg-7">
            <!-- Product Grid -->
            <div class="row g-3">
                <!-- Example Product Card -->
                <div class="col-md-12">
                    <table class="table table-bordered table-hover Datables">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Product Info</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Stocks</th>
                                <th class="text-center">Selling Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $data)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://placehold.co/300x300?text=RAM" width="50"
                                            class="rounded border" alt="">
                                        <div class="">
                                            Name: <span class="fw-semibold">{{ $data->name }}</span><br>
                                            SKU: <span class="fw-semibold">{{ $data->sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{ $data->brand }}</td>
                                <td class="text-center align-middle">{{ $data->stock_quantity }}</td>
                                <td class="text-center align-middle">₱{{ number_format($data->selling_price, 2) }}</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-outline-info me-1" value="{{ $data->id }}"
                                        id="getInfoProduct">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary addToCart" data-id="{{ $data->id }}"
                                        data-name="{{ $data->name }}" data-price="{{ $data->selling_price }}"
                                        data-stock="{{ $data->stock_quantity }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- RIGHT SIDE - CART -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Current Sale</h6>
                </div>
                <div class="card-body p-0">
                    <!-- Cart Table -->
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th width="80">Qty</th>
                                    <th width="100">Price</th>
                                    <th width="100">Subtotal</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Totals -->
                <div class="card-body border-top">
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span class="fw-bold fs-5 text-primary" id="cartTotal"></span>
                    </div>
                    <hr>
                    <!-- Payment -->
                    <div class="mb-2">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" class="form-control" id="amountPaid">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Change</span>
                        <span class="fw-bold text-success" id="changeDisplay">₱0.00</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" id="completeSale">
                            Complete Sale
                        </button>
                        <button class="btn btn-outline-danger">
                            Cancel Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Info Modal -->
<div class="modal fade" id="productInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box-open me-2 text-info"></i>
                    Product Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <img src="https://placehold.co/400x400?text=Product" class="img-fluid rounded border p-2 mb-3"
                            alt="Product Image">
                    </div>
                    <div class="col-md-7">
                        <h4 class="fw-bold"></h4>
                        <p class="text-muted mb-2" id="getSKU"></p>
                        <span class="badge mb-3" id="getStatusStocks"></span>
                        <div class="mb-2">
                            <strong>Brand:</strong> <span id="getBrand"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Category:</strong> <span id="getCategory"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Current Stock:</strong> <span id="getStocks"></span> pcs
                        </div>
                        <div class="mb-3">
                            <strong>Selling Price:</strong>
                            <span class="fs-5 text-primary fw-bold" id="getPrice"></span>
                        </div>
                        <hr>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Description
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">Placeholder content for this accordion, which is
                                        intended to demonstrate the <code>.accordion-flush</code> class. This is the
                                        first item’s accordion body.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-dark">
                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add To Cart Script in POS -->
<script>
let cart = [];
let total = 0;
let change = 0;

$(document).on('click', '.addToCart', function() {
    let id = $(this).data("id");
    let name = $(this).data("name");
    let price = parseFloat($(this).data("price"));
    let stock = parseInt($(this).data("stock"));

    let existing = cart.find(item => item.product_id === id);

    if (existing) {
        if (existing.quantity < stock) {
            existing.quantity += 1;
        } else {
            toastr.error("Error", "Stock limit reached");
        }
    } else {
        cart.push({
            product_id: id,
            name: name,
            price: price,
            quantity: 1,
            stock: stock
        });
    }

    renderCart();
});

function renderCart() {
    let tbody = $("#cartTableBody");
    tbody.empty();

    total = 0;

    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        tbody.append(`
            <tr>
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control form-control-sm"
                                    max="${item.stock}" value="${item.quantity}"
                                    onchange="updateQty(${index}, this.value)">
                </td>
                <td>${item.price.toLocaleString('en-PH')}</td>
                <td>${subtotal.toLocaleString('en-PH')}</td>
                <td>
                    <button onclick="removeItem(${index})" class="btn btn-sm btn-danger">X</button>
                </td>
            </tr>
            `)
    });

    $("#cartTotal").text(
        total.toLocaleString('en-PH', {
            style: 'currency',
            currency: 'PHP'
        })
    );
    calculateChange();
}

function calculateChange() {

    let paid = parseFloat($("#amountPaid").val()) || 0;

    change = paid - total;

    if (change < 0) {
        $("#changeDisplay")
            .removeClass("text-success")
            .addClass("text-danger")
            .text("₱0.00");
    } else {
        $("#changeDisplay")
            .removeClass("text-danger")
            .addClass("text-success")
            .text(
                change.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                })
            );
    }
    if (paid < total) {
        $("#completeSale").prop("disabled", true);
    } else {
        $("#completeSale").prop("disabled", false);
    }
}
$(document).on("input", "#amountPaid", function() {
    calculateChange();
});


function updateQty(index, qty) {
    qty = parseInt(qty);

    if (qty <= cart[index].stock) {
        cart[index].quantity = qty;
    } else {
        alert("Exceeds stock.");
    }

    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}
</script>

<script>
$(document).on('click', '#completeSale', function() {
    $.ajax({
        type: "Post",
        url: "/admin/sales/store",
        data: {
            cart: cart,
            change: change,
            amount_paid: parseFloat($("#amountPaid").val()) || 0,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(res) {
            Swal.fire({
                title: "Sale Complete",
                text: "Invoice is " + res.invoice_no,
                icon: "success",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
            cart = [];
            renderCart();
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});

$(document).ready(function() {

    $(document).on('click', '#getInfoProduct', function() {
        var product_id = $(this).val();
        $("#productInfoModal").modal('show');
        $.ajax({
            type: "GET",
            url: "/admin/product/info/" + product_id,
            success: function(res) {
                $("#getSKU").text(res.data.sku);
                $("#getBrand").text(res.data.brand);
                $("#getCategory").text(res.data.category_name);
                let stock = res.data.stock_quantity;
                let min = res.data.minimum_stock;
                let badge = $("#getStatusStocks");
                badge.attr("class", "badge mb-3");
                badge.removeClass("bg-success bg-warning bg-danger");
                if (stock == 0) {
                    badge.addClass("bg-danger");
                    badge.text("Out of Stock");
                } else if (stock <= min) {
                    badge.addClass("bg-warning text-dark");
                    badge.text("Low Stock");
                } else {
                    badge.addClass("bg-success");
                    badge.text("In Stock");
                }
                $("#getStocks").text(res.data.stock_quantity);
                let formatted = res.data.selling_price.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                });
                $("#getPrice").text(formatted);
            }
        });
    });
});
</script>

@endsection