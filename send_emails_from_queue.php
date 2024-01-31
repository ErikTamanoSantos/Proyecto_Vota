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
    $mail->Username   = ""; 
    $mail->Password   = ""; 

    $mail->IsHTML(true);
    $mail->AddAddress($destinatario);
    $mail->SetFrom("", "Vota EJA");

    $mail->Subject = $title;
    $mail->MsgHTML($content);

    if ($mail->Send()) {
        return true; 
    } else {
        return false; 
    }
}

try {
    $dsn = "mysql:host=localhost;dbname=project_vota";
    $pdo = new PDO($dsn, $dbUser, $dbPass);

   
    $userID = $_SESSION['UserID'];

} catch (PDOException $e) {
    echo $e->getMessage();
    escribirEnLog("[ENVIAR] " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
       
        $mysqli = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

        
        if ($mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $mysqli->connect_error);
        }

       
        $result = $mysqli->query("SELECT email FROM email_queue LIMIT 5");


       
        if ($result->num_rows > 0) {
          
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = ""; // Email de la cuenta de correo desde la que se enviarán los correos
            $mail->Password   = ""; // Password de la cuenta de correo
    

            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];

                if (enviarCorreo($email, "Nombre de Usuario")) {
                    echo "Correo enviado a: $email<br>";
                } else {
                    echo "Error al enviar correo a: $email<br>";
                }
            }
    
            $mysqli->query("DELETE FROM email_queue");

            $mysqli->close();
        } else {
            echo "No hay correos electrónicos en la cola.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        escribirEnLog("[ENVIAR]" . $e->getMessage());
    }
}
?>