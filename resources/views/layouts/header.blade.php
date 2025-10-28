<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- User Dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bxs-user"></i>
              Halo, {{ auth()->user()->name }}
          <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item d-flex align-items-center" href="#">
              <i class="bx bx-user me-2"></i>
              <div class="d-flex flex-column">
                <span class="fw-semibold">{{ auth()->user()->name }}</span>
                <small class="text-muted">{{ auth()->user()->email }}</small>
              </div>
            </a>
          </li>
          <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bx bx-power-off me-2"></i> Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
