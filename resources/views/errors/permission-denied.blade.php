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

    <title>Permission Denied | Koperasi Merah Putih</title>

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
    </style>
  </head>

  <body>
    <!-- Error -->
    <div class="container-xxl container-p-y">
      <div class="misc-wrapper">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body text-center py-5">
                <div class="mb-4">
                  <div class="avatar avatar-xl bg-label-danger">
                    <i class="ti ti-lock fs-1"></i>
                  </div>
                </div>
                
                <h3 class="card-title mb-3">Permission Denied</h3>
                <p class="text-muted mb-4">
                  You don't have sufficient permissions to perform this action.
                </p>
                
                <div class="alert alert-warning text-start mb-4">
                  <h6 class="alert-heading">
                    <i class="ti ti-info-circle me-1"></i>
                    Access Information
                  </h6>
                  <div class="row mb-2">
                    <div class="col-sm-4"><strong>Your Role:</strong></div>
                    <div class="col-sm-8">
                      {{ auth()->user()->roles->pluck('name')->implode(', ') ?? 'No role assigned' }}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4"><strong>Required:</strong></div>
                    <div class="col-sm-8">
                      @if(request()->route())
                        {{ ucfirst(str_replace('.', ' ', request()->route()->getAction('permission') ?? 'Unknown permission')) }}
                      @else
                        Special permission
                      @endif
                    </div>
                  </div>
                </div>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                  <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Go Back
                  </a>
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="ti ti-home me-1"></i>
                    Dashboard
                  </a>
                </div>
                
                <hr class="my-4">
                
                <div class="text-start">
                  <h6 class="mb-3">What can you do?</h6>
                  <ul class="list-unstyled">
                    <li class="mb-2">
                      <i class="ti ti-check text-success me-2"></i>
                      Contact your administrator for permission
                    </li>
                    <li class="mb-2">
                      <i class="ti ti-check text-success me-2"></i>
                      Request role upgrade if needed
                    </li>
                    <li class="mb-0">
                      <i class="ti ti-check text-success me-2"></i>
                      Return to your dashboard
                    </li>
                  </ul>
                </div>
              </div>
            </div>
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
