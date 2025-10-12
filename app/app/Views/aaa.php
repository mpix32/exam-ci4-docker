<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Dashboard - <?= NAMA_APLIKASI . " " . VERSI ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <link href="<?php echo base_url(); ?>/___/css/bootstrap.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>/___/css/style.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>/___/plugin/fa/css/font-awesome.min.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>/___/plugin/datatables/dataTables.bootstrap.css" rel="stylesheet">
</head>

<body>

   <div class="" style="min-height: 450px">
      <nav class="navbar navbar-findcond navbar-fixed-top">
         <div class="container">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand"><?php echo NAMA_APLIKASI . " " . VERSI ?></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
               <ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo session('userData.admin_nama') . " (" . session('userData.admin_user') . ")"; ?> <span class="caret"></span></a>
                     <ul class="dropdown-menu" role="menu">
                        <li><a href="#" onclick="return rubah_password();">Ubah Password</a></li>
                        <li><a href="<?php echo base_url(); ?>adm/logout" onclick="return confirm('keluar..?');">Logout</a></li>
                     </ul>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <?= gen_menu(); ?>
  	   <?php echo view($p); ?>
    
   </div>

   <div class="col-md-12 footer">
      <a href="<?php echo base_url(); ?>/adm"><?php echo NAMA_APLIKASI . " " . VERSI . "</a><br> Waktu Server: " . tjs(date('Y-m-d H:i:s'), "s") . " - Waktu Database: " . tjs(waktu_sql(), "s"); ?>.
   </div>

   <!-- insert modal -->
   <div id="tampilkan_modal"></div>


   <script src="<?php echo base_url(); ?>/___/js/jquery-1.11.3.min.js"></script>
   <script src="<?php echo base_url(); ?>/___/js/bootstrap.js"></script>
   <script src="<?php echo base_url(); ?>/___/plugin/ckeditor/ckeditor.js"></script>
   <script src="<?php echo site_url(); ?>inputmask/dist/jquery.inputmask.js"></script>

   <?php
   if (handlingUri(2) == "m_soal" && handlingUri(3) == "edit") {
   ?>
      <script src="<?php echo base_url(); ?>/___/plugin/ckeditor/ckeditor.js"></script>
   <?php
   }
   ?>
   <!-- editor
<script src="<?php echo base_url(); ?>___/plugin/editor/nicEdit.js"></script>
 -->

   <script src="<?php echo base_url(); ?>/___/plugin/datatables/jquery.dataTables.min.js"></script>
   <script src="<?php echo base_url(); ?>/___/plugin/datatables/dataTables.bootstrap.min.js"></script>


   <script src="<?php echo base_url(); ?>/___/plugin/countdown/jquery.plugin.min.js"></script>
   <script src="<?php echo base_url(); ?>/___/plugin/countdown/jquery.countdown.min.js"></script>
   <script src="<?php echo base_url(); ?>/___/plugin/jquery_zoom/jquery.zoom.min.js"></script>

   <script type="text/javascript">
      var base_url = "<?php echo base_url(); ?>";
      var editor_style = "<?php echo EDITOR_STYLE ?>";
      var uri_js = "<?= URI_JS ?>";
   </script>
   <script src="<?php echo base_url(); ?>/___/js/aplikasi.js"></script>
   <?= $this->renderSection('js') ?>

</body>

</html>