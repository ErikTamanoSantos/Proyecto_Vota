<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>
    <script src="log.php"></script>
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <title>Invitar a usuarios | Vota EJA</title>
</head>
<body>
<?php
session_start();
include 'log.php'; 

try {
    $dsn = "mysql:host=localhost;dbname=project_vota";
    $pdo = new PDO($dsn, 'aleix', 'Caqjuueeemke64*');

    // Obtener el ID del usuario actual
    $userID = $_SESSION['UserID'];
   
} catch (PDOException $e){
    echo $e->getMessage();
    escribirEnLog("[ENVIAR] ".$e);
}
?>

<!-- Añadir el título -->
<h1>Invitar a usuarios</h1>

<script>
    $(document).ready(function() {
        // Crear el contenedor del formulario
        var formContainer = $('<div></div>');

        // Crear el formulario dinámicamente
        var invitarForm = $('<form></form>', {
            'id': 'invitarForm',
            'action': 'guardar_invitaciones.php',
            'method': 'post'
        });

        // Crear el textarea dinámicamente
        var textarea = $('<textarea></textarea>', {
            'id': 'emailTextarea',
            'name': 'emails',
            'placeholder': 'Introduce correos electrónicos separados por coma',
            'rows': '4',
            'cols': '50'
        });

        // Crear el botón de enviar dinámicamente
        var enviarButton = $('<button></button>', {
            'type': 'submit',
            'text': 'Enviar'
        });

        // Agregar textarea y button al formulario
        invitarForm.append(textarea);
        invitarForm.append(enviarButton);

        // Agregar el formulario al contenedor
        formContainer.append(invitarForm);

        // Agregar el contenedor al body
        $('body').append(formContainer);
    });
</script>

</body>
</html>
