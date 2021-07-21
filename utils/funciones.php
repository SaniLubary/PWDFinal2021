<?php 
function data_submitted() {
    
    $_AAux= array();
    if (!empty($_REQUEST))
        $_AAux =$_REQUEST;
     if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
     return $_AAux;
        
}
function verEstructura($e){
    echo "<pre>";
    print_r($e);
    echo "</pre>"; 
}
function console_log($data) {
    echo "<script>";
    echo "console.log('$data')";
    echo "</script";
}
function verificarSesion(){
    if (!isset($_SESSION['usuario'])) {
        // Si no viene del weblogin, lo redirigimos alla
        header('https://weblogin.muninqn.gov.ar');
        die();
    }
}

/**
 * Chequea que el tamaño y tipo de archivos subidos sean los correctos
 * JS Alert si no lo son
 * @param int maxsize en mb del archivo, default 200mb
 * @param array formatos aceptados, default:
    $acceptable = array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png',
        'video/mp4',
        'video/mpeg'
    );
    * @return bool false si hubo un error en el chequeo de archivos
    */
function checkFile( $maxsize = 200, $acceptable = array('application/pdf','image/jpeg','image/jpg','image/gif','image/png','video/mp4','video/mpeg') )
{
    if (isset($_FILES['archivoProyecto'])) {
        $errors = array();
        
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        
        $maxsize_multiplied = $maxsize*1000000;

        // verEstructura($_FILES);
        foreach ($_FILES['archivoProyecto']['size'] as $key => $value) {
            if ( ($value >= $maxsize_multiplied) && ($value != 0) ) {
                $errors[] = "$key Archivo adjunto muy grande. Debe pesar menos de $maxsize megabytes.";
            }
            if ( (!in_array($_FILES['archivoProyecto']['type'][$key], $acceptable)) && !empty($_FILES['archivoProyecto']['type'][$key]) ) {
                $error = "$key Tipo de archivo invalido. Solamente tipos ";
                foreach ( $acceptable as $value ) {
                    $error .= $value.', ';
                }
                $error .= "se aceptan.";
                $errors[] = $error; 
            }
            if ( $_FILES['archivoProyecto']['error'][$key] != 0 && !empty($_FILES['archivoProyecto']['type'][$key]) ) {
                $errors[] = $phpFileUploadErrors[$key];
            }

        }

        if (count($errors) === 0) {
            return true;
        } else {
            foreach ($errors as $error) {
                echo '<script>alert("' . $error . '");</script>';
            }
            return false;
        }
    }
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