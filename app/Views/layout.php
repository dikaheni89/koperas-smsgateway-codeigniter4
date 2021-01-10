<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $apps;?></title>
    <meta name="author" content="fan.fantasi@gmail.com">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/favicon.ico" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0-12/css/all.css">

    <link rel="stylesheet" href="<?= base_url('template/vendors/bootstrap/dist/css/bootstrap.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/themify-icons/css/themify-icons.css');?>">

    <link rel="stylesheet" href="<?= base_url('template/vendors/sweetalert2/sweetalert2.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/toastr/toastr.min.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('template/css/main.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/css/themes/blue.css');?>">
    <script type="text/javascript" src="<?= base_url('template/vendors/jquery/dist/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?= base_url('template/vendors/popper.js/dist/umd/popper.min.js');?>"></script>
    <script type="text/javascript" src="<?= base_url('template/vendors/bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <?php 
    foreach($css_files as $file) { ?>
    <link type="text/css" rel="stylesheet" href="<?= $file; ?>" />
    <?php } ?>
    
    <?php foreach($js_files as $file) { ?>
    <script type="text/javascript" src="<?= $file; ?>"></script>
    <?php } ?>
    </head>
	<body class="fixed-navbar fixed-layout">
		<div class="page-wrapper">
        <header class="header">
		    <div class="page-brand">
		        <a class="link" href="#">
                    <img src="<?= base_url('koperasi.png'); ?>" style="vertical-align: sub; width: 30px; margin-top: 15px;" title="">
		            <span class="brand">
                        <p>KOPSIPJAM</p>
		            </span>
		            <span class="brand-mini">Administrator</span>
		        </a>
		    </div>
		    <div class="flexbox flex-1">
		      <!-- START TOP-LEFT TOOLBAR-->
		      <ul class="nav navbar-toolbar">
		          <li>
		              <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
		          </li>
		      </ul>
		      <ul class="nav navbar-toolbar">
		        <li class="dropdown dropdown-user">
		            <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                        <?php if ($profil['photo'] == ''){ $foto = 'profil.png'; }else{ $foto = $profil['photo']; } ?>
		            	<img src="<?= base_url('uploads/profil/'.$foto);?>">
		                <span></span><?= $profil['full_name'];?><i class="fa fa-angle-down m-l-5"></i></a>
		            <ul class="dropdown-menu dropdown-menu-right">
		                <a class="dropdown-item" href="<?= base_url('admin/profil');?>"><i class="fa fa-user"></i>Profile</a>
		                <li class="dropdown-divider"></li>
		                <a class="dropdown-item" href="<?= base_url('admin/logout');?>"><i class="fa fa-power-off"></i>Logout</a>
		            </ul>
		        </li> 
		      </ul>
		    </div>
        </header>
        <nav class="page-sidebar" id="sidebar">
		  <div id="sidebar-collapse">
		    <div class="admin-block d-flex">
		        <div>
		        	<img src="<?= base_url('uploads/profil/'.$foto);?>" width="45px">
		        </div>
		        <div class="admin-info">
		            <div class="font-strong"><?= $profil['full_name'];?></div><small>
		              Administrator
		            </small>
		        </div>
		    </div>
		    <ul class="side-menu metismenu">
		        <li>
		            <a class="active" href="<?= base_url('admin');?>"><i class="sidebar-item-icon ti-desktop"></i>
		                <span class="nav-label">Dashboard</span>
		            </a>
		        </li>
		        <li class="heading">AKSES MENU</li>
		        <?php
                    $menus = json_decode($menus);
                    foreach ($menus as $row) :
                      if ($row->numsub > 0){?>
                        <li>
                          <a href="javascript:;"><i class="sidebar-item-icon <?= $row->iconmain;?>"></i>
                            <span class="nav-label"><?= $row->titlemain;?></span><i class="fa fa-angle-left arrow"></i></a>
                            <ul class="nav-2-level collapse">
                              <?php foreach ($row->submenu as $sub) :?>
                              <li>
                                  <a href="<?= base_url($sub->uri);?>"><?= $sub->title;?></a>
                              </li>
                              <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php } endforeach;?>
                ?>
		    </ul>
		  </div>

        </nav>
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                 <div class="row">
                    <?php echo $content; ?>
                </div>
            </div>
            <!-- END PAGE CONTENT-->
            <footer class="page-footer">
                <div class="font-13"><?= date('Y');?> © <b>KOPERASI SIMPAN PINJAM SMA MATHLAUL ANWAR </b>.</div>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer>
        </div>
    </div>
   <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop" style="margin-left:220px; margin-top: 56px;">
        <div class="page-preloader" style="left: 57%">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
	

	
	<script type="text/javascript" src="<?= base_url('template/vendors/metisMenu/dist/metisMenu.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
	<!-- SweetAlert2 -->
	<script type="text/javascript" src="<?= base_url('template/vendors/toastr/toastr.min.js');?>"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<!-- AdminLTE App -->
	<!-- Toastr -->
	<script type="text/javascript" src="<?= base_url('template/js/app.min.js');?>"></script>
	<script type="text/javascript">
		const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
        ($("body").addClass("fixed-layout"),$("#sidebar-collapse").slimScroll({height:"100%",railOpacity:"0.9"}))
	</script>
  </body>
</html>

		    
