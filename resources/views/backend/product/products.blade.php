@extends('admin.body.header')
@section('admin')
@php
$i = 1;
@endphp
<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <button type="button" class="btn btn-primary btn-lg float-end px-5" data-bs-toggle="modal"
            data-bs-target="#addProducts">Add Products</button>
        <h2>Product Management</h2>
    </div>
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                Product List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle Datables">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Image</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Selling Price</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $prod_data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td class="text-center">
                                    <img id=""
                                        src="{{ (!empty($prod_data->image)) ? url($prod_data->image) : url('assets/img/no-image.png') }}"
                                        alt="" class="p-1 bg-primary" width="80">
                                </td>
                                <td>
                                    <strong>{{ $prod_data->name  }}</strong><br>
                                </td>
                                <td class="text-center">{{ $prod_data->category_name }}</td>
                                <td class="text-center">
                                    @if($prod_data->minimum_stock > $prod_data->stock_quantity)
                                    <span class="badge text-bg-danger">{{ $prod_data->stock_quantity }}</span>
                                    @else
                                    <span class="badge text-bg-success">{{ $prod_data->stock_quantity }}</span>
                                    @endif
                                </td>
                                <td class="text-center">₱{{ number_format($prod_data->selling_price, 2) }}</td>
                                <td class="text-center">
                                    @if($prod_data->stock_quantity == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                    @else
                                    <span class="badge bg-success">In Stock</span>
                                    @endif
                                    @if($prod_data->is_active == 0)
                                    <span class="badge bg-danger">Inactive</span>
                                    @else
                                    <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" id="editProduct"
                                        value="{{ $prod_data->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                    @if($prod_data->is_active == 0)
                                    <a href="{{ route('products.status', $prod_data->id) }}"
                                        class="btn btn-sm btn-danger" id="disable"><i
                                            class="fa-solid fa-circle-minus"></i></a>
                                    @elseif($prod_data->is_active == 1)
                                    <a href="{{ route('products.status', $prod_data->id) }}"
                                        class="btn btn-sm btn-success" id="disable"><i
                                            class="fa-solid fa-circle-plus"></i></a>
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
</div>

<div class="modal fade" id="addProducts" tabindex="-1" data-bs-back="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('products.store') }}" method="post" id="productForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-box-open me-2 text-info"></i>
                        Product Form
                    </h5>
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Brand</label>
                                    <input type="text" class="form-control" name="brand" id="brand">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select class="form-select modalSelect2" name="category_id" id="category_id">
                                        <option>Select Category</option>
                                        @foreach ($categories as $data)
                                        <option value="{{ $data->id }}">{{ $data->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">SKU (Optional)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control sku" name="sku" id="getsku"
                                            aria-label="sku" aria-describedby="button-addon2">
                                        <button class="btn btn-primary" type="button" id="createsku">Random
                                            SKU</button>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <h6 class="text-muted mb-3">Pricing</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Cost Price</label>
                                    <input type="number" class="form-control" step="any" name="cost_price"
                                        id="cost_price">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Selling Price</label>
                                    <input type="number" class="form-control" step="any" name="selling_price"
                                        id="selling_price">
                                </div>
                            </div>
                            <hr class="my-4">
                            <h6 class="text-muted mb-3">Inventory</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" name="stock_quantity" id="stock_quantity">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Minimum Stock</label>
                                    <input type="number" class="form-control" name="minimum_stock" id="minimum_stock">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-7 mt-4">
                                    <label class="form-label">Product Image</label>
                                    <input type="file" name="image" id="image">
                                </div>
                                <div class="col-md-5 mt-4">
                                    <img id="showImage" src="{{ url('assets/img/no-image.png') }}" alt=""
                                        class="p-1 bg-primary" width="200">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="12"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-info text-white">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#createsku").click(function() {
        $("#getsku").val(Math.floor(Math.random() * 99999));
    });

    $("#image").change(function(e) {
        var reader = new FileReader;
        reader.onload = function(e) {
            $("#showImage").attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
    });

    $(document).on('click', "#editProduct", function() {
        var prod_id = $(this).val();

        $("#addProducts").modal('show');

        $.ajax({
            type: "Get",
            url: "/admin/products/edit/" + prod_id,
            success: function(res) {
                $("#name").val(res.data.name);
                $("#brand").val(res.data.brand);
                $("#category_id").val(res.data.category_id).trigger("change");
                $("#description").val(res.data.description);
                if (res.data.image) {
                    $("#showImage").attr('src', "{{ asset('') }}" + res.data.image);
                } else {
                    $("#showImage").attr('src', "{{ asset('assets/img/no-image.png') }}");
                }
                $("#getsku").val(res.data.sku);
                $("#cost_price").val(res.data.cost_price);
                $("#selling_price").val(res.data.selling_price);
                $("#stock_quantity").val(res.data.stock_quantity);
                $("#minimum_stock").val(res.data.minimum_stock);
                $("#id").val(prod_id);
                $("#productForm").attr("action", "{{ route('products.update') }}")
            }
        });
    });
});
</script>
@endsection