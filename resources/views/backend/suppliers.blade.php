@extends('admin.body.header')
@section('admin')

@php
$i = 1;
@endphp
<div class="container-fluid px-4">
    <h2 class="mt-4 mb-4">Supplier Management</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Supplier Form
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier.store') }}" method="post" id="supplierForm">
                        @csrf
                        <div class="row">
                            <div class="col mb-3">
                                <input type="hidden" name="id" id="id">
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
                                @foreach ($suppliers as $data)
                                <tr>
                                    <td class="text-center align-middle">{{$i++}}</td>
                                    <td class="text-center align-middle"><strong>{{ $data->name }}</strong></td>
                                    <td class="text-center align-middle">{{ ucwords($data->supplier_type) }}</td>
                                    <td class="text-center align-middle">
                                        {{ !empty($data->contact_person) ? $data->contact_person : '—' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ !empty($data->phone) ? $data->phone : '—' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($data->is_active == 1)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-sm btn-warning" value="{{ $data->id }}"
                                            id="editSupplier"><i class="fa-solid fa-pen-to-square"></i></button>
                                        @if($data->is_active == 1)
                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('supplier.status', $data->id) }}"><i
                                                class="fa-solid fa-circle-plus"></i></a>
                                        @elseif($data->is_active == 0)
                                        <a class="btn btn-sm btn-danger"
                                            href="{{ route('supplier.status', $data->id) }}"><i
                                                class="fa-solid fa-circle-minus"></i></a>
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
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '#editSupplier', function() {
        var supplier_id = $(this).val();

        $.ajax({
            type: "Get",
            url: "/admin/suppliers/edit/" + supplier_id,
            success: function(res) {
                $("#name").val(res.data.name);
                $("#supplier_type").val(res.data.supplier_type);
                $("#contact_person").val(res.data.contact_person);
                $("#phone").val(res.data.phone);
                $("#address").val(res.data.address);
                $("#notes").val(res.data.notes);
                $("#id").val(supplier_id);
                $("#supplierForm").attr('action', "{{ route('supplier.update') }}")
            }
        });
    });
});
</script>
@endsection