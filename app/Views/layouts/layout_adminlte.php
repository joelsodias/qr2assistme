<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $page_title ?? ""  ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css">
  <?php if ($enable_datatables ?? false) :  ?>
    <!-- DataTables  & Plugins -->
    <!-- DataTables -->
    <link rel="stylesheet" href="/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.css">
  <?php endif; ?>



  <style>
    .dark-mode a {
      color: #fff;
    }

    .control-sidebar .sidebar-mini.sidebar-collapse .brand-text,
    .control-sidebar .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link p,
    .control-sidebar .sidebar-mini.sidebar-collapse .sidebar .user-panel>.info,
    .control-sidebar .nav-sidebar .nav-link>p {
      visibility: visible;

    }
  </style>
  <?= $this->renderSection("custom_css") ?>
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="layout-fixed hold-transition text-sm   
<?= ($sidebar_start_minimized ?? false) ? "sidebar-mini" : "" ?>
<?= ($sidebar_start_darkmode ?? false) ? "dark-mode" : "" ?>
<?= ($sidebar_start_colapsed ?? false) ? "sidebar-collapse" : "" ?>
">
  <div class="wrapper">
    <?php if ($enable_top_navbar ?? true) :  ?>
      <!-- Navbar -->
      <nav class="main-header d-print-none navbar navbar-expand n-avbar-white navbar-light layout-fixed">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <?php if ($enable_navbar_pushmenu ?? true) :  ?>
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
          <?php endif; ?>
          <?php if ($enable_navbar_menu ?? false) :  ?>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="/dashboard" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="#" class="nav-link">Contact</a>
            </li>
          <?php endif; ?>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <?php if ($enable_navbar_search ?? false) :  ?>
            <!-- Navbar Search -->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
              </a>
              <div class="navbar-search-block">
                <form class="form-inline">
                  <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                      <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                      <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
          <?php endif; ?>
          <?php if ($enable_messages_dropdown ?? false) :  ?>
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                    <img src="/adminlte/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">Call me whenever you can...</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div>
                  <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                    <img src="/adminlte/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">I got your message bro</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div>
                  <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                    <img src="/adminlte/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                      </h3>
                      <p class="text-sm">The subject goes here</p>
                      <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div>
                  <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
          <?php endif; ?>
          <?php if ($enable_notifications_dropdown ?? false) :  ?>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-envelope mr-2"></i> 4 new messages
                  <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-users mr-2"></i> 8 friend requests
                  <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-file mr-2"></i> 3 new reports
                  <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
            </li>
          <?php endif; ?>
          <?php if ($enable_fullscreen_selector ?? true) :  ?>
            <li class="nav-item">
              <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($enable_dark_mode_selector ?? true) :  ?>
            <li class="nav-item">
              <a class="nav-link dark-mode-switcher" data-widget="dark-mode" data-slide="true" href="#" onclick="$('body').toggleClass('dark-mode');$('.dark-mode-switcher > i').toggleClass('fa-moon');$('.dark-mode-switcher > i').toggleClass('fa-sun')" role="button">
                <i class="fas  fa-sun"></i>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($enable_control_sidebar ?? false) :  ?>
            <li class="nav-item">
              <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
                <!--img width="40" src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"-->
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.navbar -->
    <?php endif; ?>

    <?php if ($enable_sidebar ?? true) :  ?>
      <!-- Main Sidebar Container -->
      <aside class="main-sidebar d-print-none  sidebar-dark-secondary elevation-4">
        <?= $before_sidebar ?? "" ?>
        <?= $this->renderSection("before-sidebar") ?>
        <!-- Sidebar -->
        <div class="sidebar">
          <?= $sidebar ?? "" ?>
          <?= $this->renderSection("sidebar") ?>
        </div>
        <!-- /.sidebar -->
        <?= $after_sidebar ?? "" ?>
        <?= $this->renderSection("after-sidebar") ?>
      </aside>
    <?php endif; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="observe-me <?= ($enable_sidebar ?? true) ? "content-wrapper" : "" ?>">

      <?php if ($enable_content_header ?? false) :  ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0"><?= $content_header_title ?? "\$title" ?></h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#"><?= $content_header_home_title ?? "Home" ?></a></li>
                  <li class="breadcrumb-item active"><?= $content_header_title ?? "\$title" ?></li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
      <?php endif; ?>

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <?= $this->renderSection("content") ?>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php if ($enable_control_sidebar ?? false) :  ?>
      <!-- Control Sidebar -->
      <aside class="control-sidebar d-print-none control-sidebar-dark sidebar-dark-secondary">
        <div class="sidebar">
          c
        </div>
      </aside>
      <!-- /.control-sidebar -->
    <?php endif; ?>

    <?php if ($enable_footer ?? true) :  ?>
      <!-- Main Footer -->
      <footer class="<?= ($enable_sidebar ?? true) ? "main-footer" : "" ?> d-print-none">
        <div class="float-left d-none d-sm-inline-block mr-3">
          <?= date("Y") ?>
        </div>
        <div class="d-none d-sm-inline-block">&nbsp;<?= $this->renderSection("footer") ?></div>
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 3.1.0
        </div>
      </footer>
      <!-- ./footer -->
    <?php endif; ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <!-- <script src="/adminlte/plugins/jquery/jquery.js"></script> -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="/adminlte/plugins/jquery-ui/jquery-ui.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>

  <!-- Bootstrap 4 -->
  <script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.js"></script>
  <!-- AdminLTE -->
  <script src="/adminlte/dist/js/adminlte.js"></script>

  <!-- OPTIONAL SCRIPTS -->

  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- daterangepicker -->
  <script src="/adminlte/plugins/moment/moment.js"></script>
  <script src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>

  <!-- Tempusdominus Bootstrap 4 -->
  <script src="/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="/adminlte/plugins/jquery-knob/jquery.knob.js"></script>

  <!-- Summernote -->
  <script src="/adminlte/plugins/summernote/summernote-bs4.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

  <?php
  // <!-- ChartJS -->
  // <!-- <script src="/adminlte/plugins/chart.js/Chart.js"></script> -->
  // <!-- Sparkline -->
  // <!-- <script src="/adminlte/plugins/sparklines/sparkline.js"></script> -->
  // <!-- JQVMap -->
  // <script src="/adminlte/plugins/jqvmap/jquery.vmap.js"></script>
  // <script src="/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>



  ?>
  <?php if ($enable_datatables ?? false) :  ?>
    <!-- DataTables  & Plugins -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="/adminlte/plugins/datatables/jquery.dataTables.js"></script>
    <script src="/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="/adminlte/plugins/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.js"></script>
    <script src="/adminlte/plugins/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.js"></script>
    <script src="/adminlte/plugins/jszip/jszip.js"></script>
    <script src="/adminlte/plugins/pdfmake/pdfmake.js"></script>
    <script src="/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/adminlte/plugins/datatables-buttons/js/buttons.html5.js"></script>
    <script src="/adminlte/plugins/datatables-buttons/js/buttons.print.js"></script>
    <script src="/adminlte/plugins/datatables-buttons/js/buttons.colVis.js"></script>
  <?php endif; ?>

  <!-- ########################################### -->

  <!-- commom app code -->
  <?= $this->getCSRFDefaultScript() ?>
  <script src="/js/common.js"></script>
  <?= $this->renderSection("custom_scripts") ?>
</body>

</html>