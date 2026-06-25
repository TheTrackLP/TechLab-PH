<script setup>
import { useForm } from "@inertiajs/vue3";

const supplierForm = useForm({
    id: "",
    name: "",
    supplier_type: "",
    contact_person: "",
    phone: "",
    address: "",
    notes: "",
});

const submit = () => supplierForm.post(route("supplier.store"));
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
                            <h4>Category Form</h4>
                        </div>

                        <div class="card-footer">
                            <button
                                type="submit"
                                class="btn btn-success px-5 float-end"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Category Lists</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Category Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">No. Products</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(cate, index) in categories"
                                    :key="cate.id"
                                >
                                    <td class="align-middle text-center">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="align-middle">
                                        <p>{{ cate.category_name }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <p>
                                            <small>{{
                                                cate.category_desc
                                            }}</small>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge text-bg-primary"
                                            >123</span
                                        >
                                    </td>
                                    <td class="align-middle text-center">
                                        <button
                                            type="button"
                                            class="btn btn-warning"
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
                </div>
            </div>
        </div>
    </div>
</template>
