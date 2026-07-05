<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
.nav-header,
.nav-item {
    color: white;
}
.button-hover:hover,
.button-hover.active {
    background-color: #0081a7 !important;
    color: white !important;
    font-weight: bold;
}
</style>
<template>
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
            <!--begin::Brand Link-->
            <Link :href="route('admin.dash')" class="brand-link">
                <!--begin::Brand Image-->
                <i class="fa-solid fa-laptop-medical"></i>
                <!--end::Brand Image-->
                <!--begin::Brand Text-->
                <span class="brand-text fw-light text-white"
                    >TechLab <span class="text-info">PH</span></span
                >
                <!--end::Brand Text-->
            </Link>
            <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <!--begin::Sidebar Menu-->
                <ul
                    class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="navigation"
                    aria-label="Main navigation"
                    data-accordion="false"
                    id="navigation"
                >
                    <li class="nav-header">MAIN</li>
                    <li class="nav-item">
                        <a
                            href="./generate/theme.html"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-gauge"></i>
                            </div>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-header">INVENTORY</li>
                    <li class="nav-item">
                        <Link
                            :href="route('products.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-boxes"></i>
                            </div>
                            <p>Products</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :href="route('category.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-tag"></i>
                            </div>
                            <p>Categories</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :href="route('supplier.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <p>Suppliers</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :href="route('stocks.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-truck-loading"></i>
                            </div>
                            <p>Restock</p>
                        </Link>
                    </li>
                    <li class="nav-header">SALES</li>
                    <li class="nav-item">
                        <Link
                            :href="route('sales.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <p>POS</p>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :href="route('returns.index')"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-arrow-left"></i>
                            </div>
                            <p>Return</p>
                        </Link>
                    </li>
                    <li class="nav-header">SERVICES</li>
                    <li class="nav-item">
                        <a
                            href="./generate/theme.html"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-tools"></i>
                            </div>
                            <p>Repairs</p>
                        </a>
                    </li>
                    <li class="nav-header">REPORTS</li>
                    <li class="nav-item">
                        <a
                            href="./generate/theme.html"
                            class="nav-link button-hover"
                        >
                            <div class="nav-icon">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <p>Reports</p>
                        </a>
                    </li>
                </ul>
                <!--end::Sidebar Menu-->
            </nav>
        </div>
        <!--end::Sidebar Wrapper-->
    </aside>
</template>
