<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const selectProductid = ref("");

const selectProduct = computed(() => {
    return (
        props.products.find((product) => product.id == selectProductid.value) ||
        null
    );
});

watch(selectProductid, (value) => {
    const product = props.products.find((p) => p.id == value);
});

const productRestockForm = useForm({});

const props = defineProps({
    suppliers: Array,
    products: Array,
});
</script>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    layout: AdminLayout,
};
</script>
<template>
    <div>
        <div class="container-fluid mt-3">
            <h1>Restock Products</h1>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="">Supplier</label>
                            <select name="" class="form-select">
                                <option value="">Select Supplier</option>
                                <option
                                    v-for="(supp, index) in suppliers"
                                    :key="index"
                                    :value="supp.id"
                                >
                                    {{ supp.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Reference No.</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Reference #"
                            />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Notes.</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Notes"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Add Product</h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Product</label>
                            <select
                                class="select2 form-control"
                                v-model="selectProductid"
                            >
                                <option>Select Product</option>
                                <option
                                    v-for="(product, index) in products"
                                    :key="index"
                                    :value="product.id"
                                >
                                    {{ product.name }} | {{ product.sku }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <div class="col-md-1">
                                <label class="form-label">Stock</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="selectProduct?.stock_quantity"
                                    readonly
                                />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Current Cost</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="selectProduct?.selling_price"
                                    readonly
                                />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Selling Price</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="current_selling_price"
                                    readonly
                                />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">New Cost</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="new_cost_price"
                                    step="any"
                                />
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Qty</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="quantity"
                                />
                            </div>
                            <div class="col-md-1">
                                <button
                                    class="btn btn-dark w-100"
                                    id="addRestock"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div
                            class="alert alert-info py-2 mb-0"
                            id="profit_preview"
                        >
                            Estimated Profit Per Unit:
                            <span id="estimated"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5>Restock Items</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Cost</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <strong>Total Items:</strong>
                            <span id="totalItems"></span>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Total Cost:</strong>
                            <span id="overAll"></span>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-success px-4" id="saveRestock">
                            <i class="fa-solid fa-save me-1"></i>
                            Save Restock
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
