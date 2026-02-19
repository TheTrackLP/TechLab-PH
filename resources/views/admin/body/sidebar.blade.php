<style>
.button-hover:hover,
.button-hover.active {
    background-color: #0081A7 !important;
    color: white !important;
    font-weight: bold;
}
</style>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark shadow-sm" id="sidenavAccordion">

        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- MAIN -->
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="nav-link button-hover {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                    href="{{ route('admin.dash') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Dashboard
                </a>
                <!-- INVENTORY -->
                <div class="sb-sidenav-menu-heading">Inventory</div>
                <a class="nav-link button-hover {{ request()->routeIs('products.*') ? 'active' : '' }}"
                    href="{{ route('products.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    Products
                </a>
                <a class="nav-link button-hover {{ request()->routeIs('category.*') ? 'active': '' }}"
                    href="{{ route('category.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    Categories
                </a>
                <a class="nav-link button-hover {{ request()->routeIs('supplier.*') ? 'active': '' }}"
                    href="{{ route('supplier.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    Suppliers
                </a>
                <!-- SALES -->
                <div class="sb-sidenav-menu-heading">Sales</div>
                <a class="nav-link button-hover {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                    href="{{ route('sales.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    POS
                </a>
                <a class="nav-link button-hover" href="#">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    Transactions
                </a>
                <!-- SERVICES -->
                <div class="sb-sidenav-menu-heading">Services</div>
                <a class="nav-link button-hover" href="#">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    Repairs
                </a>
                <!-- REPORTS -->
                <div class="sb-sidenav-menu-heading">Reports</div>
                <a class="nav-link button-hover {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                    href="{{ route('reports.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    Reports
                </a>
            </div>
        </div>
        <!-- Footer -->
        <div class="sb-sidenav-footer text-center small">
            <div class="row">
                <div class="col">
                    <div>TechLab PH</div>
                    <div class="text-muted">Inventory System v1.0</div>
                </div>
                <div class="col">
                    <button class="btn btn-danger btn-lg"><i class="fa-solid fa-power-off"></i></button>
                </div>
            </div>
        </div>
    </nav>
</div>