<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  {{-- Wrapper kiri-kanan --}}
  <div class="d-flex justify-content-between align-items-center flex-grow-1" id="navbar-collapse">

    {{-- Breadcrumb kiri --}}
    <div class="d-none d-md-block">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>

          @php
            $segments = request()->segments();
            $url = '';
          @endphp

          @foreach($segments as $key => $segment)
            @php $url .= '/' . $segment; @endphp
            @if($key + 1 < count($segments))
              <li class="breadcrumb-item">
                <a href="{{ url($url) }}">{{ ucwords(str_replace('-', ' ', $segment)) }}</a>
              </li>
            @else
              <li class="breadcrumb-item active fw-bold" aria-current="page">
                {{ ucwords(str_replace('-', ' ', $segment)) }}
              </li>
            @endif
          @endforeach
        </ol>
      </nav>
    </div>

    {{-- User info kanan --}}
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bxs-user me-1"></i>
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
          <li>
            <a class="dropdown-item" href="{{ route('login') }}">
              <i class="bx bx-power-off me-2"></i> Logout
            </a>
          </li>
        </ul>
      </li>
    </ul>

  </div>
</nav>

{{-- Sedikit styling tambahan biar lebih rapi --}}
<style>
  .breadcrumb {
    background: transparent;
    font-size: 0.9rem;
  }
  .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
  }
  .navbar .breadcrumb a {
    color: #6c757d;
    text-decoration: none;
  }
  .navbar .breadcrumb .active {
    color: #696cff;
    font-weight: 500;
  }
</style>
