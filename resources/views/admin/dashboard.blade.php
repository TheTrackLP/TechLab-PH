@extends('admin.body.header')
@section('admin')
@php
$i = 1;
@endphp
<main>
    <div class="container-fluid px-4 py-4" style="background:#f4f6f9; min-height:100vh;">
        <h2 class="mb-4 fw-semibold">Dashboard Overview</h2>
        <div class="row g-4">
            <!-- Total Products -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="small text-white mb-1">Total Products</p>
                            <h3 class="fw-bold mb-0 text-white">{{ $total_products  }}</h3>
                            <small class="text-white">{{ $inactive_products }} Inactive</small>
                        </div>
                        <i class="fas fa-box fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
            <!-- Low Stock -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-warning">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="small text-dark mb-1">Low Stock</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $low_stocks }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
            <!-- Out of Stock -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-danger">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="small text-white mb-1">Out of Stock</p>
                            <h3 class="fw-bold mb-0 text-white">{{ $out_of_stocks }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
            <!-- Suppliers -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm bg-dark">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="small text-white mb-1">Suppliers</p>
                            <h3 class="fw-bold mb-0 text-white">{{ $suppliers }}</h3>
                            <small class="text-white">{{ $inactive_suppliers }} Inactive</small>
                        </div>
                        <i class="fas fa-truck fa-2x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-dark text-white">
                Low Stock Products
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle Datables">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Minimum</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_low_stocks as $low)
                            <tr>
                                <td class="text-center align-middle">{{$i++}}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $low->image ?? asset('assets/img/no-image-png') }}" width="50"
                                            class="rounded border" alt="">
                                        <div class="">
                                            Name: <span class="fw-semibold">{{ $low->name }}</span><br>
                                            SKU: <span class="fw-semibold">{{ $low->sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{ $low->category_name }}</td>
                                <td class="text-center align-middle">{{ $low->stock_quantity }}</td>
                                <td class="text-center align-middle">{{ $low->minimum_stock }}</td>
                                <td class="text-center align-middle">
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
</main>
@endsection