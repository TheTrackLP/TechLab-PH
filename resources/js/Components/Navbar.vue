<script setup>
const emit = defineEmits(["toggleSidebar"]);
</script>

<template>
    <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        data-lte-toggle="sidebar"
                        href="#"
                        role="button"
                        @click.prevent="emit('toggleSidebar')"
                    >
                        <i class="bi bi-list"></i>
                    </a>
                </li>
            </ul>
            <!--end::Start Navbar Links-->
        </div>
        <!--end::Container-->
    </nav>
</template>
