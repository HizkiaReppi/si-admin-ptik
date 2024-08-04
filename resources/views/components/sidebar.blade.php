@php
    $homeLink = null;

    if (auth()->user()->role == 'admin') {
        $homeLink = route('dashboard');
    } else if(auth()->user()->role == 'student') {
        $homeLink = route('dashboard.submission.student.index');
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
        @can('student')
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons fa-solid fa-envelopes-bulk"></i>
                    <div data-i18n="Bimbingan">Pengajuan Surat</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('dashboard.submission.student.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.submission.student.index') }}" class="menu-link">
                            <div data-i18n="Pengajuan Surat">Pengajuan Surat</div>
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="menu-item {{ request()->routeIs('dashboard.submission.student.show') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.submission.student.show', $category->slug) }}" class="menu-link">
                                <div data-i18n="{{ $category->name }}">{{ $category->name }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @elsecan('admin')
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
            <li class="menu-item {{ request()->routeIs('dashboard.submission.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.submission.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-envelopes-bulk"></i>
                    <div data-i18n="Pengajuan Surat">Pengajuan Surat</div>
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
