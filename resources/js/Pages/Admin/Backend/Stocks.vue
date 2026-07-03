<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { currencyFormat } from "@/reuseables";

const selectProductid = ref("");
const restockAmount = ref("");
const restockQTY = ref("");

const selectProduct = computed(() => {
    return (
        props.products.find((product) => product.id == selectProductid.value) ||
        null
    );
});

const restockForm = useForm({
    supplier_id: "",
    referenceNo: "",
    notes: "",
    restockProducts: [],
});

const cartRestock = ref([]);
const qty = ref(0);
let total = ref(0);
const addtoRestock = () => {
    if (!selectProduct.value) {
        Swal.fire({
            icon: "warning",
            title: "Error!",
            text: "Select Item to Restock.",
            timer: 1000,
        });
        return;
    } else {
        cartRestock.value.push({
            product_id: selectProduct.value.id,
            product_name: selectProduct.value.name,
            productNewCost: restockAmount.value,
            productNewQTY: restockQTY.value,
        });
    }
};

const restockSubtotal = (items) => {
    return items.productNewCost * items.productNewQTY;
};

const removeStock = (items) => {
    cartRestock.value.splice(items, 1);
};

const grandTotalRestock = computed(() => {
    return cartRestock.value.reduce((total, item) => {
        return total + item.productNewCost * item.productNewQTY;
    }, 0);
});
const grandTotalRestockItems = computed(() => {
    return cartRestock.value.length;
});

const completedRestock = () => {
    if (cartRestock.value.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Fill up the Restock Items.",
            timer: 2009,
        });
        return;
    }
    router.post(
        route("stocks.store"),
        {
            restockProducts: cartRestock.value,
            supplier_id: restockForm.supplier_id,
            referenceNo: restockForm.referenceNo,
            notes: restockForm.notes,
            totalAmountRestock: grandTotalRestockItems.value,
            totalItemsRestock: grandTotalRestock.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                ((cartRestock.value = []),
                    (restockForm.referenceNo = ""),
                    (restockForm.supplier_id = ""),
                    (restockForm.notes = ""),
                    (restockQTY.value = ""),
                    (restockAmount.value = ""),
                    (selectProductid.value = ""),
                    Swal.fire({
                        icon: "success",
                        title: "Complete!",
                        text: "Product/s Restock Successfully.",
                        timer: 2000,
                    }));
            },
        },
    );
};

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
                            <select
                                class="form-select"
                                v-model="restockForm.supplier_id"
                            >
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
                                v-model="restockForm.referenceNo"
                            />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Notes.</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Notes"
                                v-model="restockForm.notes"
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
                                :value="selectProduct?.cost_price"
                                readonly
                            />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Selling Price</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="selectProduct?.selling_price"
                                readonly
                            />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">New Cost</label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="restockAmount"
                                step="any"
                            />
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Qty</label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="restockQTY"
                            />
                        </div>
                        <div class="col-md-1">
                            <button
                                class="btn btn-dark w-100"
                                @click="addtoRestock()"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="alert alert-info py-2 mb-0" id="profit_preview">
                        Estimated Profit Per Unit:
                        <span></span>
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
                        <tr v-for="(items, index) in cartRestock" :key="index">
                            <td class="text-center align-middle">
                                {{ index + 1 }}
                            </td>
                            <td class="align-middle">
                                {{ items.product_name }}
                            </td>
                            <td class="text-center align-middle">
                                {{ currencyFormat(items.productNewQTY) }}
                            </td>
                            <td class="text-center align-middle">
                                {{ currencyFormat(items.productNewCost) }}
                            </td>
                            <td class="text-center align-middle">
                                {{ currencyFormat(restockSubtotal(items)) }}
                            </td>
                            <td class="text-center align-middle">
                                <button
                                    class="btn btn-danger"
                                    @click="removeStock(items)"
                                >
                                    X
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <strong>Total Items:</strong>
                        <span>{{ grandTotalRestockItems }}</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Total Cost:</strong>
                        <span>{{ currencyFormat(grandTotalRestock) }}</span>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button
                        class="btn btn-success px-4"
                        @click="completedRestock"
                    >
                        <i class="fa-solid fa-save me-1"></i>
                        Save Restock
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
