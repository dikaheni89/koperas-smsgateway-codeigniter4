<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title;?></title>
    <meta name="author" content="dinaspariwisata@gmail.com">
    <link rel="shortcut icon" href="<?= base_url(); ?>/favicon.ico" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0-12/css/all.css">

    <link rel="stylesheet" href="<?= base_url('template/vendors/bootstrap/dist/css/bootstrap.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/themify-icons/css/themify-icons.css');?>">

    <link rel="stylesheet" href="<?= base_url('template/vendors/sweetalert2/sweetalert2.min.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('template/css/main.css');?>">
    <link rel="stylesheet" href="<?= base_url('template/css/pages/auth-light.css');?>">
    </head>
	<body class="bg-silver-300">
		<div class="content">
        <div class="brand">
        </div>
        <div>
            <div class="text-center m-b-20">
                <img class="img-circle" src="<?= base_url('koperasi.png');?>" width="110px" />
            </div>
            <form class="text-center" id="login-form" action="javascript:;" method="post">
                <h5 class="font-strong">KOPERASI SMA MATHLAUL ANWAR</h5>
                <p class="font-13">Your are in sign in. Enter your username or email to login system</p>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="form-group">
	                <div class="input-group-icon right">
	                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
	                    <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">
	                </div>
	            </div>
	            <div class="form-group">
	                <div class="input-group-icon right">
	                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
	                    <input class="form-control" type="password" name="password" placeholder="Password">
	                </div>
	            </div>
                <div class="form-group">
                <div class="box well"></div>
	                <button class="btn btn-info btn-block" type="submit" id="login">Login</button>
	            </div>
	            <!-- <div class="social-auth-hr">
	                <span>Or login with</span>
	            </div>
	            <div class="text-center">Belum Punya Akun?
	                <a class="color-blue" href="<?= base_url('registrasi');?>">Registrasi</a>
	            </div> -->
            </form>
        </div>
    </div>
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Mohon Tunggu...</div>
    </div>
    <style>
        .brand {
            font-size: 44px;
            text-align: center;
            margin: 40px 0;
        }

        .content {
            max-width: 300px;
            margin: 0 auto;
        }
    </style>
    <script type="text/javascript" src="<?= base_url('template/vendors/jquery/dist/jquery.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/popper.js/dist/umd/popper.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/metisMenu/dist/metisMenu.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/bootstrap/dist/js/bootstrap.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/jquery-validation/dist/jquery.validate.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/jquery.maskedinput/dist/jquery.maskedinput.min.js');?>"></script>
	<!-- SweetAlert2 -->
	<script type="text/javascript" src="<?= base_url('template/vendors/toastr/toastr.min.js');?>"></script>
	<script type="text/javascript" src="<?= base_url('template/vendors/sweetalert2/sweetalert2.min.js');?>"></script>

    <script src="<?= base_url('template/loaderajax/jm.spinner.js');?>" type="text/javascript"></script> 

    <!-- CORE SCRIPTS-->
    <script src="<?= base_url('template/js/app.min.js');?>" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
	<script type="text/javascript">
        $(document).ready(function (){
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
        
		$(function() {
            $('#login-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                messages:{
                	email:{
                        required : 'Email tidak boleh kosong',
                        email: 'Format Email salah, isi dengan format email@domain.com'
                    },
                	password:'Password tidak boleh kosong.'
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(form) {
                      var user = $('input[name=email]').val();
                      var pass = $('input[name=password]').val();
                      var csrfHash = $('input[name=csrf_pariwisata_name]').val();
                      $.ajax({
                          type: "POST",
                          url : "<?= base_url('auth/check')?>",
                          dataType:'json',
                          data: {csrf_pariwisata_name: csrfHash, username:user, password:pass},
                          beforeSend:function(){
                                $('.box').jmspinner('large');       
                            },
                          success: function(msg){
                            var jsondata= JSON.parse(JSON.stringify(msg));
                            $('.box').jmspinner(false);
                            
                            var val = jsondata.map(function(e) {
                                return e.value;
                            });
                            var message = jsondata.map(function(e) {
                                return e.message;
                            });
                            var token = jsondata.map(function(e) {
                                return e.token;
                            });
                            console.log(token);
                            if (val == 0){
                              Toast.fire({
                                type: 'error',
                                title: ''+message+''
                                })
                              window.setTimeout(function(){
                                window.location.href="<?= base_url('login')?>";
                              },1000);
                            }else{
                              Toast.fire({
                                type: 'success',
                                title: ''+message+''
                              });
                               window.setTimeout(function(){
                                window.location.href="<?= base_url('admin')?>";
                              },1000);
                            }
                        },
                        complete: function (XMLHttpRequest, textStatus) {
                            var headers = XMLHttpRequest;
                            if (headers.status != 200){
                                Toast.fire({
                                    type: 'error',
                                    title: 'Hayooo.. Mau ngapain yaa.....'
                                })   
                             }
                        }

                      }); 
                      return false;
                }
            });
        });
        
    });
	</script>
  </body>
</html>

		    
