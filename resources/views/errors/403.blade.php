<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
-->
<!-- beautify ignore:start -->
<html
  lang="id"
  class="light-style"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('sneat-1.0.0/assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Access Denied - 403 | Koperasi Merah Putih</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat-1.0.0/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])

    <!-- Custom CSS -->
    <style>
      .misc-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 8rem);
      }
      .misc-error h2 {
        font-size: 8rem;
        color: #b91c1c;
        line-height: 1;
      }
      .misc-error h4 {
        font-size: 1.5rem;
        color: #5e6670;
      }
      .misc-footer {
        margin-top: 2rem;
      }
    </style>
  </head>

  <body>
    <!-- Error -->
    <div class="container-xxl container-p-y">
      <div class="misc-wrapper">
        <div class="text-center">
          <h2 class="mb-2">403</h2>
          <h4 class="mb-3">Access Denied</h4>
          <p class="mb-4">You don't have permission to access this page.</p>
          
          <div class="alert alert-warning mb-4 text-start">
            <h6 class="alert-heading">
              <i class="ti ti-lock me-1"></i>
              Permission Required
            </h6>
            <p class="mb-2">This action requires special permissions. Please contact your administrator if you believe this is an error.</p>
            <hr>
            <p class="mb-0">
              <small class="text-muted">
                Your current role: <strong>{{ auth()->user()->roles->pluck('name')->implode(', ') ?? 'No role' }}</strong>
              </small>
            </p>
          </div>
          
          <div class="d-flex justify-content-center gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
              <i class="ti ti-arrow-left me-1"></i>
              Go Back
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
              <i class="ti ti-home me-1"></i>
              Dashboard
            </a>
          </div>
          
          <div class="mt-3">
            <img
              src="{{ asset('sneat-1.0.0/assets/img/illustrations/page-misc-error-light.png') }}"
              alt="access-denied"
              width="500"
              class="img-fluid"
              data-app-dark-img="illustrations/page-misc-error-dark.png"
              data-app-light-img="illustrations/page-misc-error-light.png"
            />
          </div>
        </div>
      </div>
    </div>
    <!-- /Error -->

    <!-- Core JS -->
    <script src="{{ asset('sneat-1.0.0/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/bootstrap.js') }}"></script>

    <!-- Template JS -->
    <script src="{{ asset('sneat-1.0.0/assets/js/main.js') }}"></script>
  </body>
</html>
