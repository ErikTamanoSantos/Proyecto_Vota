<?php
session_start();
include 'log.php';
include("config.php");

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
        return true; // Envío exitoso
    } else {
        return false; // Error en el envío
    }
}

try {
    $dsn = "mysql:host=localhost;dbname=project_vota";
    $pdo = new PDO($dsn, 'root', '');

    // Obtener el ID del usuario actual
    $userID = $_SESSION['UserID'];

} catch (PDOException $e) {
    echo $e->getMessage();
    escribirEnLog("[ENVIAR] " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        // Conectar a la base de datos (reemplaza con tus credenciales)
        $mysqli = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

        // Verificar la conexión
        if ($mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $mysqli->connect_error);
        }

       // Obtener correos electrónicos de la tabla email_queue (limitado a 5)
        $result = $mysqli->query("SELECT email FROM email_queue LIMIT 5");


        // Verificar si hay correos electrónicos en la cola
        if ($result->num_rows > 0) {
            // Configuración del servidor SMTP (reemplaza con tu configuración)
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = "anaviogarcia.cf@iesesteveterradas.cat"; // Email de la cuenta de correo desde la que se enviarán los correos
            $mail->Password   = "Caqjuueeemke64"; // Password de la cuenta de correo
    

            // Iterar sobre los resultados y enviar correos electrónicos
            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];

                // Puedes ajustar el nombre de usuario según tus necesidades
                if (enviarCorreo($email, "Nombre de Usuario")) {
                    echo "Correo enviado a: $email<br>";
                } else {
                    echo "Error al enviar correo a: $email<br>";
                }
            }

            // Eliminar todos los correos electrónicos de la tabla después de enviarlos
            $mysqli->query("DELETE FROM email_queue");

            // Cerrar la conexión
            $mysqli->close();
        } else {
            echo "No hay correos electrónicos en la cola.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>