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

          <ul class="navbar-nav d-flex flex-row">
            <!-- Opciones de manejo de sesion -->
            <?php 
            if ($user_validado) {
              echo "
                  <li class=\"nav-item active\">
                    <a class=\"nav-link me-1\" aria-current=\"page\" href=\"./comprar.php\">
                    <i class=\"bi bi-cart4\"></i>
                    Ver carrito
                    </a>
                  </li>

                ";
              
              echo "
                <li class=\"nav-item me-3 me-lg-0\">
                  <a class=\"nav-link\" href=\"#\" onclick='cerrarSession();' rel=\"nofollow\">
                  <i class=\"bi bi-box-arrow-right\"></i></i> Cerrar Sesion
                  </a>
                </li>
              ";
            } else {
              echo "
                <li class=\"nav-item me-3 me-lg-0\">
                  <a class=\"nav-link\" href=\"./login.php\" rel=\"nofollow\">
                    <i class=\"bi bi-person-circle\"></i> Iniciar Sesion
                  </a>
                </li>
                <li class=\"nav-item me-3 me-lg-0\">
                  <a class=\"nav-link\" href=\"./register.php\" rel=\"nofollow\">
                    <i class=\"bi bi-person-lines-fill\"></i> Crear Cuenta
                  </a>
                </li>
              ";
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>