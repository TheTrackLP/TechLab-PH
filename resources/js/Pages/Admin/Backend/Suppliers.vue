<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Pagination from "@/Components/Pagination.vue";

const supplierFormMode = ref("create");

const supplierForm = useForm({
    id: "",
    name: "",
    supplier_type: "",
    contact_person: "",
    phone: "",
    address: "",
    notes: "",
});

const supplierTypeData = (supp) => {
    supplierFormMode.value = "edit";
    supplierForm.id = supp.id;
    supplierForm.name = supp.name;
    supplierForm.supplier_type = supp.supplier_type;
    supplierForm.contact_person = supp.contact_person;
    supplierForm.phone = supp.phone;
    supplierForm.address = supp.address;
    supplierForm.notes = supp.notes;
};

const submit = () => {
    if (supplierFormMode.value === "create") {
        supplierForm.post(route("supplier.store"), {
            preserveScroll: true,
            onSuccess: () => {
                supplierForm.reset();
            },
        });
    } else {
        supplierForm.post(route("supplier.update", supplierForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                supplierForm.reset();
            },
        });
    }
};

const props = defineProps({
    suppliers: Object,
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
        <div class="row mt-3">
            <div class="col-md-4">
                <form @submit.prevent="submit">
                    <div class="card">
                        <div class="card-header">
                            <h4>Supplier Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input
                                        type="hidden"
                                        v-model="supplierForm.id"
                                    />
                                    <label for="">Supplier Name:</label>
                                    <input
                                        type="text"
                                        v-model="supplierForm.name"
                                        class="form-control"
                                    />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Supplier Type:</label>
                                    <select
                                        v-model="supplierForm.supplier_type"
                                        class="form-control"
                                    >
                                        <option value="">Select Type</option>
                                        <option value="distributor">
                                            Distributor
                                        </option>
                                        <option value="online">Online</option>
                                        <option value="local">Local</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Contact Person</label>
                                    <input
                                        type="text"
                                        v-model="supplierForm.contact_person"
                                        class="form-control"
                                    />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Phone</label>
                                    <input
                                        type="text"
                                        v-model="supplierForm.phone"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="">Address</label>
                                    <textarea
                                        v-model="supplierForm.address"
                                        rows="2"
                                        class="form-control"
                                    ></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Notes</label>
                                    <textarea
                                        v-model="supplierForm.notes"
                                        rows="2"
                                        class="form-control"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button
                                type="submit"
                                class="btn btn-success px-5 float-end"
                            >
                                {{
                                    supplierForm.processing
                                        ? "Saving.."
                                        : supplierFormMode === "create"
                                          ? "Add"
                                          : "Update"
                                }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Supplier Lists</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Contact</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(supp, index) in suppliers.data"
                                    :key="supp.id"
                                >
                                    <td class="align-middle text-center">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="align-middle">
                                        <p>{{ supp.name }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <p>
                                            Name:
                                            <b>{{ supp.contact_person }}</b>
                                        </p>
                                        <p>
                                            Phone: <b>{{ supp.phone }}</b>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            v-if="supp.is_active === 1"
                                            class="badge rounded-pill text-bg-success"
                                            >Active</span
                                        >
                                        <span
                                            v-else-if="supp.is_active === 0"
                                            class="badge rounded-pill text-bg-danger"
                                            >Inactive</span
                                        >
                                    </td>
                                    <td class="align-middle text-center">
                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            @click="supplierTypeData(supp)"
                                        >
                                            <i
                                                class="fa-solid fa-pen-to-square"
                                            ></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center my-3">
                        <Pagination :links="suppliers.links" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
