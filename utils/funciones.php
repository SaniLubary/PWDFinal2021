<?php 

function verEstructura($e){
    echo "<pre>";
    print_r($e);
    echo "</pre>"; 
}

function __autoload($class_name){
    global $ROOT; // seteada en configuracion.php
    
    // echo "class ".$class_name ;
    $directorys = array(
        $ROOT.'controllers/',
        $ROOT.'models/',
        $ROOT.'models/db/'
      //  $GLOBALS['ROOT'].'util/class/',
    );
    // verEstructura($directorys) ;
    foreach($directorys as $directory){
        if(file_exists($directory.$class_name . '.php')){
            // echo "se incluyo".$directory.$class_name . '.php';
            require_once($directory.$class_name . '.php');
            return;
        }
    }
}

?>