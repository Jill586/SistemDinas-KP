<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr"
      data-theme="theme-default" data-assets-path="../assets3/"
      data-template="vertical-menu-template-free">
  <head>
    @include('layouts.css')
    <title>@yield('title', 'Aplikasi Pegawai')</title>

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Header / Navbar -->
          @include('layouts.header')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            @include('layouts.footer')

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- JS Utama -->
    @include('layouts.js')

    <!-- JS Select2 (harus setelah jQuery dimuat) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Tambahkan ini supaya script tambahan dari child (SweetAlert, Select2 init, dll) bisa jalan --}}
    @stack('scripts')
  </body>
</html>
