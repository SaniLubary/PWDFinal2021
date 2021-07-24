<?php
include "../configuration.php";

$sessionController = new SessionController();
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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/index.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
            }
        ?>
        
        <form id="form_login" action="./requests/Session.php" method="POST">
            Usuario: <input type="text" name="usnombre" required><br>
            Contrase&ntilde;a: <input type="password" id="uspass" name="uspass" required><br>
            <input type="submit" value="Enviar" onclick="(e) => submitLoginRegister(e)">
        </form>

        <div id="cargando" style="display: none;">Cargando...</div>
        <button onclick="window.location.replace('./register.php');">Crear Usuario</button>
        <button onclick="window.location.replace('./index.php');">Volver al Inicio</button>

        <script src="./js/md5.js" async defer></script>
        <script src="./js/index.js" async defer></script>
    </body>
</html>