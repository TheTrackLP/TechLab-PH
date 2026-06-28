<script setup>
import { computed, nextTick, ref } from "vue";
import { Modal } from "bootstrap";

const modalRef = ref(null);
let modalInstance = null;
const selectedProductID = ref("");
const getBrand = ref("");
const getCategory = ref("");

const openShowProductModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const openShowModal = (product) => {
    selectedProductID.value = product.id;
    getBrand.value = product.brand;
    getCategory.value = product.category;

    openShowProductModal();
};

const showModalProduct = computed(() => {
    return (
        props.products.find(
            (product) => product.id == selectedProductID.value,
        ) || null
    );
});

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
                                                class="rounded border mx-auto d-block border"
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
                                                                product.sku
                                                            }}</span
                                                        >
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p>
                                                {{
                                                    product.selling_price.toLocaleString(
                                                        "en-PH",
                                                        {
                                                            style: "currency",
                                                            currency: "PHP",
                                                        },
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
                                <tbody id="cartTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Totals -->
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span
                                class="fw-bold fs-5 text-primary"
                                id="cartTotal"
                            ></span>
                        </div>
                        <hr />
                        <!-- Payment -->
                        <div class="mb-2">
                            <label class="form-label">Amount Paid</label>
                            <input
                                type="number"
                                class="form-control"
                                id="amountPaid"
                            />
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Change</span>
                            <span
                                class="fw-bold text-success"
                                id="changeDisplay"
                                >₱0.00</span
                            >
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" id="completeSale">
                                Complete Sale
                            </button>
                            <button class="btn btn-outline-danger">
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
                                src="https://placehold.co/400x400?text=Product"
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
                                <span id="getCategory"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Current Stock:</strong>
                                <span id="getStocks"></span> pcs
                            </div>
                            <div class="mb-3">
                                <strong>Selling Price:</strong>
                                <span
                                    class="fs-5 text-primary fw-bold"
                                    id="getPrice"
                                ></span>
                            </div>
                            <hr />
                            <div
                                class="accordion accordion-flush"
                                id="accordionFlushExample"
                            >
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne"
                                            aria-expanded="false"
                                            aria-controls="flush-collapseOne"
                                        >
                                            Accordion Item #1
                                        </button>
                                    </h2>
                                    <div
                                        id="flush-collapseOne"
                                        class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample"
                                    >
                                        <div class="accordion-body">
                                            Placeholder content for this
                                            accordion, which is intended to
                                            demonstrate the
                                            <code>.accordion-flush</code> class.
                                            This is the first item’s accordion
                                            body.
                                        </div>
                                    </div>
                                </div>
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
                    <button class="btn btn-dark">
                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
