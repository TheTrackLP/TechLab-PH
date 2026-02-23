@extends('admin.body.header')
@section('admin')

<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Stock Movements</h1>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Movement Type</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <option value="sale">Sale</option>
                        <option value="restock">Restock</option>
                        <option value="return">Return</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>

                <div class="col-md-3 d-grid">
                    <button class="btn btn-dark">
                        Generate
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Movement Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th class="text-start">Product</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Reference</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2026-02-21</td>
                            <td class="text-start">Kingston DDR4 8GB</td>
                            <td>
                                <span class="badge bg-danger">Sale</span>
                            </td>
                            <td class="text-danger fw-bold">-2</td>
                            <td>Invoice: TL-2026-00012</td>
                            <td>Admin</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>2026-02-22</td>
                            <td class="text-start">Kingston DDR4 8GB</td>
                            <td>
                                <span class="badge bg-success">Restock</span>
                            </td>
                            <td class="text-success fw-bold">+10</td>
                            <td>Restock #5</td>
                            <td>Admin</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection