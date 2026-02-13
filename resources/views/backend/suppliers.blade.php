@extends('admin.body.header')
@section('admin')

<div class="container-fluid px-4">
    <h2 class="mt-4 mb-4">Supplier Management</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        Add Supplier
                    </div>
                    <div class="card-body">
                        <form action="{{ route('supplier.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Supplier Name</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">Supplier Type</label>
                                    <select class="form-select" name="supplier_type" id="supplier_type">
                                        <option value="">Select Type</option>
                                        <option value="online">Online</option>
                                        <option value="local">Local Store</option>
                                        <option value="distributor">Distributor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" name="contact_person" id="contact_person">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" rows="2" name="address" id="address"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" rows="2" name="notes" id="notes"></textarea>
                            </div>
                            <button type="submit" class="btn btn-info text-white w-100">
                                Save Supplier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        Supplier List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Contact</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center align-middle">1</td>
                                        <td class="align-middle"><strong>Shopee Seller A</strong></td>
                                        <td class="text-center align-middle">Online</td>
                                        <td class="align-middle">—</td>
                                        <td class="align-middle">—</td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                            <button class="btn btn-sm btn-danger"><i
                                                    class="fa-solid fa-circle-minus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


@endsection