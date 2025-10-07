<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
<div class="app-brand text-center py-3">
    <a href="{{ url('/') }}" class="app-brand-link d-inline-flex align-items-center justify-content-center">
        <!-- Logo -->
        <img src="https://siakkab.go.id/images/link/diskominfo.png"
             alt="Logo" width="30" height="30" class="me-2">

        <!-- Tulisan -->
        <span class="app-brand-text demo menu-text fw-bolder text-uppercase">E-SPT</span>
    </a>
</div>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('data-pegawai') ? 'active' : '' }}">
      <a href="{{ route('data-pegawai') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Analytics">Data Pegawai</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('perjalanan-dinas.create') ? 'active' : '' }}">
      <a href="{{ route('perjalanan-dinas.create') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-plus"></i>
        <div data-i18n="Analytics">Buat Pengajuan</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('perjalanan-dinas.index') ? 'active' : '' }}">
      <a href="{{ route('perjalanan-dinas.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-list-ul"></i>
        <div data-i18n="Analytics">Pengajuan Perjalanan Dinas</div>
      </a>
    </li>

        <li class="menu-item {{ request()->routeIs('verifikasi-pengajuan.index') ? 'active' : '' }}">
      <a href="{{ route('verifikasi-pengajuan.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        <div data-i18n="Analytics">Verifikasi Pengajuan</div>
      </a>
    </li>

     <li class="menu-item {{ request()->routeIs('persetujuan-atasan.index') ? 'active' : '' }}">
      <a href="{{ route('persetujuan-atasan.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-badge-check"></i>
        <div data-i18n="Analytics">Persetujuan Atasan</div>
      </a>
    </li>

         <li class="menu-item {{ request()->routeIs('dokumen-perjalanan-dinas.index') ? 'active' : '' }}">
      <a href="{{ route('dokumen-perjalanan-dinas.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-folder"></i>
        <div data-i18n="Analytics">Dokumen SPT/SPPD</div>
      </a>
    </li>

        <li class="menu-item {{ request()->routeIs('sbu-item.index') ? 'active' : '' }}">
      <a href="{{ route('sbu-item.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-grid"></i>
        <div data-i18n="Analytics">Manajemen SBU</div>
      </a>
    </li>


</aside>
