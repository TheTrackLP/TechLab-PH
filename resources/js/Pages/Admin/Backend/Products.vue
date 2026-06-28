<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { nextTick, ref } from "vue";

const modalRef = ref(null);
const productsFormMode = ref('create');
let modalInstance = null;

const openProductFormModal = () =>{
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
}

const openProductModal = () => {
    productsFormMode.value = 'create';
    productsForm.reset();
    openProductFormModal.show();
}

const openCreateForm = () => {
    productsFormMode.value = 'create';
    productsForm.reset();
    openProductFormModal();
}

const closeProductModalForm = () => {
    modalInstance?.hide();
    productsForm.reset();
}

const productsForm = useForm({
    id: "",
    category_id: "",
    supplier_id: "",
    brand: "",
    name: "",
    sku: "",
    description: "",
    stock_quantity: "",
    minimum_stock: "",
    cost_price: "",
    selling_price: "",
});

const getProductsData = (product) => {
    productsFormMode.value = 'edit';
    productsForm.id = product.id;
    productsForm.category_id = product.category_id;
    productsForm.supplier_id = product.supplier_id;
    productsForm.brand = product.brand;
    productsForm.name = product.name;
    productsForm.sku = product.sku;
    productsForm.description = product.description;
    productsForm.stock_quantity = product.stock_quantity;
    productsForm.minimum_stock = product.minimum_stock;
    productsForm.cost_price = product.cost_price;
    productsForm.selling_price = product.selling_price;
    openProductFormModal();
}
const props = defineProps({
    products: Array,
    suppliers: Array,
    categories: Array,
});

const changeStatus = (product) => {
    productsForm.post(route('products.status', product.id));
}

const submit = () => {
    if(productsFormMode.value === 'create'){
        productsForm.post(route('products.store'), {
            preserveScroll: true,
            onSuccess: () => {
                productsForm.reset();
                closeProductModalForm();
            }
        });
    } else {
        productsForm.post(route('products.update', productsForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                productsForm.reset();
                closeProductModalForm();
            }
        });
    }
}

</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { route } from "ziggy-js";
export default {
    layout: AdminLayout,
};
</script>
<template>
    <div>
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <button
                        type="button"
                        class="btn btn-primary px-4 float-end"
                        @click="openCreateForm"
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
                            <tr v-for="(product, index) in products" :key="index">
                                <td class="text-center align-middle">{{ index + 1 }}</td>
                                <td class="text-center">
                                    <img
                                        class="p-1 bg-primary"
                                        src="/assets/img/no-image.png"
                                        width="70"
                                    />
                                </td>
                                <td class="align-middle">
                                    <p>{{ product.name }}</p>
                                </td>
                                <td class="text-center align-middle">
                                    {{ product.category_id }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ product.stock_quantity }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ product.selling_price.toLocaleString('en-PH',{
                                        style: 'currency',
                                        currency: 'PHP',
                                    }) }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge rounded-pill text-bg-danger" v-if="product.stock_quantity === 0">Out of Stock</span>
                                    <span class="badge rounded-pill text-bg-success" v-else-if="product.stock_quantity > product.minimum_stock">Instock</span>
                                    <span class="badge rounded-pill text-bg-warning" v-else-if="product.stock_quantity < product.minimum_stock">Low Stock</span>
                                    <span class="badge rounded-pill text-bg-danger" v-if="product.is_active === 0">Inactive</span>
                                    <span class="badge rounded-pill text-bg-success" v-else-if="product.is_active === 1">Active</span>
                                </td>
                                <td class="text-center align-middle">
                                    <button
                                        type="button"
                                        class="btn btn-warning"
                                        @click="getProductsData(product)"
                                    >
                                        <i
                                            class="fa-solid fa-pen-to-square"
                                        ></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        @click="changeStatus(product)"
                                        v-if="product.is_active === 0">
                                        <i class="fa-solid fa-circle-minus"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        @click="changeStatus(product)"
                                        v-else-if="product.is_active === 1">
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </button>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="modalRef" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="submit">
                    <div class="modal-content">
                        <div class="modal-header bg-black">
                            <h3 class="text-white">Product Form</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Basic Information</h5>
                                    <input type="hidden" v-model="productsForm.id">
                                    <div class="form-group mb-3">
                                        <label for="">Product Name:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Product Name..."
                                            v-model="productsForm.name"
                                        />
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="">Brand:</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Enter Brand Name..."
                                                v-model="productsForm.brand"
                                            />
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="">SKU:</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Enter SKU..."
                                                v-model="productsForm.sku"
                                            />
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="">Category:</label>
                                            <select class="form-select" v-model="productsForm.category_id">
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
                                            <select class="form-select" v-model="productsForm.supplier_id">
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
                                                v-model="productsForm.cost_price"
                                            />
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="">Selling Price:</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="Enter Selling Price..."
                                                v-model="productsForm.selling_price"
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
                                                v-model="productsForm.stock_quantity"
                                            />
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="">Minimum Stock:</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="Enter Stock Minimum Stock"
                                                v-model="productsForm.minimum_stock"
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
                                                v-model="productsForm.description"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="closeProductModalForm">Close</button>
                            <button type="submit" class="btn btn-success px-4">
                                {{ productsForm.processing ? 'Saving...' : productsFormMode === 'create' ? 'Add' : 'Update' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
