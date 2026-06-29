<script setup>
import { computed, ref } from "vue";
import { Modal } from "bootstrap";
import { currencyFormat, openModal } from "@/reuseables";
import Swal from "sweetalert2";
import { router, useForm } from "@inertiajs/vue3";

const modalRef = ref(null);
const amountPaid = ref("");
const accordionOpen = ref(false);
const selectedProductID = ref("");

const getBrand = ref("");
const getCategory = ref("");
const getStocks = ref("");
const getMinStocks = ref("");
const getSellingPrice = ref("");
const getProductDesc = ref("");

const openShowModal = (product) => {
    selectedProductID.value = product.id;
    getBrand.value = product.brand;
    getCategory.value = product.cat_name;
    getStocks.value = product.stock_quantity;
    getMinStocks.value = product.minimum_stock;
    getSellingPrice.value = product.selling_price;
    getProductDesc.value = product.description;
    openModal(modalRef);
};

const form = useForm({
    amountPaid: "",
    products: [],
});

const getProductDetails = computed(() => {
    return (
        props.products.find(
            (product) => product.id == selectedProductID.value,
        ) || null
    );
});

const cartProducts = ref([]);
const qty = ref(1);
let total = ref(0);
let buttonSale = ref(false);
const change = ref(0);

const addToCartProduct = (product) => {
    selectedProductID.value = product.id;
    buttonSale = true;

    if (!product) {
        return;
    }

    const existing = cartProducts.value.find((item) => item.id == product.id);

    if (existing) {
        existing.qty += qty.value;
    } else {
        cartProducts.value.push({
            product_id: product.id,
            name: product.name,
            selling_price: product.selling_price,
            stock_quantity: product.stock_quantity,
            qty: qty.value,
        });
    }
};

const itemSubtotal = (item) => {
    return item.selling_price * item.qty;
};

const grandTotal = computed(() => {
    return cartProducts.value.reduce((total, item) => {
        return total + item.selling_price * item.qty;
    }, 0);
});

const updateQty = (index, qty) => {
    if (qty <= cartProducts.value[index].stock_quantity) {
        cartProducts.value[index].qty = qty;
    } else {
        Swal.fire({
            icon: "warning",
            title: "Out of Stock",
            text: "Not enough stock available.",
            timer: 1000,
        });
    }
};
const removeFromCart = (item) => {
    cartProducts.value.splice(item, 1);
    if (cartProducts.value.length === 0) {
        buttonSale = false;
    }
};

const completedSale = () => {
    if (cartProducts.value.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Empty Cart",
            text: "Please add products to cart first.",
        });
        return;
    }

    if (form.amountPaid < grandTotal.value) {
        Swal.fire({
            icon: "warning",
            title: "Insufficient Amount",
            text: "Amount paid is less than the total.",
        });
        return;
    }

    router.post(
        route("sales.store"),
        {
            products: cartProducts.value,
            amount_paid: form.amountPaid,
            change: change.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                cartProducts.value = [];
                form.amountPaid = 0;
                Swal.fire({
                    icon: "success",
                    title: "Sale Complete!",
                    text: "Transaction recorded successfully.",
                });
            },
        },
    );
};

const calculateChange = () => {
    change.value = form.amountPaid - grandTotal.value;
};

const clearProductsCart = () => {
    if (cartProducts.value.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Empty",
            text: "Empty Cart.",
        });
    } else {
        Swal.fire({
            title: "Clear Cart?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Clear it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                cartProducts.value = [];
                form.amountPaid = "";
                change.value = "";
            }
        });
    }
};

const props = defineProps({
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
        <div class="row">
            <div class="col-md-8">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h4>Point of Sale</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Products</th>
                                        <th class="text-center">
                                            Selling Price
                                        </th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(product, index) in products"
                                        :key="index"
                                    >
                                        <td class="text-center">
                                            <img
                                                src="/assets/img/no-image.png"
                                                width="80"
                                                class="rounded border mx-auto d-block"
                                                alt="{{ product.name }}"
                                            />
                                        </td>
                                        <td class="align-middle">
                                            <div
                                                class="d-flex align-items-center gap-3"
                                            >
                                                <div class="">
                                                    <p>
                                                        Name:
                                                        <span
                                                            class="fw-semibold"
                                                            >{{
                                                                product.name
                                                            }}</span
                                                        ><br />
                                                    </p>
                                                    <p>
                                                        Brand:
                                                        <span
                                                            class="fw-semibold"
                                                            >{{
                                                                product.brand
                                                            }}</span
                                                        >
                                                    </p>
                                                    <p>
                                                        Stocks:
                                                        <span
                                                            class="fw-semibold"
                                                            >{{
                                                                product.stock_quantity
                                                            }}</span
                                                        >
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p>
                                                {{
                                                    currencyFormat(
                                                        product.selling_price,
                                                    )
                                                }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <button
                                                type="button"
                                                class="btn btn-info text-white"
                                                @click="openShowModal(product)"
                                            >
                                                <i
                                                    class="fa-solid fa-circle-plus"
                                                ></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                @click="
                                                    addToCartProduct(product)
                                                "
                                            >
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">Current Sale</h6>
                    </div>
                    <div class="card-body p-0">
                        <!-- Cart Table -->
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th width="80">Qty</th>
                                        <th width="100">Price</th>
                                        <th width="100">Subtotal</th>
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in cartProducts"
                                        :key="index"
                                    >
                                        <td>{{ item.name }}</td>
                                        <td>
                                            <input
                                                type="number"
                                                class="form-control form-control-sm"
                                                :max="item.stock"
                                                :value="item.qty"
                                                @change="
                                                    updateQty(
                                                        index,
                                                        $event.target.value,
                                                    )
                                                "
                                            />
                                        </td>
                                        <td>
                                            {{
                                                currencyFormat(
                                                    item.selling_price,
                                                )
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                currencyFormat(
                                                    itemSubtotal(item),
                                                )
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                @click="removeFromCart(item)"
                                                class="btn btn-sm btn-danger"
                                            >
                                                X
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Totals -->
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span class="fw-bold fs-5 text-primary">{{
                                currencyFormat(grandTotal)
                            }}</span>
                        </div>
                        <hr />
                        <!-- Payment -->
                        <div class="mb-2">
                            <label class="form-label">Amount Paid</label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="form.amountPaid"
                                @input="calculateChange"
                            />
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Change</span>
                            <span class="fw-bold text-success">
                                {{ currencyFormat(change) }}</span
                            >
                        </div>
                        <div class="d-grid gap-2">
                            <button
                                v-if="buttonSale"
                                class="btn btn-success"
                                @click="completedSale"
                            >
                                Complete Sale
                            </button>
                            <button
                                class="btn btn-outline-danger"
                                @click="clearProductsCart"
                            >
                                Cancel Sale
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" ref="modalRef" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-box-open me-2 text-info"></i>
                        Product Information
                    </h5>
                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 text-center">
                            <img
                                src="/assets/img/no-image.png"
                                class="img-fluid rounded border p-2 mb-3"
                                alt="Product Image"
                            />
                        </div>
                        <div class="col-md-7">
                            <h4 class="fw-bold"></h4>
                            <p class="text-muted mb-2" id="getSKU"></p>
                            <span
                                class="badge mb-3"
                                id="getStatusStocks"
                            ></span>
                            <div class="mb-2">
                                <strong>Brand:</strong>
                                <span>{{ getBrand }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Category:</strong>
                                <span>{{ getCategory }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Current Stock:</strong>
                                <span
                                    v-if="getStocks > getMinStocks"
                                    class="badge rounded-pill text-bg-success"
                                    >{{ getStocks }}</span
                                >
                                <span
                                    v-if="getStocks < getMinStocks"
                                    class="badge rounded-pill text-bg-danger"
                                    >{{ getStocks }}</span
                                >
                            </div>
                            <div class="mb-2">
                                <strong>Selling Price:</strong>
                                <span class="text-success">{{
                                    currencyFormat(getSellingPrice)
                                }}</span>
                            </div>
                            <hr />
                            <div
                                class="p-3 d-flex justify-content-between align-items-center"
                                style="cursor: pointer; background: #e8f0fe"
                                @click="accordionOpen = !accordionOpen"
                            >
                                <span>Product Details</span>
                                <i
                                    :class="
                                        accordionOpen
                                            ? 'bi bi-chevron-up'
                                            : 'bi bi-chevron-down'
                                    "
                                ></i>
                            </div>
                            <div v-if="accordionOpen" class="p-3 border">
                                <small>
                                    {{ getProductDesc }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
