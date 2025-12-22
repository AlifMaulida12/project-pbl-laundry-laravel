<style>
    /* Ubah warna gradasi sesuai preferensi Anda */
    .navbar-nav.bg-gradient-primary.sidebar.sidebar-dark.accordion {
        background: rgb(25, 198, 207);
    }
</style>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Tyo Super Laundry</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('pelanggan.index') }}"><!-- ini terhubung dengan index di pelanggan-->
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Data Pelanggan</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('pesanan.index') }}"><!-- ini terhubung dengan index di pesanan-->
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Semua Transaksi</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('reviews.index') }}"><!-- ini terhubung dengan index di review-->
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Review Pelanggan</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('cuaca.index') }}"><!-- ini terhubung dengan index di cuaca-->
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Prediksi Cuaca</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
