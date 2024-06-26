<?php 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="icon" type="image/x-icon" href="img/praise_icon.ico" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Login</title>
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="libs/fontawesome/css/all.css">
    <style rel="stylesheet" type="text/css">
        body, html{
            color: #fff!important;
            height: 100%!important;
        }

    </style>
</head>
<body class="bg-gradient-mid-dark">

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12 mt-5 pt-5">
                <input type="hidden">
            </div>

            <div class="col-xl-10 col-lg-12 col-md-9 mt-5 pt-5">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6 bg-dark">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 mb-4" style="color:#fff;">Bem Vindo</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="user" placeholder="Informe seu Login" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" placeholder="Informe sua Senha">
                                        </div>
                                        <a class="btn btn-secondary btn-user btn-block" id="login">
                                            Acessar
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" data-bs-toggle="modal" href="../forgot-password.php" style="cursor:pointer; color:#858796;">Esqueceu a Senha?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="libs/jquery/jquery.min.js"></script>
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="libs/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Ajax CDN -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script> -->
    <script src="js/functions.js"></script>
    <!-- lib de notificação do bootsrap -->
    <script src="libs/BootstrapNotify/bootstrap-notify.js"></script>
</body>

</html>

<script>
$(document).ready(function() {
    $('.blocked-btn').hide();
});

$("#user").keyup(function(data) {
  if (data.keyCode === 13) {
    $("#login").click();
  }
});

$("#password").keyup(function(data) {
  if (data.keyCode === 13) {
    $("#login").click();
  }
});

$("#login").click(function() {
    login();
});

</script>



