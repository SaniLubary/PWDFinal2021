<footer class="mt-auto bg-light text-lg-start">
  <?php 
        if ($user_validado && (isset($rol) && $rol !== 1)) {
          // mostrar carrito o boton 'volver' acorde a vista actual
          if ($_SESSION['url'] !== "$PROYECTO/vista/comprar.php") {
            echo "
            <div class=\"py-4 text-center\">
              <a role=\"button\" class=\"btn btn-primary btn-lg m-2\" href=\"./comprar.php\">
                <i class=\"bi bi-cart4\"></i>
                Ver carrito
              </a>
            </div>
            ";
          } else {
            echo "
            <div class=\"py-4 text-center\">
              <a role=\"button\" class=\"btn btn-primary btn-lg m-2\" href=\"./\">
                <i class=\"bi bi-arrow-return-left\"></i>
                Volver
              </a>
            </div>
            ";
          }
          
        }
      ?>

    <hr class="m-0" />

    <div class="text-center p-3"">
      © 2021 Copyright:
      <a class="text-dark" href="#">Tienda de Cuadros Online</a>
    </div>
</footer>