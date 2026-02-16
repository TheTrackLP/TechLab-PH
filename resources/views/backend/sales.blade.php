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
                                    <button class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal"
                                        data-bs-target="#productInfoModal">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary">
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
                            <tbody>
                                <tr>
                                    <td>
                                        Zodiac Capricorn Build Powered by ASUS - R5 7500F / 32GB DDR5 / 500GB NVMe / RTX
                                        5060 OC PC Build

                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" value="1">
                                    </td>
                                    <td>1000</td>
                                    <td class="fw-bold">1000</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Totals -->
                <div class="card-body border-top">

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span class="fw-semibold">₱1,000</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span class="fw-bold fs-5 text-primary">₱1,000</span>
                    </div>
                    <hr>
                    <!-- Payment -->
                    <div class="mb-2">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" class="form-control">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Change</span>
                        <span class="fw-bold text-success">₱0.00</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success">
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
                        <p class="text-muted mb-2">SKU: KF8GB3200</p>
                        <span class="badge bg-success mb-3">In Stock</span>
                        <div class="mb-2">
                            <strong>Brand:</strong> Kingston
                        </div>
                        <div class="mb-2">
                            <strong>Category:</strong> RAM
                        </div>
                        <div class="mb-2">
                            <strong>Current Stock:</strong> 15 pcs
                        </div>
                        <div class="mb-3">
                            <strong>Selling Price:</strong>
                            <span class="fs-5 text-primary fw-bold">₱1,000</span>
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


@endsection