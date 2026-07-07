<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { inject } from "vue";
import Pagination from "@/Components/Pagination.vue";

const deleteRecord = inject("deleteRecord");

const categoryFormMode = ref("create");

const categoryForm = useForm({
    id: "",
    category_name: "",
    category_desc: "",
});

const categoryData = (cate) => {
    categoryFormMode.value = "edit";
    categoryForm.id = cate.id;
    categoryForm.category_name = cate.category_name;
    categoryForm.category_desc = cate.category_desc;
};

const submit = () => {
    if (categoryFormMode.value === "create") {
        categoryForm.post(route("category.store"), {
            preserveScroll: true,
            onSuccess: () => {
                categoryForm.reset();
            },
        });
    } else {
        categoryForm.post(route("category.update", categoryForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                categoryForm.reset();
                categoryFormMode.value = "create";
            },
        });
    }
};

const props = defineProps({
    categories: Object,
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
                            <h4>Category Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="">Category Name:</label>
                                <input
                                    type="text"
                                    v-model="categoryForm.category_name"
                                    class="form-control"
                                />
                                <input
                                    type="hidden"
                                    v-model="categoryForm.id"
                                />
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Category Description:</label>
                                <textarea
                                    class="form-control"
                                    rows="7"
                                    v-model="categoryForm.category_desc"
                                ></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button
                                type="submit"
                                class="btn btn-success px-5 float-end"
                            >
                                {{
                                    categoryForm.processing
                                        ? "Saving.."
                                        : categoryFormMode === "create"
                                          ? "Add"
                                          : "Save Changes"
                                }}
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
                                    v-for="(cate, index) in categories.data"
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
                                            @click="categoryData(cate)"
                                        >
                                            <i
                                                class="fa-solid fa-pen-to-square"
                                            ></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            @click="
                                                deleteRecord(
                                                    route(
                                                        'category.delete',
                                                        cate.id,
                                                    ),
                                                )
                                            "
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3 mb-3">
                        <Pagination :links="categories.links" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
