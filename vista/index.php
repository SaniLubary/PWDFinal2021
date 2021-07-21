<?php

include "../configuration.php";
session_start();
$_GET['APP'] = isset($_GET['APP']) ? $_GET['APP'] : 39;
// TODO guardar el id de app cuando sea dada de alta
if (isset($_GET['SESSIONKEY'])) {
    $_SESSION['app'] = $_GET['APP'];
    $_SESSION['token'] = $_GET['SESSIONKEY'];
    include "../utils/WSWebLogin.php";
    if (!isset($_SESSION['usuario'])) {
        // No es una sesion valida
        header('location:https://weblogin.muninqn.gov.ar');
        die();
    }
    // Verificar si el usuario existe, sino darlo de alta
    $usuario = $_SESSION['usuario'];
    $params = array(
        "idusuario_weblogin" => $usuario['idusuario_weblogin']
    );
    $UController = new UsuarioController();
    $usuarioExistente = $UController->buscar($params);
    if ($usuarioExistente) {
        // El usuario existe
        $_SESSION['usuario'] = $usuarioExistente[0];
    } else {
        // Alta usuario
        $nombreapellido = explode(",", $usuario["razonSocial"]);
        $params = array(
            "nombre" => $nombreapellido[1],
            "apellido" => $nombreapellido[0],
            "nrodocumento" => $usuario["documento"],
            "email" => $usuario["correoElectronico"],
            "idusuario_weblogin" => $usuario['idusuario_weblogin'],
            "perfil" => $_SESSION['perfilUsuario']
        );
        if (!$UController->alta($params)) {
            // Falla al cargar el usuario
        }
        $_SESSION['usuario'] = $UController->buscar($params)[0];
    }
    $_SESSION['usuario_weblogin'] = $usuario;
    $_SESSION['usuario'] = $usuario;
    header('location:views/menu/index.php');
    die();
}
// No es una sesion valida
header('location:https://weblogin.muninqn.gov.ar');
die();
