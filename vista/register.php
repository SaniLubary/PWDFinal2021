<?php
include "../configuration.php";

// Si el usuario ya tiene sesion activa, se redirecciona
if ($sessionController->validar())
    redireccionarUltimaPagina();

// Si se hizo submit con los datos requeridos, se intenta . . .
if (isset($_POST['usnombre']) && isset($_POST['usmail']) && isset($_POST['uspass'])) {
    $usuarioController = new UsuarioController();
    // Dar de alta usuario
    if ($usuarioController->alta($_POST)) {
        $sessionController = new SessionController();
        // Iniciar su sesion
        if ($sessionController->iniciar($_POST['usnombre'], $_POST['uspass']))
            // Se setea variable 'error' a vacio por si algun error fue seteado antes
            $_SESSION['error'] = '';
            redireccionarUltimaPagina();
    }
    else echo 'Hubo un error en su registro, intente de nuevo mas tarde.';
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
        <form id="form_register" action="#" method="POST">
            Usuario: <input type="text" name="usnombre" required><br>
            Mail: <input type="email" name="usmail" required><br>
            Contrase&ntilde;a: <input type="password" id="uspass" name="uspass" required><br>
            <input type="submit" onclick="(e) => submitLoginRegister(e)" value="Enviar" >
        </form>

        <div id="cargando" style="display: none;">Cargando...</div>
        <button onclick="window.location.replace('./login.php');">Iniciar Sesion</button>
        <button onclick="window.location.replace('./index.php');">Volver al Inicio</button>

        <script src="./js/md5.js" async defer></script>
        <script src="./js/index.js" async defer></script>
    </body>
</html>