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
 * @param {Number} cicantidad Cuanto del producto a agregar
 * @param {Boolean|Number} user_validado
 */
async function agregarAlCarrito(idproducto, cicantidad, user_validado) {
    if (user_validado) {
        fetch(`./requests.php?idproducto=${idproducto}&cicantidad=${cicantidad}`)
            .then(response => response.json())
            .then(data => {
                if (data.response == true) {
                    alert('Producto agregado al carrito.')
                } else {
                    alert('Se produjo un error.')
                }
            })
            .catch(error => {
                alert('Hubo un problema.')
                console.error('Ocurrio un problema en la llamada ajax:', error);
            });
    } else {
        // Si el usuario no esta validado, el producto seleccionado se guarda en cookies.
        // Para cuando el usuario se identifique, la pagina podra saber el producto 
        //  seleccionado previo a la validacion, y agregarlo al carrito.
        document.cookie =`producto=${idproducto}; path=/`
        window.location.replace("./login.php");
    }

}

/**
 * Llama a 'agregar al carrito' pero redirije a la pagina de confirmar compra a la vez
 */
async function comprar(idproducto, cicantidad, user_validado) {
    await agregarAlCarrito(idproducto, cicantidad, user_validado);
    window.location.replace("./comprar.php");
}

/**
 * Cifra la contrasenia para el submit
 *  Aplica estilo al formulario para input correctos/incorrectos
 * @param {event} e Obj event del boton submit
 * @returns 
 */
function submitLoginRegister(e) {
    var t = evt.target;
    let form_correcto = true
    
    // Aplicar estilos por cada input requerido
    let form_inputs = document.querySelectorAll('form [required]')
    for(let i=0; i < form_inputs.length; i++){
        if(form_inputs[i].value === '' && form_inputs[i].hasAttribute('required')){
            form_inputs[i].style.backgroundColor = 'rgba(255, 0, 0, 0.42)'
            form_correcto = false
        } else {
            form_inputs[i].style.backgroundColor = 'rgba(0, 255, 95, 0.42)'
        }
    }

    if (!form_correcto) return false;
    
    let form = document.querySelector('form');
    // Se oculta el formuilario y se muestra un mensaje de 'Cargando'
    form.style.display = 'none'
    document.getElementById('cargando').style.display = 'block'
    document.getElementById("uspass").value = hex_md5(document.getElementById("uspass").value)

    // Se completa la accion del boton 'submit'
    t.dispatchEvent( evt )
    return false
}
