<?php 

$menuController = new MenuController();
$menurolController = new MenuRolController();

// Se buscan los menus correspondientes al rol del usuario
$menus = $menuController->buscar([], $rol);
?>
<!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->
  <!-- Navegacion principal de la pagina -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <div class="container-fluid">
        <!-- Icono de la pagina -->
        <a href="./">
          <img src="./resources/ISOLOGOTIPO PEDCO Sombrero 2020.png" height="56" alt="" loading="lazy"style="margin-top: -3px;" />
        </a>
        <div class="d-flex flex-grow-1">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <!-- Opciones que se veran por cada tipo de usuario -->
            <li class="nav-item active">
              <a class="nav-link" aria-current="page" href="./"><h4><strong>Inicio</strong></h4></a>
            </li>
            <!-- Todo user validado podra ver su nombre de usuario -->
            <?php 
            if ($user_validado) {
            echo "
              <li class=\"nav-item mt-1 me-3 me-lg-0\">
                <a class=\"nav-link\"rel=\"nofollow\">
                  Bienvenido ".$_SESSION['usnombre']."!
                </a>
              </li>
            ";
            }
            ?>
          </ul>

          <!-- El resto de opciones se generan dinamicamente de acuerdo al rol del usuario -->
          <ul class="navbar-nav d-flex flex-row">
            <!-- Opciones de manejo de sesion -->
            <?php 
            foreach ($menus as $menu) {
              $medeshabilitado = $menu->getMedeshabilitado();
              if (!isset($medeshabilitado) or $medeshabilitado === (null || '')) {
                // Si el menu tiene menus-hijo, se crea un dropdown a partir del menu padre
                $hijos_menu = $menuController->buscar(['idpadre'=>$menu->getIdmenu()]);
                $idpadre = $menu->getIdpadre();
                if ( !empty($hijos_menu) ) {
                  crearDropDown($menu, $hijos_menu);
                } else if ( !is_numeric($idpadre) ) { // Si no tiene idpadre ni hijos
                  // Se crean botones de navegacion normales
                  $nombre = $menu->getMenombre();
                  $clases = $menu->getMedescripcion()?$menu->getMedescripcion():'';
  
                  // Las acciones pueden ser redireccion ('href') o funciones ('onclick')
                  $accion = getAccion($nombre);
                  
                  echo "
                  <li class=\"nav-item active\">
                    <a class=\"nav-link me-1\" aria-current=\"page\" $accion>
                    <i class=\"$clases\"></i>
                    $nombre
                    </a>
                  </li>";
                }
              }
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>

<?php

// Acciones a tomar para los diferentes botones
function getAccion($nombre) {
  switch ($nombre) {
    case 'Ver Carrito':
      return 'href="./comprar.php"';
    case 'Cerrar Sesion':
      return "href='#' onclick='cerrarSession();'";
    case 'Iniciar Sesion':
      return 'href="./login.php"';
    case 'Crear Cuenta':
      return 'href="./register.php"';
    case 'Datos Personales':
      return 'href="./datosPersonales.php"';
    case 'Historial de Compras':
      return 'href="./historial.php"';
    case 'Compras':
      return 'href="./admin_compras.php"';
    default:
      return 'href="./"';
  }
}

function crearDropDown($menu, $hijos_menu) {
  $li_elements = '';
  foreach ($hijos_menu as $hijo) {
    $nombre_hijo = $hijo->getMenombre();
    $accion_hijo = getAccion($nombre_hijo);
    
    $li_elements .= "<li><a class=\"dropdown-item\" $accion_hijo> $nombre_hijo</a></li>";
  }
  
  $idmenu = $menu->getIdmenu();
  $nombre = $menu->getMenombre();
  $clases = $menu->getMedescripcion();
  
  echo "
  <li class=\"nav-item active\">
    <div class=\"dropdown float-start\">
      <a class=\"nav-link me-1 dropdown-toggle\"  id=\"dropdownMenu-$idmenu\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\" aria-current=\"page\" href=\"#\">
        <i class=\"$clases\"></i>
        $nombre
      </a>
      <ul class=\"dropdown-menu dropdown-menu-dark\" aria-labelledby=\"dropdownMenu-$idmenu\">
        $li_elements
      </ul>
    </div>
  </li>
  ";
}

?>