window.onload = () => {
  var btn_guardar = document.getElementById('btn-guardar')
  var btn_cerrar_modal = document.getElementById('btn-cerrar-modal')
  
  btn_guardar.onclick = (e) => {
    if (guardarElemento()) {
      // si todo bien, se cierra el modal
      let event = new Event("click");
      btn_cerrar_modal.dispatchEvent(event)
    }
  }

}

function eliminarElemento(id) {
  fetch('./requests.php?delete=true&idmenu='+id)
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

function guardarElemento() {
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

  fetch('./requests.php?create=true&'+ new URLSearchParams(json_params))
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
function actualizar(id) {
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

  fetch('./requests.php?update=true&'+ new URLSearchParams(json_params))
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
