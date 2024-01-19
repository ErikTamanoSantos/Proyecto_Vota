function mostrarNotificacion(tipo, mensaje) {
    var notificacion = document.createElement('div');
    notificacion.className = 'notificacion ' + tipo;
    notificacion.innerText = mensaje;

    // Agregar ícono de cerrar
    var cerrarIcono = document.createElement('span');
    cerrarIcono.className = 'cerrar-icono';
    cerrarIcono.innerHTML = '&times;'; // Carácter X

    cerrarIcono.addEventListener('click', function() {
      cerrarNotificacion(notificacion);
    });

    notificacion.appendChild(cerrarIcono);

    var contenedor = document.getElementById('contenedorNotificaciones');
    contenedor.appendChild(notificacion);

    notificacion.style.display = 'block';
}

function cerrarNotificacion(notificacion) {
    notificacion.style.display = 'none';
    var contenedor = document.getElementById('contenedorNotificaciones');
    contenedor.removeChild(notificacion);
}

window.onload = mostrarNotificacion("error", "Se produjo un error al realizar la operación");
