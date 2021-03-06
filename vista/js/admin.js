function aumentarEstadoDeCompra(id) {
  fetch('./requests.php?aumentar-estado=true&idcompra='+id)
        .then(response => response.json())
        .then(data => {
          if (data.response == true) {
            window.location.reload(true)
          } else {
            alert('No se pudo actualizar')
            return false;
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

function cancelarCompra(id) {
  fetch('./requests.php?cancelar-compra=true&idcompra='+id)
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.response == true) {
            window.location.reload(true)
          } else {
            alert('No se pudo actualizar')
            return false;
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

function eliminarElemento(id, tabla) {
  fetch(`./requests.php?delete=true&id${tabla}=${id}`)
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.response == true) {
            window.location.reload(true)
          } else {
            if (data.response == 1451) {
              alert('El producto se encuentra en el carro de alguien');
            } else {
              alert('No se pudo eliminar')
            }
            return false;
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

function guardarElemento(tabla) {
  let inputs = document.querySelectorAll(`#modal-body > input, #modal-body > select`)
  
  let params = [];
  inputs.forEach(element => {
    let val = element.value != ''? element.value:''

    // En el caso de ser checkbox, leer el valor 'checked' y reemplacar 'val' acorde
    if (element.type && element.type === 'checkbox') {
      if (element.checked == true) {
        val = true
      } else val = false
    }
    params[element.name] = val;
  });

  let json_params = {...params}

  fetch('./requests.php?create=true&tabla='+tabla+'&'+ new URLSearchParams(json_params))
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.response == true) {
            window.location.reload(true)
          } else {
            alert('No se pudo dar de alta')
            return false;
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

/**
 * Actualiza la info de un elemento en una tabla de elementos
 * @param {string} id id del TR que contiene los inputs de la tabla para el elemento a actualizar 
 */
function actualizar(id, tabla) {
  let inputs = document.querySelectorAll(`#${id} > * > input, #${id} > * > select`)
  
  let params = [];
  inputs.forEach(element => {
    let val = element.value != ''? element.value:''

    // En el caso de ser checkbox, leer el valor 'checked' y reemplacar 'val' acorde
    if (element.type && element.type === 'checkbox') {
      if (element.checked == true) {
        val = true
      } else val = false
    }
    params[element.name] = val;
  });

  let json_params = {...params}

  fetch('./requests.php?update=true&tabla='+tabla+'&'+ new URLSearchParams(json_params))
        .then(response => response.json())
        .then(data => {
          if (data.response == true) {
            window.location.reload(true)
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
  
}


function setearRol(id, rol) {
  fetch(`./requests.php?setrol=true&idusuario=${id}&idrol=${rol}`)
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.response == true) {
            window.location.reload(true)
          } else {
            alert('No se pudo eliminar')
            return false;
          }
        })
        .catch(error => {
            console.error('Ocurrio un problema en la llamada ajax:', error);
        });
}

