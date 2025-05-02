<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Monitoring Tugas Akhir</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
  <!-- FullCalendar -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fullcalendar/main.css') }}">
  <!-- AdminLTE -->
  <script src="{{ asset('assets/dist/css/adminlte.min.js') }}"></script>
  <!-- Manual CSS -->
  <link rel="stylesheet" href="/resources/css/app.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.css') }}">

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        // title: 'Success',
                        title: "{{ session('success') }}",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Error',
                        title: "{{ session('error') }}",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            </script>
        @endif
        @if ($errors->any())
            @php
                $errorList = '<ul>';
                foreach ($errors->all() as $error) {
                    $errorList .= '<li>' . $error . '</li>';
                }
                $errorList .= '</ul>';
            @endphp
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Kesalahan Validasi',
                        title: `{!! $errorList !!}`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 7000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            </script>
        @endif
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" data-slide="true" role="button" id="pushMenuIcon"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">
          {{ request()->route()->getName() }}
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown dropstart">
          <div class="media user-panel d-flex" data-toggle="dropdown">
            <div class="image">
              <img src="{{ asset('assets/dist/img/user.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
          </div>
          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <li class="dropdown-item">
              <div class="image text-center">
                <img src="{{ asset('assets/dist/img/user2.jpg') }}" class="img-circle elevation-2" alt="User Image">
                <a href="#" class="d-block">{{auth()->user()->nomor_induk }}</a>
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
              </div>
              <br>
              <br>
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger ml-auto" style="color: white;">Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          </ul>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" data-slide="true" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link text-center">
        <img id="polban-logo" src="{{ asset('assets/dist/img/polban.png') }}" id="polban-logo" alt="Polban Logo" style="width: 140px; height: auto;">
      </a>
      <!-- Sidebar -->
      <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (auth()->user()->role=="1" || auth()->user()->role == "2" || auth()->user()->role == "3" || auth()->user()->role == "4")
            <li class="nav-item">
              <a href="{{ route('home') }}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Beranda
                  <!-- <span class="right badge badge-danger">New</span> -->
                </p>
              </a>
            </li>
            @endif
            @if (auth()->user()->role== "1")
            <li class="nav-item">
              <a href="{{ route('kota') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  KoTA
                  <!-- <span class="right badge badge-info">5</span> -->
                </p>
              </a>
            </li>
            @endif
            @if (auth()->user()->role == "1" ||  auth()->user()->role == "2" || auth()->user()->role == "3" || auth()->user()->role == "4")
            <li class="nav-item">
              <a href="{{ route('timeline') }}" class="nav-link">
                <i class="nav-icon fas fa-calendar"></i>
                <p>
                  Timeline
                  <!-- <span class="right badge badge-success">Updated</span> -->
                </p>
              </a>
            </li>
            @endif
            @if (auth()->user()->role=="1")
            <li class="nav-item">
              <a href="{{ route('artefak') }}" class="nav-link">
                <i class="nav-icon fas fa-file"></i>
                <p>
                  Artefak
                  <!-- <span class="right badge badge-danger">New</span> -->
                </p>
              </a>
            </li>
            @endif
            @if (auth()->user()->role == "3")
            <li class="nav-item">
              <a href="#artefakCollapse" class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="artefakCollapse">
                <i class="nav-icon fas fa-file"></i>
                <p>
                  Artefak
                  <!-- <span class="right badge badge-danger">New</span> -->
                </p>
              </a>
              <div class="collapse" id="artefakCollapse">
                <ul class="list-unstyled">
                  <li>
                    <a class="nav-link" href="{{ route('artefak') }}">
                      <i class="nav-icon fas fa-arrow"></i>
                      <p>FTA dan Dokumen</p>
                    </a>
                  </li>
                  <li>
                    <a class="nav-link" href="{{ route('resume') }}">
                        <i class="nav-icon fas fa-arrow"></i>
                      <p>Resume Bimbingan</p>
                    </a>
                  </li>
                  <!-- <li>
                    <a class="nav-link" href="#">
                      <i class="nav-icon fas fa-arrow"></i>
                      <p>Lain-lain</p>
                    </a>
                  </li> -->
                </ul>
              </div>
            </li>
            @endif
            @if (auth()->user()->role=="1")
            <li class="nav-item">
                <a href="{{ route('jadwal') }}" class="nav-link">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>
                        Jadwal Kesediaan
                        <!-- <span class="right badge badge-success">Updated</span> -->
                    </p>
                </a>
            </li>
            @endif
            @if (auth()->user()->role == "3")
            <li class="nav-item">
                <a href="#jadwalCollapse" class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="jadwalCollapse">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>
                        Jadwal
                        <!-- <span class="right badge badge-danger">New</span> -->
                    </p>
                </a>
                <div class="collapse" id="jadwalCollapse">
                    <ul class="list-unstyled">
                        <li>
                            <a class="nav-link" href="{{ route('jadwal') }}">
                                <i class="nav-icon fas fa-arrow"></i>
                                <p>Jadwal Kesediaan</p>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('kegiatan.index') }}">
                                <i class="nav-icon fas fa-arrow"></i>
                                <p>Jadwal Kegiatan</p>
                            </a>
                        </li>
                        <!-- <li>
                            <a class="nav-link" href="#">
                                <i class="nav-icon fas fa-arrow"></i>
                                <p>Lain-lain</p>
                            </a>
                        </li> -->
                    </ul>
                </div>
            </li>
            @endif
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

  @yield('content')

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- JQuery ui -->
  <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- DataTables & Plugins -->
  <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
  <!-- ChartJs -->
  <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Toastr -->
  <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
  <!-- FullCalendar -->
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/main.js') }}"></script>
  <!-- AdminLTE -->
  <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('assets/AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('assets/dist/js/demo.js') }}"></script>



  <script>
      document.getElementById('pushMenuIcon').addEventListener('click', function() {
          var logoImg = document.getElementById('polban-logo');
          var currentSrc = logoImg.src;
          if (currentSrc.includes('polban.png')) {
              logoImg.src = "{{ asset('assets/dist/img/polban1.png') }}";
              logoImg.style.width = '20px';
              logoImg.style.height = 'auto';
          } else {
              logoImg.src = "{{ asset('assets/dist/img/polban.png') }}";
              logoImg.style.width = '140px';
              logoImg.style.height = 'auto';
          }
      });
  </script>

</body>
</html>
