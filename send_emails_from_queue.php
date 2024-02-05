<?php
session_start();
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


function getTokenFromEmail($email, $pollID) {
    include("config.php");
    try {
        $dsn = "mysql:host=localhost;dbname=project_vota";
        $pdo = new PDO($dsn, $dbUser, $dbPass);

        $stmt = $pdo->prepare("SELECT PIU.tokenQuestion 
                              FROM email_queue EQ
                              JOIN Users U ON EQ.email = U.Email
                              JOIN Poll_InvitedUsers PIU ON U.ID = PIU.UserID AND EQ.PollID = PIU.PollID
                              WHERE U.Email = ? AND EQ.PollID = ?");
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $pollID);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result !== false) {
            return $result['tokenQuestion'];
        } else {
            return null;
        }
            
    } catch (PDOException $e) {
        echo $e->getMessage();
        escribirEnLog("[ENVIAR] " . $e->getMessage());
    }
}

function enviarCorreo($pdo, $destinatario, $username, $pollID) {
    
    $token = getTokenFromEmail($destinatario, $pollID);
    echo $token;
    $title = "Has sido invitado para votar, " . $username . "!";
    $content = "Has sido invitado a votar a una encuesta! Accede a este enlace y realiza tu votación!<a href='localhost/Proyecto_Vota/votePoll.php?tokenQuestion=".$token."'>Accede a la votación</a><br>El equipo de VOTA EJA.";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug  = 2;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "jbernabeucaballero.cf@iesesteveterradas.cat"; 
    $mail->Password   = "KekHut93"; 

    $mail->IsHTML(true);
    $mail->AddAddress($destinatario);
    $mail->SetFrom("jbernabeucaballero.cf@iesesteveterradas.cat", "Vota EJA");

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
    $result = $mysqli->query("SELECT email, PollID FROM email_queue LIMIT 5");

    if ($result->num_rows > 0) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 2;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "jbernabeucaballero.cf@iesesteveterradas.cat";
        $mail->Password   = "KekHut93"; // Password de la cuenta de correo

        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
            $pollID = $row['PollID'];
        
            $token = getTokenFromEmail($email, $pollID);
        
            if ($token) {
                if (enviarCorreo($pdo, $email, "Nombre de Usuario", $pollID)) {
                    echo "Correo enviado a: $email<br>";
                } else {
                    // hacer echo del error:
                    $error = $mail->ErrorInfo;
                    echo "Error al enviar correo a: $email. Detalles del error: $error<br>";
                }
            } else {
                echo "No se encontró token para el correo: $email<br>";
            }
        }

        $mysqli->query("DELETE FROM email_queue LIMIT 5");

        $mysqli->close();
    } else {
        echo "No hay correos electrónicos en la cola.";
    }
} catch (Exception $e) {
    escribirEnLog("[ENVIAR]" . $e->getMessage());
}

?>
