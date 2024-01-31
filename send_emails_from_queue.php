<?php
session_start();
include './components/log.php';
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


function getTokenFromEmail($email) {
    include("config.php");
    try {
        $dsn = "mysql:host=localhost;dbname=project_vota";
        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $stmt = $pdo->prepare("SELECT tokenQuestion FROM Poll_InvitedUsers WHERE UserID IN (SELECT ID FROM Users WHERE email = ?)");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $result = $stmt->fetch();
        echo $result['tokenQuestion'];
        echo '<div>';
        if ($result) {
            return $result['tokenQuestion'];
        } else {
            return null; // Usuario no encontrado o sin token
        }
        
       
        $userID = $_SESSION['UserID'];
    
    } catch (PDOException $e) {
        echo $e->getMessage();
        escribirEnLog("[ENVIAR] " . $e->getMessage());
    }
}


function enviarCorreo($pdo, $destinatario, $username) {
    
    $token = getTokenFromEmail($destinatario);

    $title = "Has sido invitado para votar, " . $username . "!";
    $content = "Puedes votar en el siguiente enlace: http://localhost:8080/votePoll.php?tokenQuestion=$token";

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug  = 2;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "etamanosantos.cf@iesesteveterradas.cat"; 
    $mail->Password   = "Dennis12Erik19!"; 

    $mail->IsHTML(true);
    $mail->AddAddress($destinatario);
    $mail->SetFrom("eja@email.com", "Vota EJA");

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
        $mail->Username   = "etamanosantos.cf@iesesteveterradas.cat"; 
        $mail->Password   = "Dennis12Erik19!"; // Password de la cuenta de correo


        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
            $token = getTokenFromEmail($email);
                    
            if ($token) {
                if (enviarCorreo($pdo, $email, "Nombre de Usuario")) {
                    echo "Correo enviado a: $email<br>";
                } else {
                    echo "Error al enviar correo a: $email<br>";
                }
            } else {
                echo "No se encontró token para el correo: $email<br>";
            }
        }
        

        $mysqli->query("DELETE FROM email_queue");

        $mysqli->close();
    } else {
        echo "No hay correos electrónicos en la cola.";
    }
} catch (Exception $e) {
    
    escribirEnLog("[ENVIAR]" . $e->getMessage());
}

?>