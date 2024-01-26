<?php

function escribirEnLog($mensaje) {
    // Obtiene la fecha y hora actual
    $fechaHoraActual = date('Y-m-d H:i:s');

    // Obtiene solo la fecha para el nombre del archivo
    $fechaActual = date('Y-m-d');

    // Ruta del directorio logs
    $directorioLogs = 'logs/';

    // Verifica si el directorio existe, si no, lo crea
    if (!file_exists($directorioLogs)) {
        mkdir($directorioLogs, 0755, true);
    }

    // Ruta completa del archivo de log con la fecha actual
    $rutaArchivo = $directorioLogs . 'log_' . $fechaActual . '.txt';

    // Abre el archivo en modo escritura (si no existe, lo crea)
    $archivo = fopen($rutaArchivo, 'a');

    // Verifica si se pudo abrir el archivo
    if ($archivo) {
        fwrite($archivo, str_repeat("*", 50) . "\n");
        // Formatea el mensaje con la fecha y hora, seguido del texto
        $mensajeFormateado = $fechaHoraActual . " " . $mensaje . "\n";

        // Escribe el mensaje en el archivo
        fwrite($archivo, $mensajeFormateado);

        // Cierra el archivo
        fclose($archivo);
    } else {
        // Manejo de error si no se puede abrir el archivo
        echo "Error al abrir el archivo de log.";
    }
}


?>
