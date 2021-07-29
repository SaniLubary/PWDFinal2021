<?php
include "../configuration.php";

$sessionController = new SessionController();

// Si el usuario ya tiene sesion activa, se redirecciona
if ($sessionController->validar())
    redireccionarUltimaPagina();

$user_validado = false;

if (isset($_POST['usnombre']) && isset($_POST['uspass'])) {
    $usuarioController = new UsuarioController();
    if ($usuarioController->alta($_POST)) {
        if ($sessionController->iniciar($_POST['usnombre'], $_POST['uspass']))
            $_SESSION['error'] = '';
            redireccionarUltimaPagina();
    }
    else echo 'Hubo un error en su registro.';
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>TP Final PWD</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./css/index.css" />
</head>
    <body class="d-flex flex-column min-vh-100">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Se incluye el header -->
        <?php include './header.php'; ?>

        <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                $_SESSION['error'] = '';
            }
        ?>
        
        <form id="form_login" action="./requests.php" method="POST">
            Usuario: <input class="form-control" type="text" name="usnombre" required><br>
            Contrase&ntilde;a: <input class="form-control" type="password" id="uspass" name="uspass" required><br>
            <input class="form-control" type="submit" value="Enviar" onclick="(e) => submitLoginRegister(e)">
            <div id="cargando" style="display: none;" class="spinner-border" role="status"></div>
        </form>

        <!-- Se incluye el footer -->
        <?php include './footer.php'; ?>
    
        <script src="./js/md5.js" async defer></script>
        <script src="./js/index.js" async defer></script>
    </body>
</html>