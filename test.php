<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="post" action="">
    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" required>

    <label for="username">Nombre de usuario:</label>
    <input type="text" id="username" name="username" required>

    <button type="submit" name="submit">Enviar</button>
</form>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function generarToken($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $token .= $characters[$randomIndex];
    }
    return $token;
}

function enviarCorreo($destinatario, $username) {
    $token = generarToken();

    $title = "Bienvenido, " . $username . "!";
    $content = "Bienvenido, <strong>" . $username . "</strong>. Valida tu cuenta accediendo a este enlace.<br><a class='btn' href='http://localhost/proyecto_vota/dashboard.php?validToken=" . $token . "'>Validar cuenta</a>.<br><br>Atentamente, el equipo de Vota EJA.";

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug  = 2;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "anaviogarcia.cf@iesesteveterradas.cat"; // Email de la cuenta de correo desde la que se enviarán los correos
    $mail->Password   = "Caqjuueeemke64"; // Password de la cuenta de correo

    $mail->IsHTML(true);
    $mail->AddAddress($destinatario);
    $mail->SetFrom("anaviogarcia.cf@iesesteveterradas.cat", "Vota EJA");

    $mail->Subject = $title;
    $mail->MsgHTML($content);

    if ($mail->Send()) {
        echo '<script>showNotification("success", "¡Registro completado!");</script>';
    } else {
        echo '<script>showNotification("error", "Vaya, parece que no se ha enviado el correo. ' . $mail->ErrorInfo . '");</script>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

    if ($email && $username) {
        enviarCorreo($email, $username);
    } else {
        echo "Los datos del formulario no son válidos.";
    }
}

?>

</body>
</html>
