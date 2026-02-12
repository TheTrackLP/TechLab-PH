@extends('admin.body.header')
@section('admin')

<!-- Category Module -->
<div class="container-fluid mt-2">
    <h4 class="mt-4 mb-4">Category Management</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <form action="{{ route('category.store') }}" method="post">
                    @csrf
                    <div class="card-header bg-dark text-white">
                        Add Category
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="">Category name</label>
                            <input type="text" class="form-control" name="category_name"
                                placeholder="Enter Category Name">
                        </div>
                        <div class="mb-3">
                            <label for="">Category Description</label>
                            <textarea class="form-control" rows="4" name="category_description"
                                placeholder="Optional description"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success px-5 ">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Category Lists</div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center" width="35%">Description</th>
                            <th class="text-center">No. Products</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $data)
                        <tr>
                            <td class="text-center align-middle">1</td>
                            <td class="align-middle">
                                <p>{{ $data->category_name }}</p>
                            </td>
                            <td class="align-middle">Description</td>
                            <td class="text-center align-middle">
                                <span class="badge bg-primary">12</span>
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-warning"><i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="btn btn-danger"><i
                                        class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection