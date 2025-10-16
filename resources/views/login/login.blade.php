<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets3/') }}/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <title>Login - ESPT</title>

    <!-- login -->
    <link rel="stylesheet" href="{{ asset('assets3/css/login.css') }}"

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets3/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"

    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets3/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets3/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets3/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets3/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets3/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets3/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets3/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets3/js/config.js') }}"></script>
  </head>

  <body>


    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-3">
                <a href="#" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">E-SPT & SPPD</span>
                </a>
              </div>

              <h4 class="mb-2 text-center">Selamat Datang 👋</h4>
              <p class="mb-4 text-center">Silakan login untuk melanjutkan</p>

              {{-- ALERT ERROR --}}
              @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
              @endif

              {{-- FORM LOGIN --}}
              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    required
                    autofocus
                  />
                  @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control @error('password') is-invalid @enderror"
                      name="password"
                      placeholder="********"
                      required
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @error('password')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember-me" />
                    <label class="form-check-label" for="remember-me">Ingat Saya</label>
                  </div>

                </div>

                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
              </form>


            </div>
          </div>
          <!-- /Login Card -->
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets3/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets3/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets3/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets3/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets3/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets3/js/main.js') }}"></script>
  </body>
</html>
