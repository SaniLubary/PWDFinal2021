/**
 * Chequea si la persona se encuentra loggeada
 */
async function checkSession() {
    await fetch(`./requests.php?validar=true`, )
        .then(response => response.json())
        .then(data => {
            if (data.response == false) {
                // Redirect to Login Page
                window.location.replace("./login.php");
                return false
            } else {
                return true
            }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
            return false
        });
}

/**
 * Llama a Requests.php para cerrar la sesion activa
 */
function cerrarSession() {
    fetch('./requests.php?validar=false')
        .then(response => response.json())
        .then(data => {
            if (data.response == false) {
                if (data.mensaje) {
                    alert(data.mensaje)
                } else {
                    alert('Hubo un problema.')
                }
            } else {
                window.location.reload(true)
            }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

/**
 * LLama al scripts Requests.php para agregar el producto al carrito.
 * @param {Number} idproducto Id producto a agregar
 * @param {Boolean|Number} user_validado
 */
async function agregarAlCarrito(idproducto, user_validado) {
    let cicantidad = getCicantidad(idproducto);
    
    // Si no se escribio un valor correcto, no continua
    if ( cicantidad!=null && !isNaN(cicantidad) && user_validado ) {
        fetch(`./requests.php?idproducto=${idproducto}&cicantidad=${cicantidad}`)
            .then(response => response.json())
            .then(data => {
                if (data.response == true) {
                    document.getElementById('mensajes_operaciones').innerHTML = `
                    <i class="bi bi-bookmark-check"></i> Producto ${idproducto} agregado al carrito.
                    <a class="btn mb-1" aria-current="page" href="./comprar.php">
                        <i class="bi bi-arrow-right-short"></i>
                        <i class="bi bi-cart4"></i>
                        Ver carrito
                    </a>
                    `;

                    document.getElementById('mensajes_operaciones').style.display = 'block'
                    window.location.replace("#main-title");
                } else {
                    alert('Se produjo un error.')
                }
            })
            .catch(error => {
                console.error('Ocurrio un problema en la llamada ajax:', error);
            });
    } else if ( cicantidad!=null && cicantidad && !user_validado) {
        // Si el usuario no esta validado, el producto seleccionado se guarda en cookies.
        // Para cuando el usuario se identifique, la pagina podra saber el producto 
        //  seleccionado previo a la validacion, y agregarlo al carrito.
        document.cookie =`producto=${idproducto}; path=/`
        document.cookie =`cicantidad=${cicantidad}; path=/`
        window.location.replace("./login.php");
        return false
    } else if (cicantidad == null) {
        return false;
    }
}

function formOnSubmit() {
    // Se oculta el formuilario y se muestra un mensaje de 'Cargando'
    let form = document.querySelector('form');
    form.style.display = 'none'
    document.getElementById('cargando').style.display = 'block'
    let pass = document.getElementById("uspass").value

    if (pass !== '') {
        document.getElementById("uspass").value = hex_md5(pass)
    }
    
    return true
}

function pagar() {
    fetch('./requests.php?comprar=true')
        .then(response => response.json())
        .then(data => {
            if (data.response == false) {
                if (data.mensaje) {
                    alert(data.mensaje)
                } else {
                    alert('Hubo un problema.')
                    window.location.reload(true)
                }
            } else {
                window.location.reload(true)
            }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

function inputEscribirCantidad(id) {
    let input = document.getElementById(`${id}-cantidad-input`);
    let select = document.getElementById(`${id}-cantidad-select`);
    if (select.value === 'escribir') {
        input.style.display = 'block'
        input.disabled = false
    } else {
        input.style.display = 'none'
        input.disabled = true
    }
}

function getCicantidad(idproducto) {
    let cicantidad = null
    
    let cant_input = document.getElementById(`${idproducto}-cantidad-input`);    
    let cant_select = document.getElementById(`${idproducto}-cantidad-select`);
    
    // Se selecciona el valor del select, y si se indico 'Mas cantidad', se toma el valor escrito
    if (cant_input.style.display === 'none' || cant_input.style.display === '') {
        // Se validan valores
        let s_cant = parseFloat(cant_select.value)
        if ( isNaN(s_cant) ) {
            // Si no se selecciono una cantidad, se asume 1 unidad
            cicantidad = 1
        } else {
            cant_select.style.border = '2px solid green'
            cicantidad = s_cant
        }
    } else {
        // Se validan valores
        let i_cant = parseFloat(cant_input.value)
        if ( isNaN(i_cant) ) {
            cant_input.style.border = '2px solid red'
        } else {
            cant_input.style.border = '2px solid green'
            cicantidad = i_cant
        }
    }

    return cicantidad;
}

/**
 * Quita un producto del carrito de compras de la persona
 * @param {Number} idcompraitem Id compraitem en el 
 * @param {Number} idproducto Id producto a agregar
 */
function quitarDelCarrito(idproducto, idcompraitem) {
    cicantidad = getCicantidad(idproducto);

    if ( !isNaN(idproducto) && !isNaN(cicantidad)) {
        fetch(`./requests.php?quitar=true&idcompraitem=${idcompraitem}&cicantidad=${cicantidad}`)
            .then(response => response.json())
            .then(data => {
                if (data.response == true) {
                    window.location.replace("./comprar.php");
                } else {
                    alert('Se produjo un error.')
                }
            })
            .catch(error => {
                alert('Hubo un problema.')
                console.error('Ocurrio un problema en la llamada ajax:', error);
            });
    }
}
