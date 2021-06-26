<!DOCTYPE html>
<html lang="pt-BR">

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

<body class="<?= ($add_body_class ?? "") ?>">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <?= $this->renderSection("content") ?>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->



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

  <!-- ########################################### -->

  <?= $this->getCSRFDefaultScript() ?>
  <?= $this->renderSection("custom_scripts") ?>
</body>

</html>