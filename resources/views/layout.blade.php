<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Medistru-ERP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Favicon -->
  <link rel="shortcut icon" href="" type="image/png">


  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Overlay Scrollbars css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Sweetalert2 css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Bootstrap Tagsinput css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/bootstrap-taginput/bootstrap-tagsinput.css') }}">
  <!-- Bootstrap Colorpicker css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Bootstrap-datepicker css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <!-- Icheck Bootstrap css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Bootstrap-Iconpicker css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/bootstrap-iconpicker/bootstrap-iconpicker.min.css') }}">
  <!-- Select2 css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2-bootstrap4.min.css') }}">
  <!-- summernote css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/summernote/summernote-bs4.css') }}">
  <!-- DataTable css -->
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/data-table/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/data-table/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard/plugins/data-table/buttons.bootstrap4.min.css') }}">


</head>

<body {{ Session::has('notification') ? 'data-notification' : '' }} @if(Session::has('notification')) data-notification-message='{{ json_encode(Session::get('notification')) }} @endif' class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

  <div class="wrapper">

    @include('partials.top-navbar')

    @include('partials.side-navbar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <!--------Footer------------------>
    <footer class="main-footer">
      <div class="d-inline">Copyright Reserved</div>
      <div class="float-right d-none d-sm-inline-block">
        {{ __('Version : 1.1') }}
      </div>
    </footer>
  </div>
  <!-- Theme Css -->
  <link rel="stylesheet" href="{{ asset('dashboard/css/adminlte.min.css') }}">
  <!-- Custon css -->
  <link rel="stylesheet" href="{{ asset('dashboard/css/custom.css') }}">
  <!-- ./wrapper -->
  <input type="hidden" id="main_url" value=" ">
  <!-- jQuery 3 -->
  <script src="{{ asset('dashboard/js/jquery.min.js') }}"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Overlay Scrollbars js -->
  <script src="{{ asset('dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- Sweetalert2 js -->
  <script src="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- Bootstrap Colorpicker js -->
  <script src="{{ asset('dashboard/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
  <!-- Moment js -->
  <script src="{{ asset('dashboard/plugins/moment/moment.min.js') }}"></script>
  <!-- Bootstrap Tagsinput js -->
  <script src="{{ asset('dashboard/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js') }}"></script>
  <!-- Bs-custom-file-input js -->
  <script src="{{ asset('dashboard/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <!-- Bootstrap-datepicker js -->
  <script src="{{ asset('dashboard/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- Bootstrap-Iconpicker js -->
  <script src="{{ asset('dashboard/plugins/bootstrap-iconpicker/bootstrap-iconpicker.bundle.min.js') }}"></script>
  <!-- Bootstrap-Switch js -->
  <script src="{{ asset('dashboard/plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
  <!-- Select2 js -->
  <script src="{{ asset('dashboard/plugins/select2/select2.full.min.js') }}"></script>
  <!-- Summernote js -->
  <script src="{{ asset('dashboard/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- DataTable js -->
  <script src="{{ asset('dashboard/plugins/data-table/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('dashboard/plugins/data-table/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('dashboard/plugins/data-table/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('dashboard/plugins/data-table/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('dashboard/plugins/data-table/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('dashboard/plugins/data-table/buttons.bootstrap4.min.js') }}"></script>

  @yield('script')

  <!-- dashboardLTE App -->
  <script src="{{ asset('dashboard/js/adminlte.min.js') }}"></script>
  <!-- Custom js -->
  <script src="{{ asset('dashboard/js/custom.js') }}"></script>







</body>

</html>