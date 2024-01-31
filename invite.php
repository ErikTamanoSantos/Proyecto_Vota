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
<div id="notificationContainer"></div>
<?php
session_start();
include 'log.php';
include("config.php");

function generarToken($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $token .= $characters[$randomIndex];
    }
    return $token;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['emails'])) {
        $emails = $_POST['emails'];

        
        $arrayEmails = preg_split('/[,\n]+/', $emails);
        $arrayEmails = array_map('trim', $arrayEmails);

        $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        $correosIncorrectos = [];
        $correosCorrectos = [];

        foreach ($arrayEmails as $email) {
            if (preg_match($emailRegex, $email)) {
                $correosCorrectos[] = $email;
            } else {
                $correosIncorrectos[] = $email;
            }
        }

        if (!empty($correosCorrectos)) {
           
            $mysqliEmailQueue = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

            
            if ($mysqliEmailQueue->connect_error) {
                die("Error de conexión a la base de datos email_queue: " . $mysqliEmailQueue->connect_error);
            }

           
            $stmtEmailQueue = $mysqliEmailQueue->prepare("INSERT INTO email_queue (email) VALUES (?)");

            if (!$stmtEmailQueue) {
                die("Error preparing statement: " . $mysqliEmailQueue->error);
            }

            $stmtEmailQueue->bind_param("s", $email);

            foreach ($correosCorrectos as $email) {
                if (!$stmtEmailQueue->execute()) {
                    die("Error executing statement: " . $stmtEmailQueue->error);
                }
            }

           
            $stmtEmailQueue->close();
            $mysqliEmailQueue->close();

           
            $mysqli = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

           
            if ($mysqli->connect_error) {
                die("Error de conexión a la base de datos: " . $mysqli->connect_error);
            }

            
            $stmtCheckEmails = $mysqli->prepare("SELECT COUNT(*) FROM Users WHERE Email = ?");
            $stmtCheckEmails->bind_param("s", $email);

            $erroresDuplicados = [];

            foreach ($correosCorrectos as $emailValido) {
                $stmtCheckEmails->execute();
                $stmtCheckEmails->bind_result($count);
                $stmtCheckEmails->fetch();
            
                if ($count > 0) {
                    $erroresDuplicados[] = $emailValido;
                }
            }

           
            $stmtCheckEmails->close();

            if (!empty($erroresDuplicados)) {
            } else {
                
                $stmt = $mysqli->prepare("INSERT INTO Users (Email) VALUES (?)");

                if (!$stmt) {
                    die("Error preparing statement: " . $mysqli->error);
                }

                $stmt->bind_param("s", $emailValido);

                foreach ($correosCorrectos as $emailValido) {
                    if (!$stmt->execute()) {
                        die("Error executing statement: " . $stmt->error);
                    }
                }

               
                $ultimoID = $mysqli->insert_id;

                
                $stmt->close();
                $mysqli->close();

                
                $mysqliInvited = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

               
                if ($mysqliInvited->connect_error) {
                    die("Error de conexión a la base de datos: " . $mysqliInvited->connect_error);
                }

               
                $stmtInvited = $mysqliInvited->prepare("INSERT INTO Poll_InvitedUsers (UserID, PollID, TokenQuestion) VALUES (?, ?, ?)");

                if (!$stmtInvited) {
                    die("Error preparing statement: " . $mysqliInvited->error);
                }

                
                $otroCampoValor = $_GET['pollID'];
                $token = generarToken();

                $stmtInvited->bind_param("iss", $ultimoID, $otroCampoValor, $token);

                if (!$stmtInvited->execute()) {
                    die("Error executing statement: " . $stmtInvited->error);
                }

               
                $stmtInvited->close();
                $mysqliInvited->close();

                echo '<script>showNotification("success", "¡Correos electrónicos almacenados con éxito!");</script>';
            }
        }

        if (!empty($correosIncorrectos)) {
            echo "Los siguientes correos electrónicos no son válidos: " . implode(', ', $correosIncorrectos);
        }
    } else {
        echo "No se proporcionaron correos electrónicos.";
    }
}
?>


<h1>Invitar a usuarios</h1>


<script>
    $(document).ready(function() {
        var form = $('<form></form>', {
            'method': 'post',
            'action': '',
        });

        $('body').append(form);

       
        var textarea = $('<textarea></textarea>', {
            'id': 'emailTextarea',
            'name': 'emails',
            'placeholder': 'Introduce correos electrónicos separados por comas o saltos de línea',
            'rows': '4',
            'cols': '50',
            'oninput': 'checkEmails()'
        });

        
        $('form').append(textarea);

       
        var enviarButton = $('<button></button>', {
            'id': 'enviarButton',
            'text': 'Enviar',
            'style': 'display:none', 
            'click': function() {
                almacenarEnArray();
                validarEmails();
            }
        });

        $('form').append(enviarButton);
    });

    function checkEmails() {
        var emails = $('#emailTextarea').val();
        var enviarButton = $('#enviarButton');

        if (emails.trim() !== '') {
            enviarButton.show();
        } else {
            enviarButton.hide();
        }
    }
</script>


</body>
</html>
