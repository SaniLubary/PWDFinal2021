/**
 * Chequea si la persona se encuentra loggeada
 * @idproducto Cuando checkSession es llamado desde agregarAlCarrito(), guardar idproducto en cookies desde Session.php 
 */
async function checkSession(idproducto = '') {
    await fetch(`./requests/Session.php?validar=true&producto=${idproducto}`, )
        .then(response => response.json())
        .then(data => {
            if (data.response == false) {
                // Redirect to Login Page
                window.location.replace("http://localhost/facu/TPFinal/vista/login.php");
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

// Chequea si la persona se encuentra loggeada
function cerrarSession() {
    fetch('./requests/Session.php?validar=false')
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
 * LLama al scripot Carrito.php para agregar el producto al carrito.
 * @param {*} idproducto Id prod a agregar
 */
async function agregarAlCarrito(idproducto, user_validado) {
    //  En el chequeo de sesion se incluye idproducto por si el usuario no tiene sesion iniciada, el producto seleccionado se guarda en cookies mas adelante
    //      Asi una vez el usuario se identifique, la pagina recuerde el producto que habia seleccionado previamente
    if (user_validado) {
        fetch(`./requests/Carrito.php?producto=${idproducto}`, )
            .then(response => response.json())
            .then(data => {
                console.log(data.response);
                if (data.response == true) {
                    alert('Producto agregado al carrito.')
                }
            })
            .catch(error => {
                alert('Hubo un problema.')
                console.error('Ocurrio un problema en la llamada ajax:', error);
            });
    } else {
        document.cookie =`producto=${idproducto}; path=/`
        window.location.replace("http://localhost/facu/TPFinal/vista/login.php");
    }

}

// Cifra la contrasenia para el submit
function submitLoginRegister() {
    let form_inputs = document.querySelectorAll('form [required]')
    let can_submit = true
    for(let i=0; i < form_inputs.length; i++){
        if(form_inputs[i].value === '' && form_inputs[i].hasAttribute('required')){
            form_inputs[i].style.backgroundColor = 'rgba(255, 0, 0, 0.42)'
            can_submit = false
        } else {
            form_inputs[i].style.backgroundColor = 'rgba(0, 255, 95, 0.42)'
        }
    }

    if (!can_submit) return false;
    
    let form = document.querySelector('form');
    form.style.display = 'none'
    document.getElementById('cargando').style.display = 'block'
    document.getElementById("uspass").value = hex_md5(document.getElementById("uspass").value)

    document.querySelector('form').submit()
}
