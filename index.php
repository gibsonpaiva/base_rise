<?php
    /// Inicia uma Sessão se ainda não tiver iniciado
    if(session_id() == '') { session_start(); }

	// Verifica se o usuário está autenticado
	if(isset($_SESSION['user']['id'])) {
		// Caso esteja conectado, redireciona para a página inicial
		header("Location: home.php");
	} else if(isset($_SESSION['message'])) {
		// Caso não esteja autenticado e tenha tido o acesso negado
		echo $_SESSION['message'];
        // Redefine a mensagem da Sessão
		unset($_SESSION['message']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
        <!-- Titulo -->
        <title>RISE</title>
		<?php include_once "frames/head.php" ?>
        <!-- Others CSS -->
        <link rel="stylesheet" href="/stylesheet/others/login.css">
	</head>

    <body class="hold-transition login-page">
    <div class="full-screen-video-container">
        <video autoplay loop muted>
          <source src="img/Rise2.mp4" type="video/mp4" style="width: 100%;"/>
        </video>

		<div class="container conf-container-login text-center">
         <section class="">
                <form class="form-signin" action="config/login.php" method="POST" >

                    <img class="img-logo-login" src="img/rise-logo-01.png" alt="RISE">

                    <h2 class="text-welcome font-weight-light"><b>Bem-vindo ao RISE On-Premises!</h2>
                    <h6>Utilize sua conta de domínio para efetuar o logon.</b></h6>
                    <br>

                    <h6>
                    <div class="input-group-append">
                        <span class="input-group-text glyphicon glyphicon-user"></span>
                        <input  type="text" id="inputUsername" name="username" class="form-control input-lg conf-input transparent" placeholder="Username" required>
                    </div>
                    <br>

                    <div class="input-group-append">
                        <span class="input-group-text glyphicon glyphicon-lock"></span>
                        <input type="password" id="inputPassword" name="password" class="form-control input-lg conf-input transparent" placeholder="Password" required>
                    </div>
                    </h6>

                    <button class="btn btn-danger conf-btn-login" type="submit">ENTRAR</button>
                    <br>

                    <h6><b>| &copy; 2018-2021 | RISE-UP |</h6>

                </form>
            </section>
        </div>
    </div>
	</body>
</html>
