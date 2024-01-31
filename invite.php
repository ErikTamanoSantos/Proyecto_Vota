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
include './components/log.php';
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

        // Validar los correos electrónicos antes de almacenarlos en la base de datos
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
            // Conectar a tu base de datos para la tabla email_queue
            $mysqliEmailQueue = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

            // Verificar la conexión
            if ($mysqliEmailQueue->connect_error) {
                die("Error de conexión a la base de datos email_queue: " . $mysqliEmailQueue->connect_error);
            }

            // Insertar en email_queue
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

            // Cerrar la conexión y liberar recursos
            $stmtEmailQueue->close();
            $mysqliEmailQueue->close();

            // Conectar a tu base de datos (reemplaza con tus credenciales)
            $mysqli = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

            // Verificar la conexión
            if ($mysqli->connect_error) {
                die("Error de conexión a la base de datos: " . $mysqli->connect_error);
            }

            // Verificar si los correos ya existen en la tabla Users
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

            // Cerrar la conexión y liberar recursos
            $stmtCheckEmails->close();

            if (!empty($erroresDuplicados)) {
            } else {
                // Preparar la consulta para insertar correos electrónicos en la tabla Users
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

                // Obtener el ID del último usuario insertado
                $ultimoID = $mysqli->insert_id;

                // Cerrar la conexión y liberar recursos
                $stmt->close();
                $mysqli->close();

                // Conectar a tu base de datos para la tabla Poll_InvitedUsers
                $mysqliInvited = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

                // Verificar la conexión
                if ($mysqliInvited->connect_error) {
                    die("Error de conexión a la base de datos: " . $mysqliInvited->connect_error);
                }

                // Preparar la consulta para insertar en Poll_InvitedUsers
                $stmtInvited = $mysqliInvited->prepare("INSERT INTO poll_invitedusers (UserID, PollID, tokenQuestion) VALUES (?, ?, ?)");


                if (!$stmtInvited) {
                    die("Error preparing statement: " . $mysqliInvited->error);
                }

                // Sustituir 'OtroCampo' por el nombre real del otro campo que quieras insertar
                $otroCampoValor = $_GET['pollID'];
                $token = generarToken(); // Generar un token utilizando la función generarToken

                $stmtInvited->bind_param("iss", $ultimoID, $otroCampoValor, $token);

                if (!$stmtInvited->execute()) {
                    die("Error executing statement: " . $stmtInvited->error);
                }

                // Cerrar la conexión y liberar recursos
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


<!-- Añadir el título -->
<h1>Invitar a usuarios</h1>


<script>
    $(document).ready(function() {
        var form = $('<form></form>', {
            'method': 'post',
            'action': '',
        });

        $('body').append(form);

        // Crear el textarea dinámicamente
        var textarea = $('<textarea></textarea>', {
            'id': 'emailTextarea',
            'name': 'emails',
            'placeholder': 'Introduce correos electrónicos separados por comas o saltos de línea',
            'rows': '4',
            'cols': '50',
            'oninput': 'checkEmails()'
        });

        // Adjuntar el textarea al body
        $('form').append(textarea);

        // Crear el botón de enviar dinámicamente
        var enviarButton = $('<button></button>', {
            'id': 'enviarButton',
            'text': 'Enviar',
            'style': 'display:none', // Inicialmente oculto
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
