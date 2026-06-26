<script setup>
import { useForm } from "@inertiajs/vue3";
const props = defineProps({
    suppliers: Array,
    categories: Array,
});

const productsForm = useForm({
    id: "",
    category_id: "",
    brand: "",
    name: "",
    sku: "",
    description: "",
    stock_quantity: "",
    minimum_stock: "",
    cost_price: "",
    selling_price: "",
});
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    layout: AdminLayout,
};
</script>
<template>
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <button
                    type="button"
                    class="btn btn-primary px-4 float-end"
                    data-bs-toggle="modal"
                    data-bs-target="#openModalProduct"
                >
                    <i class="fa-solid fa-plus"></i> Add Product
                </button>
                <h4>Product Lists</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Catgory</th>
                            <th class="text-center">Stocks</th>
                            <th class="text-center">Selling Price</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-middle">#</td>
                            <td class="text-center align-middle">Image</td>
                            <td class="align-middle">Product</td>
                            <td class="text-center align-middle">Catgory</td>
                            <td class="text-center align-middle">Stocks</td>
                            <td class="text-center align-middle">
                                Selling Price
                            </td>
                            <td class="text-center align-middle">Status</td>
                            <td class="text-center align-middle">Action</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="openModalProduct" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-black">
                    <h3 class="text-white">Product Form</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Basic Information</h5>
                            <div class="form-group mb-3">
                                <label for="">Product Name:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter Product Name..."
                                />
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Brand:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Brand Name..."
                                    />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">SKU:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter SKU..."
                                    />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Category:</label>
                                    <select name="" class="form-select">
                                        <option>Select Category</option>
                                        <option
                                            v-for="(cate, index) in categories"
                                            :key="index"
                                            :value="cate.id"
                                        >
                                            {{ cate.category_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Supplier:</label>
                                    <select name="" class="form-select">
                                        <option>Select Supplier</option>
                                        <option
                                            v-for="(supp, index) in suppliers"
                                            :key="index"
                                            :value="supp.id"
                                        >
                                            {{ supp.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <h5 class="text-muted mb-3">Pricing</h5>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Cost:</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Item Cost..."
                                    />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Selling Price:</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Selling Price..."
                                    />
                                </div>
                            </div>
                            <hr class="my-4" />
                            <h5 class="text-muted mb-3">Inventory</h5>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Stock Quantity:</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Stock..."
                                    />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Minimum Stock:</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Stock Minimum Stock"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="">Image:</label>
                                    <input
                                        type="file"
                                        name=""
                                        class="form-control"
                                    />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for=""></label>
                                    <img
                                        class="p-1 bg-primary"
                                        src="/assets/img/no-image.png"
                                        width="200"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="">Description:</label>
                                    <textarea
                                        class="form-control"
                                        rows="13"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Close</button>
                    <button type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
