<?php 
header('Content-Type: text/html; charset=utf-8');
header ("Cache-Control: no-cache, must-revalidate ");

/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////

session_start();

/**
 * Setear $PROYECTO con la ruta del proyecto que tengas, tal que:
 *  'http://localhost/hasta/el/root/de/tu/proyecto' 
 *  Por ejemplo 'Facu/TPFinal'
 */
$PROYECTO = 'Facu/TPFinal';
$ROOT = $_SERVER['DOCUMENT_ROOT']."/$PROYECTO/";

require $ROOT.'utils/funciones.php';

// Valida session del user
$sessionController = new SessionController();
$user_validado = false;
if ($sessionController->validar())
    $user_validado = true;   

$rol = $sessionController->getRol();

// Solo se continua si no se hizo un pedido a requests.php
// Si estamos haciendo un request a requests.php, no queremos redirecciones raras
if (!strpos( $_SERVER['REQUEST_URI'], 'requests.php' )) {
  // admin no compra, se obliga a redirigir a ./admin.php
  if ($rol === 1 && !strpos( $_SERVER['REQUEST_URI'], 'admin' ) ) {
    header("Location: ./admin.php");
    exit();
  }
}
