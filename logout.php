<?php
    session_start();
    if (!isset($_SESSION['UserID'])) {
        include('./errors/error403.php');
    } else {
        if(isset($_POST['closeSession'])){
            session_destroy();
            header("Location:./index.php");
        }
        if(isset($_POST['returnToDashboard'])){
            header("Location:./dashboard.php");
        }
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar sesion | Vota EJA</title>
</head>
<body>
    <h1>¿Quieres salir y cerrar tu sesion?<h1>
    <form method="POST">
        <button id="closeSession" name="closeSession">Salir</button>
        <button id="returnToDashboard" name="returnToDashboard">Volver</button>
    </form>
</body>
</html>
<?php
    }
?>