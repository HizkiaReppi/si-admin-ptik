@php
    $homeLink = null;

    if(auth()->user()->role == 'admin') {
        $homeLink = route('dashboard');
    }
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ $homeLink }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2 text-uppercase">ADMIN PTIK</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @can('admin')
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-chart-line"></i>
                    <div data-i18n="Bimbingan">Dashboard</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Manajemen Surat</span>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.category.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.category.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-list"></i>
                    <div data-i18n="Kategori">Kategori</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Manajemen Pengguna</span>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.lecturer.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.lecturer.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-chalkboard-user"></i>
                    <div data-i18n="Dosen">Dosen</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.student.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.student.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user"></i>
                    <div data-i18n="Mahasiswa">Mahasiswa</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.kajur.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.kajur.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user-graduate"></i>
                    <div data-i18n="Kajur">Ketua Jurusan</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
