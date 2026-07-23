<script setup>
import { Modal } from "bootstrap";
import { openModal, currencyFormat, closeModal } from "@/reuseables";
import { ref, watch, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";

const modalRepariForm = ref(null);
const modalDiagnoseForm = ref(null);
const selectedCategory = ref(null);
const productItems = ref([]);
const showAddParts = ref(false);

const buttonCancel = ref(false);

const getCustomer = ref("");
const getRepairId = ref("");
const getContact = ref("");
const getDevice = ref("");
const getStatus = ref("");
const getReceivedDate = ref("");
const getPickupDate = ref("");
const getRepairNo = ref("");
const getIssueDesc = ref("");
const getDiagnosis = ref("");
const getLaborFee = ref("");

const repairForm = useForm({
    repairId: "",
    customer_name: "",
    contact_number: "",
    device_type: "",
    device_brand: "",
    issue_description: "",
    diagnosis: "",
    labor_fee: "",
});

watch(selectedCategory, (newValue) => {
    if (!newValue) return;
    axios.get(`/repairs/select-products/${newValue.id}`).then((res) => {
        productItems.value = res.data;
    });
});

const openModalRepairForm = () => {
    openModal(modalRepariForm);
    repairForm.reset();
};

const openModalDiagnoseForm = (repair) => {
    getRepairId.value = repair.id;
    getRepairNo.value = repair.repair_no;
    getCustomer.value = repair.customer_name;
    getContact.value = repair.contact_number;
    getDevice.value = repair.device_type;
    getStatus.value = repair.status;
    getIssueDesc.value = repair.issue_description;
    getReceivedDate.value = repair.created_at;
    getPickupDate.value = repair.pickup_deadline;
    getLaborFee.value = repair.labor_fee;
    getDiagnosis.value = repair.diagnosis;
    repairForm.repairId = repair.id;
    repairForm.customer_name = repair.customer_name;
    repairForm.contact_number = repair.contact_number;
    repairForm.device_type = repair.device_type;
    repairForm.issue_description = repair.issue_description;
    repairForm.device_brand = repair.device_brand;
    repairForm.labor_fee = repair.labor_fee;
    repairForm.diagnosis = repair.diagnosis;
    openModal(modalDiagnoseForm);
};

const closeProductModalForm = () => {
    closeModal(modalDiagnoseForm);
    repairForm.reset();
};

const repairChangeStatus = (valueStatus) => {
    router.post(route("repair.status", getRepairId.value), {
        preserveScroll: true,
        onSuccess: () => {
            repairForm.reset();
            closeProductModalForm();
        },
        btnRepairChangeStatus: valueStatus,
        changeRepairStatusID: getRepairId.value,
    });
};

const repairSubmit = () => {
    repairForm.post(route("repair.store"), {
        preserveScroll: true,
        onSuccess: () => {
            closeProductModalForm();
        },
    });
};

const repairDiagnosisUpdate = () => {
    repairForm.post(route("repair.update", repairForm.repairId), {
        preserveScroll: true,
        onSuccess: () => {
            repairForm.reset();
            closeProductModalForm();
        },
    });
};

const overallAmount = computed(() => {
    return getLaborFee.value;
});

const props = defineProps({
    categories: Array,
    repairs: Array,
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <button
                        class="btn btn-primary px-4 float-end"
                        @click="openModalRepairForm"
                    >
                        <i class="fa-solid fa-plus"></i>Add Repair
                    </button>
                    <h4>Repair Lists</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Repair No.</th>
                                <th class="text-center">Customer Details</th>
                                <th class="text-center">Device</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pickup Deadline</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(repair, index) in repairs" :key="index">
                                <td class="text-center align-middle">
                                    <p>{{ index + 1 }}</p>
                                </td>
                                <td class="text-center align-middle">
                                    <p>{{ repair.repair_no }}</p>
                                </td>
                                <td class="align-middle">
                                    <p>
                                        <strong>Name: </strong
                                        >{{ repair.customer_name }}
                                    </p>
                                    <p>
                                        <strong>Contact: </strong
                                        >{{ repair.contact_number }}
                                    </p>
                                </td>
                                <td class="align-middle">
                                    <p>
                                        {{ repair.device_type }} |
                                        {{ repair.device_brand }}
                                    </p>
                                </td>
                                <td class="text-center align-middle">
                                    <p>
                                        {{
                                            currencyFormat(repair.total_amount)
                                        }}
                                    </p>
                                </td>
                                <td class="text-center align-middle">
                                    <span
                                        class="badge rounded-pill text-bg-secondary"
                                        v-if="
                                            repair.status == 'pending_diagnosis'
                                        "
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-info"
                                        v-else-if="
                                            repair.status == 'awaiting_approval'
                                        "
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-primary"
                                        v-else-if="
                                            repair.status == 'in_progress'
                                        "
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-success"
                                        v-else-if="repair.status == 'completed'"
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-dark"
                                        v-else-if="repair.status == 'released'"
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-warning"
                                        v-else-if="repair.status == 'cancelled'"
                                        >{{ repair.status }}</span
                                    >
                                    <span
                                        class="badge rounded-pill text-bg-danger"
                                        v-else-if="repair.status == 'abandoned'"
                                        >{{ repair.status }}</span
                                    >
                                </td>
                                <td class="text-center align-middle">
                                    <p v-if="repair.pickup_deadline">
                                        {{ repair.pickup_deadline }}
                                    </p>
                                    <p v-else="">Awaiting</p>
                                </td>
                                <td class="text-center align-middle">
                                    <button
                                        class="btn btn-sm btn-info text-white me-1"
                                        @click="openModalDiagnoseForm(repair)"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="modalRepariForm" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="repairSubmit">
                        <div class="modal-header bg-dark text-white">
                            Add Repair
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Customer Name:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="repairForm.customer_name"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label>Contact Number:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="repairForm.contact_number"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <label>Device Type:</label>
                                    <select
                                        class="form-select"
                                        v-model="repairForm.device_type"
                                    >
                                        <option value="">
                                            Select an Option
                                        </option>
                                        <option value="laptop">Laptop</option>
                                        <option value="printer">Printer</option>
                                        <option value="desktop">Desktop</option>
                                        <option value="router">Router</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Device Brand/Model:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="repairForm.device_brand"
                                    />
                                </div>
                                <div class="col-md-12">
                                    <label>Issue Description:</label>
                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        v-model="repairForm.issue_description"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger px-3">
                                Close
                            </button>
                            <button type="submit" class="btn btn-success px-3">
                                Save Repair
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="modalDiagnoseForm" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="text-white">
                            Repair Details <span>{{ getRepairNo }}</span>
                            <input
                                type="hidden"
                                v-model="repairForm.repairId"
                            />
                        </h4>
                    </div>
                    <div class="modal-body">
                        <ul
                            class="nav nav-pills mb-3"
                            id="pills-tab"
                            role="tablist"
                        >
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link active"
                                    id="pills-home-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-details"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-home"
                                    aria-selected="true"
                                >
                                    Repair Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link"
                                    id="pills-profile-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-diagnose"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-profile"
                                    aria-selected="false"
                                >
                                    Diagnose Details
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div
                                class="tab-pane fade show active"
                                id="pills-details"
                                role="tabpanel"
                                aria-labelledby="pills-home-tab"
                                tabindex="0"
                            >
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p>
                                                    <strong
                                                        >Cusomter:
                                                        {{
                                                            getCustomer
                                                        }}</strong
                                                    >
                                                </p>
                                                <p>
                                                    <strong
                                                        >Contact:
                                                        {{ getContact }}</strong
                                                    >
                                                </p>
                                                <p>
                                                    <strong
                                                        >Device:
                                                        {{ getDevice }}</strong
                                                    >
                                                </p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p>
                                                    <strong>Status: </strong>
                                                    <span
                                                        class="badge rounded-pill text-bg-secondary"
                                                        v-if="
                                                            getStatus ==
                                                            'pending_diagnosis'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-info"
                                                        v-else-if="
                                                            getStatus ==
                                                            'awaiting_approval'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-primary"
                                                        v-else-if="
                                                            getStatus.status ==
                                                            'in_progress'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-success"
                                                        v-else-if="
                                                            getStatus ==
                                                            'completed'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-dark"
                                                        v-else-if="
                                                            getStatus ==
                                                            'released'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-warning"
                                                        v-else-if="
                                                            getStatus ==
                                                            'cancelled'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                    <span
                                                        class="badge rounded-pill text-bg-danger"
                                                        v-else-if="
                                                            getStatus ==
                                                            'abandoned'
                                                        "
                                                        >{{ getStatus }}</span
                                                    >
                                                </p>
                                                <p>
                                                    <strong
                                                        >Received:
                                                        {{
                                                            getReceivedDate
                                                        }}</strong
                                                    >
                                                </p>
                                                <p>
                                                    <strong
                                                        >Pickup Date:
                                                        <span
                                                            v-if="getPickupDate"
                                                            >{{
                                                                getPickupDate
                                                            }}</span
                                                        >
                                                        <span v-else=""
                                                            >Awaiting</span
                                                        >
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <p>
                                                <strong
                                                    >Issue Description:</strong
                                                >
                                            </p>
                                            <p class="text-secondary">
                                                {{ getIssueDesc }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <p><strong>Diagnosis</strong></p>
                                            <p>{{ getDiagnosis }}</p>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="bg-light p-3 rounded border"
                                                >
                                                    <div
                                                        class="d-flex justify-content-between mb-2"
                                                    >
                                                        <span>Labor Fee</span>
                                                        <span
                                                            class="labor_fee fw-semibold"
                                                            >{{
                                                                currencyFormat(
                                                                    getLaborFee,
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between mb-2"
                                                    >
                                                        <span>Parts Total</span>
                                                        <span
                                                            class="fw-semibold"
                                                        ></span>
                                                    </div>
                                                    <hr />
                                                    <div
                                                        class="d-flex justify-content-between mb-2"
                                                    >
                                                        <span>Change</span>
                                                        <span
                                                            class="changeDisplayAmount fw-semibold"
                                                            >P 0.00</span
                                                        >
                                                    </div>
                                                    <hr />
                                                    <div
                                                        class="d-flex justify-content-between fs-5"
                                                    >
                                                        <strong
                                                            >Total
                                                            Amount</strong
                                                        >
                                                        <strong
                                                            class="text-success"
                                                            >{{
                                                                currencyFormat(
                                                                    overallAmount,
                                                                )
                                                            }}</strong
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="bg-white p-3 rounded border"
                                                >
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label"
                                                            >Payment Type</label
                                                        >
                                                        <select
                                                            class="form-select"
                                                            id="payment_type"
                                                        >
                                                            <option
                                                                value="cash"
                                                            >
                                                                Cash
                                                            </option>
                                                            <option
                                                                value="gcash"
                                                            >
                                                                GCash
                                                            </option>
                                                            <option
                                                                value="bank_transfer"
                                                            >
                                                                Bank Transfer
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label"
                                                            >Amount Paid</label
                                                        >
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            class="form-control"
                                                            id="amount_paid"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-center"
                                        >
                                            <div>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger"
                                                    data-bs-dismiss="modal"
                                                >
                                                    <i
                                                        class="fa-solid fa-xmark me-1"
                                                    ></i>
                                                    Close
                                                </button>
                                            </div>
                                            <form
                                                @submit.prevent="
                                                    repairChangeStatus
                                                "
                                            ></form>
                                            <div
                                                class="d-flex flex-wrap gap-2 justify-content-end"
                                            >
                                                <template
                                                    v-if="
                                                        getStatus ==
                                                        'pending_diagnosis'
                                                    "
                                                >
                                                </template>
                                                <template
                                                    v-if="
                                                        getStatus ==
                                                        'awaiting_approval'
                                                    "
                                                >
                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary"
                                                        @click="
                                                            repairChangeStatus(
                                                                'in_progress',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-check me-1"
                                                        ></i>
                                                        Approve Repair
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger"
                                                        @click="
                                                            repairChangeStatus(
                                                                'cancelled',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-ban me-1"
                                                        ></i>
                                                        Cancel Repair
                                                    </button>
                                                </template>
                                                <template
                                                    v-if="
                                                        getStatus ==
                                                        'in_progress'
                                                    "
                                                >
                                                    <button
                                                        type="button"
                                                        class="btn btn-success"
                                                        @click="
                                                            repairChangeStatus(
                                                                'completed',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-circle-check me-1"
                                                        ></i>
                                                        Mark as Completed
                                                    </button>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        getStatus == 'completed'
                                                    "
                                                >
                                                    <button
                                                        type="button"
                                                        class="btn btn-info text-white"
                                                        @click="
                                                            repairChangeStatus(
                                                                'released',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-box-open me-1"
                                                        ></i>
                                                        Release Unit
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        @click="
                                                            repairChangeStatus(
                                                                'abandoned',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-clock me-1"
                                                        ></i>
                                                        Mark as Abandoned
                                                    </button>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        getStatus == 'released'
                                                    "
                                                >
                                                    <button
                                                        type="button"
                                                        class="btn btn-warning text-dark"
                                                        @click="
                                                            repairChangeStatus(
                                                                'generate_sale',
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            class="fa-solid fa-receipt me-1"
                                                        ></i>
                                                        Generate Sale
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="tab-pane fade"
                                id="pills-diagnose"
                                role="tabpanel"
                                aria-labelledby="pills-profile-tab"
                                tabindex="0"
                            >
                                <form @submit.prevent="repairDiagnosisUpdate">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="">Diagnosis</label>
                                                <textarea
                                                    rows="5"
                                                    class="form-control border-secondary"
                                                    v-model="
                                                        repairForm.diagnosis
                                                    "
                                                ></textarea>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="">Labor Fee</label>
                                                <input
                                                    type="number"
                                                    class="form-control border-secondary"
                                                    v-model="
                                                        repairForm.labor_fee
                                                    "
                                                />
                                            </div>
                                            <hr />
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="checkDefault"
                                                    v-model="showAddParts"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="checkDefault"
                                                >
                                                    Show Add Parts
                                                </label>
                                            </div>
                                            <div v-show="showAddParts">
                                                <h6 class="mb-3">Add Parts</h6>
                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-3"
                                                >
                                                    <h6 class="mb-0 fw-bold">
                                                        <i
                                                            class="fa-solid fa-screwdriver-wrench me-2 text-primary"
                                                        ></i>
                                                        Add / Modify Parts
                                                    </h6>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm"
                                                        id="currentPartsPreview"
                                                    >
                                                        <i
                                                            class="fa-solid fa-eye me-1"
                                                        ></i>
                                                        View Current Parts
                                                    </button>
                                                </div>
                                                <div
                                                    class="row g-3 align-items-end mb-4"
                                                >
                                                    <div class="col-md-3">
                                                        <label
                                                            class="form-label"
                                                            >Category</label
                                                        >
                                                        <select
                                                            class="form-select border-secondary"
                                                            v-model="
                                                                selectedCategory
                                                            "
                                                        >
                                                            <option value="">
                                                                Select an Option
                                                            </option>
                                                            <option
                                                                v-for="(
                                                                    items, index
                                                                ) in categories"
                                                                :key="index"
                                                                :value="items"
                                                            >
                                                                {{
                                                                    items.category_name
                                                                }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label
                                                            class="form-label"
                                                            >Product</label
                                                        >
                                                        <select
                                                            class="form-select border-secondary"
                                                        >
                                                            <option value="">
                                                                Select an Option
                                                            </option>
                                                            <option
                                                                v-for="(
                                                                    product,
                                                                    index
                                                                ) in productItems"
                                                                :key="index"
                                                                :value="product"
                                                            >
                                                                {{
                                                                    product.name
                                                                }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label
                                                            class="form-label"
                                                            >Qty</label
                                                        >
                                                        <input
                                                            type="number"
                                                            id="quantity"
                                                            class="form-control border-secondary"
                                                            min="1"
                                                        />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label
                                                            class="form-label"
                                                            >Unit Price</label
                                                        >
                                                        <input
                                                            type="text"
                                                            id="unit_price"
                                                            class="form-control border-secondary"
                                                            readonly
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-md-2 d-grid"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="btn btn-dark"
                                                            id="addRepairParts"
                                                        >
                                                            <i
                                                                class="fa-solid fa-plus me-1"
                                                            ></i>
                                                            Add Part
                                                        </button>
                                                    </div>
                                                </div>
                                                <table
                                                    class="table table-bordered table-hover"
                                                >
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th
                                                                class="text-center"
                                                            >
                                                                Product
                                                            </th>
                                                            <th
                                                                class="text-center"
                                                            >
                                                                Qty
                                                            </th>
                                                            <th
                                                                class="text-center"
                                                            >
                                                                Unit Price
                                                            </th>
                                                            <th
                                                                class="text-center"
                                                            >
                                                                Subtotal
                                                            </th>
                                                            <th
                                                                class="text-center"
                                                            >
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>1</th>
                                                            <td
                                                                class="text-center align-middle"
                                                            >
                                                                Mark
                                                            </td>
                                                            <td
                                                                class="text-center align-middle"
                                                            >
                                                                Otto
                                                            </td>
                                                            <td
                                                                class="text-center align-middle"
                                                            >
                                                                @mdo
                                                            </td>
                                                            <td
                                                                class="text-center align-middle"
                                                            >
                                                                asd
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr />
                                            <div class="row mt-4 g-4">
                                                <div class="col-md-6">
                                                    <div
                                                        class="card border-0 shadow-sm bg-light h-100"
                                                    >
                                                        <div class="card-body">
                                                            <h6
                                                                class="fw-bold text-muted mb-3"
                                                            >
                                                                <i
                                                                    class="fa-solid fa-receipt me-2 text-secondary"
                                                                ></i>
                                                                Current Saved
                                                                Amount
                                                            </h6>
                                                            <div
                                                                class="d-flex justify-content-between mb-2"
                                                            >
                                                                <span
                                                                    >Labor
                                                                    Fee:</span
                                                                >
                                                                <span
                                                                    class="fw-semibold text-dark"
                                                                    >{{
                                                                        currencyFormat(
                                                                            getLaborFee,
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="d-flex justify-content-between mb-2"
                                                            >
                                                                <span
                                                                    >Parts
                                                                    Total:</span
                                                                >
                                                                <span
                                                                    class="fw-semibold text-dark parts_amount"
                                                                >
                                                                </span>
                                                            </div>
                                                            <hr />
                                                            <div
                                                                class="d-flex justify-content-between fs-5"
                                                            >
                                                                <strong
                                                                    >Total
                                                                    Amount:</strong
                                                                >
                                                                <strong
                                                                    class="text-secondary"
                                                                >
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="card border-0 shadow-sm border-start border-4 border-success h-100"
                                                    >
                                                        <div class="card-body">
                                                            <h6
                                                                class="fw-bold text-success mb-3"
                                                            >
                                                                <i
                                                                    class="fa-solid fa-pen-to-square me-2"
                                                                ></i>
                                                                New Estimated
                                                                Amount
                                                            </h6>
                                                            <div
                                                                class="d-flex justify-content-between mb-2"
                                                            >
                                                                <span
                                                                    >Labor
                                                                    Fee:</span
                                                                >
                                                                <span
                                                                    class="fw-semibold"
                                                                    id="laborFeePreview"
                                                                >
                                                                </span>
                                                            </div>

                                                            <div
                                                                class="d-flex justify-content-between mb-2"
                                                            >
                                                                <span
                                                                    >Parts
                                                                    Total:</span
                                                                >
                                                                <span
                                                                    class="fw-semibold"
                                                                    id="partsTotalPreview"
                                                                >
                                                                </span>
                                                            </div>
                                                            <hr />
                                                            <div
                                                                class="d-flex justify-content-between fs-5"
                                                            >
                                                                <strong
                                                                    >Total
                                                                    Amount:</strong
                                                                >
                                                                <strong
                                                                    class="text-success"
                                                                    id="overallTotalPreview"
                                                                >
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button
                                                class="btn btn-danger mr-4"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                id="saveRepair"
                                                class="btn btn-success"
                                            >
                                                Save Diagnosis
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
