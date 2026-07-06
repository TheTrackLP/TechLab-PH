<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";
import { currencyFormat } from "@/reuseables";

const selectedSaleID = ref("");
const saleItems = ref([]);
const selectedSale = ref(null);
const selectedProductID = ref([]);

watch(selectedSale, (newValue) => {
    if (!newValue) return;
    axios.get(`/returns/sale-items/${newValue.id}`).then((res) => {
        saleItems.value = res.data;
    });
});
const selectAllItems = (e) => {
    if (e.target.checked) {
        selectedProductID.value = saleItems.value.map((p) => p.id);
    } else {
        selectedProductID.value = [];
    }
};
const searchSale = () => {
    const getInvoice = props.sales.find(
        (sale) => sale.invoice_no == selectedSaleID.value,
    );

    if (getInvoice) {
        selectedSale.value = getInvoice;
        selectedProductID.value = [];
    } else {
        Swal.fire({
            icon: "warning",
            title: "Not Found",
            text: "Invoice not found.",
        });
    }
};

const returnSubTotal = (items) => {
    return items.selling_price_snapshot * items.quantity;
};
const returnForm = useForm({
    returnReason: "",
    returnType: "",
    returnNote: "",
});

const addReturnItems = (items) => {
    return items.product_name;
};

const props = defineProps({
    sales: Array,
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
            <h4>Product Return/s</h4>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Search Invoice</h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="selectedSaleID"
                                placeholder="Enter invoice number (ex: TL-2026-00001)"
                            />
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-dark" @click="searchSale()">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Invoice Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Invoice:</strong>
                            <span>{{ selectedSale?.invoice_no }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Customer:</strong>
                            <span>{{ selectedSale?.customer_name }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Date:</strong>
                            <span>{{ selectedSale?.completed_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Purchased Details</h6>
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">
                                    <input
                                        class="form-check-input border border-success"
                                        type="checkbox"
                                        @change="selectAllItems"
                                    />
                                </th>
                                <th class="text-center">Product</th>
                                <th class="text-center">QTY Bought</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Return QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(items, index) in saleItems"
                                :key="index"
                            >
                                <td class="text-center align-middle">
                                    <input
                                        class="form-check-input border border-success"
                                        type="checkbox"
                                        :value="items.id"
                                        v-model="selectedProductID"
                                    />
                                </td>
                                <td>{{ items.product_name }}</td>
                                <td class="text-center align-middle">
                                    {{ items.quantity }}
                                </td>
                                <td class="text-center align-middle">
                                    {{
                                        currencyFormat(
                                            items.selling_price_snapshot,
                                        )
                                    }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ currencyFormat(returnSubTotal(items)) }}
                                </td>
                                <td class="text-center align-middle">
                                    <input
                                        type="number"
                                        :max="items.quantity"
                                        class="form-control"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Return Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Return Reason:</strong>
                            <select
                                v-model="returnForm.returnReason"
                                class="form-select"
                            >
                                <option>Select an Option</option>
                                <option value="defective">Defective</option>
                                <option value="wrong_item">Wrong Item</option>
                                <option value="change_mind">
                                    Customer Changed Mind
                                </option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <strong>Return Type:</strong>
                            <select
                                v-model="returnForm.returnType"
                                class="form-select"
                            >
                                <option>Select on Option</option>
                                <option class="refund">Refund</option>
                                <option class="exchange">Exchange</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <strong>Notes:</strong>
                            <input
                                type="text"
                                class="form-control"
                                v-model="returnForm.returnNote"
                            />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger px-5">
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="btn btn-success px-5 mx-3"
                        @click="addReturnItems"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
