<?php 

function verEstructura($e) {
    echo "<pre>";
    print_r($e);
    echo "</pre>"; 
}

function redireccionarUltimaPagina() {
    global $PROYECTO;
    
    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    
    if(isset($_SESSION['url'])) 
        $url = $_SESSION['url'];
    else 
        $url = "$PROYECTO/vista/";

    header("Location: http://$host:$port/$url");
    exit();
}

function utf8_converter($array, $json)
{
    array_walk_recursive($array, function (&$item) {
        $item = utf8_encode($item);
    });
    
    if ($json === true) return json_encode($array);
    return $array;
}

spl_autoload_register(function ($class_name) {
    global $ROOT; // seteada en configuracion.php
    
    $directorys = array(
        $ROOT.'controllers/',
        $ROOT.'models/',
        $ROOT.'models/db/'
    );

    foreach ($directorys as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            include($directory . $class_name . '.php');
            return;
        }
    }
});

?>