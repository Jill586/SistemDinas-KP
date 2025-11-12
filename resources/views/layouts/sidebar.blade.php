<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme d-flex flex-column">
  <div class="app-brand demo">
    <div class="app-brand text-center py-3">
        <a href="{{ url('/') }}" class="app-brand-link d-inline-flex align-items-center justify-content-center">
            <img src="https://siakkab.go.id/images/link/diskominfo.png" alt="Logo" width="30" height="30" class="me-2">
            <span class="app-brand-text demo menu-text fw-bolder text-uppercase">E-SPT</span>
        </a>
    </div>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  @php
      $role = Auth::check() ? trim(Auth::user()->role) : null;
  @endphp

  <ul class="menu-inner py-1 flex-grow-1">

    @if ($role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-loader-circle"></i>
          <div>Dashboard</div>
        </a>
      </li>
    @endif

    @if ($role === 'admin_bidang' || $role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('perjalanan-dinas.create') ? 'active' : '' }}">
        <a href="{{ route('perjalanan-dinas.create') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-plus"></i>
          <div>Buat Pengajuan</div>
        </a>
      </li>
    @endif

    @if ($role === 'verifikator1' || $role === 'super_admin' || $role === 'admin_bidang')
      <li class="menu-item {{ request()->routeIs('perjalanan-dinas.index') ? 'active' : '' }}">
        <a href="{{ route('perjalanan-dinas.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-list-ul"></i>
          <div>Pengajuan Perjalanan Dinas</div>
        </a>
      </li>
    @endif

     @if ($role === 'verifikator1' || $role === 'super_admin' || $role === 'admin_bidang')
      <li class="menu-item {{ request()->routeIs('verifikasi-staff.index') ? 'active' : '' }}">
        <a href="{{ route('verifikasi-staff.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-check-circle"></i>
          <div>Verifikasi Staff</div>
        </a>
      </li>
    @endif


    @if ($role === 'verifikator2' || $role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('verifikasi-pengajuan.index') ? 'active' : '' }}">
        <a href="{{ route('verifikasi-pengajuan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user-check"></i>
          <div>Verifikasi Pengajuan</div>
        </a>
      </li>
    @endif

    @if ($role === 'verifikator3' || $role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('persetujuan-atasan.index') ? 'active' : '' }}">
        <a href="{{ route('persetujuan-atasan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-badge-check"></i>
          <div>Persetujuan Atasan</div>
        </a>
      </li>
    @endif

        @if ($role === 'admin_bidang' || $role === 'super_admin' || $role === 'verifikator2' || $role === 'verifikator3')
      <li class="menu-item {{ request()->routeIs('dokumen-perjalanan-dinas.index') ? 'active' : '' }}">
        <a href="{{ route('dokumen-perjalanan-dinas.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-folder"></i>
          <div>Dokumen SPT/SPPD</div>
        </a>
      </li>
    @endif

    @if ($role === 'admin_bidang' || $role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
        <a href="{{ route('laporan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-clipboard"></i>
          <div>Laporan Perjadin</div>
        </a>
      </li>
    @endif

    @if ($role === 'verifikator2' || $role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('verifikasi-laporan.index') ? 'active' : '' }}">
        <a href="{{ route('verifikasi-laporan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-clipboard"></i>
          <div>Verifikasi Laporan</div>
        </a>
      </li>
    @endif

    @if ($role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('data-pegawai') ? 'active' : '' }}">
        <a href="{{ route('data-pegawai') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div>Data Pegawai</div>
        </a>
      </li>
    @endif

    @if ($role === 'super_admin')
      <li class="menu-item {{ request()->routeIs('sbu-item.index') ? 'active' : '' }}">
        <a href="{{ route('sbu-item.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-grid"></i>
          <div>Manajemen SBU</div>
        </a>
      </li>
    @endif

    {{-- Spacer agar Logout turun ke bawah --}}
    <div style="flex-grow: 1;"></div>

    {{-- Tombol Logout --}}
    <li class="menu-item {{ request()->routeIs('login') ? 'active' : '' }}">
      <a href="{{ route('login') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-log-out"></i>
        <div>Logout</div>
      </a>
    </li>

  </ul>
</aside>
