<?php 
include '../../configuration.php';
$sessionController = new SessionController();

// Valida datos de inicio de sesion, se llama cuando se intenta interactuar con elementos de la pagina que requieran de una sesion iniciada
if ($_POST && isset($_POST['usnombre']) && isset($_POST['uspass']) && ($_POST['usnombre'] && $_POST['uspass']) != '') {
    if (!$sessionController->iniciar($_POST['usnombre'], $_POST['uspass'])) {
        $_SESSION['error'] = "Usuario o Contrase&ntilde;a Incorrectos!";
        header("Location: ../login.php");
        exit();
    }
    
    redireccionarUltimaPagina();
    exit();
}

if (isset($_GET['validar'])) {
    if ($_GET['validar'] === 'true'){
        // Valida session del usuario
        if ($sessionController->validar()) {
            print json_encode(['response' => true]);
            exit();
        }
    } elseif ($_GET['validar'] === 'false') {
        // Cierra session del usuario
        if ($sessionController->cerrar()){
            print json_encode(['response' => true]);
            exit();
        }
    }
        
    print json_encode(['response' => false]);
    exit();
}

print json_encode(['response' => false, 'mensaje' => 'Accion no especificada.']);
exit();
