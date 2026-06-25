<script setup>
import Footer from "@/Components/Footer.vue";
import Sidebar from "@/Components/Sidebar.vue";
import Navbar from "@/Components/Navbar.vue";
import { ref, watch, provide } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const sidebarOpen = ref(true);

const page = usePage();

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
}

watch(
    () => page.props.flash?.success,
    (value) => {
        if (value) {
            Swal.fire({
                title: "Success!",
                text: value,
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    },
    { deep: true },
);

watch(
    () => page.props.flash?.error,
    (value) => {
        if (value) {
            Swal.fire({
                title: "Error!",
                text: value,
                icon: "error",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    },
    { deep: true },
);

const deleteRecord = (deleteRoute, value) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            router.get(
                deleteRoute,
                {},
                {
                    preserveScroll: true,
                },
            );
            Swal.fire({
                title: "Deleted!",
                text: value,
                icon: "warning",
                timer: 2000,
                showConfirmButton: false,
            });
        }
    });
};
provide("deleteRecord", deleteRecord);
</script>
<template>
    <!--begin::Body-->
    <!--begin::App Wrapper-->
    <div class="app-wrapper" :class="{ 'sidebar-collapse': !sidebarOpen }">
        <!--begin::Header-->
        <Navbar @toggleSidebar="toggleSidebar" />
        <!--end::Header-->
        <!--begin::Sidebar-->
        <Sidebar />
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <slot />
        <!--end::App Main-->
        <!--begin::Footer-->
        <Footer />
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
</template>
