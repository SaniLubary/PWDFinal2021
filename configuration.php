<?php header('Content-Type: text/html; charset=utf-8');
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
$PROYECTO ='Facu/TPFinal';
$ROOT =$_SERVER['DOCUMENT_ROOT']."/$PROYECTO/";

require $ROOT.'utils/funciones.php';
