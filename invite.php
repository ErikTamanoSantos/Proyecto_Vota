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
        try {
            $hostname = "localhost";
            $dbname = "project_vota";
            $username = $dbUser;
            $pw = $dbPass;
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
        } catch (PDOException $e) {
            echo "Failed to get DB handle: ". $e->getMessage();
            escribirEnLog("[invite] ".$e);
            exit;
        }

        // Validar los correos electrónicos antes de almacenarlos en la base de datos
        $arrayEmails = preg_split('/[,\n]+/', $emails);
        $arrayEmails = array_map('trim', $arrayEmails);

        $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        $correosIncorrectos = [];
        $correosCorrectos = [];

        foreach ($arrayEmails as $email) {
            if (preg_match($emailRegex, $email)) {
                $alreadySent = false;
                foreach ($correosCorrectos as $emailCheck) {
                    if ($email == $emailCheck) {
                        $alreadySent = true;
                    }
                }
                if (!$alreadySent) {
                    $query = $pdo -> prepare("SELECT count(*) as `counter` FROM Users WHERE Email = ?");
                    $query->bindParam(1, $email);
                    $query->execute();
                    $row = $query->fetch();
                    if ($row && $row["counter"] > 0) {
                    } else {
                        $pdo->beginTransaction();
                        $insertQuery = $pdo -> prepare("INSERT INTO Users(`email`) VALUES (?)");
                        $insertQuery ->bindParam(1, $email);
                        $insertQuery ->execute();
                        $pdo->commit();
                    }
                    $emailID = "";
                    echo $email;

                    $query = $pdo -> prepare("SELECT ID FROM Users WHERE Email = ?");
                    $query->bindParam(1, $email);
                    $query->execute();
                    $row = $query->fetch();
                    if ($row) {
                        $emailID = $row["ID"];
                    } 
                    echo "<p>$emailID</p>";
                    $token = generarToken();
                    $pdo->beginTransaction();
                    $query = $pdo -> prepare("INSERT INTO Poll_InvitedUsers(UserID, PollID, tokenQuestion) VALUES (?, ?, ?)");
                    $query->bindParam(1, $emailID);
                    $query->bindParam(2, $_GET["pollID"]);
                    $query->bindParam(3, $token);
                    $query->execute();
                    $query = $pdo -> prepare("INSERT INTO email_queue(email) VALUES (?)");
                    $query->bindParam(1, $email);
                    $query->execute();
                    $pdo->commit();
                    $correosCorrectos[] = $email;
                }
            } else {
                $correosIncorrectos[] = $email;
            }
        }

        if (!empty($correosCorrectos)) {
            /*
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
                echo var_dump($erroresDuplicados);
            } else {

                $mysqliCheckEmail = new mysqli("localhost", $dbUser, $dbPass, "project_vota");
                $stmtCheckEmail = $mysqliCheckEmail->prepare("SELECT count(*) FROM Users WHERE Email = ?");
                $stmtCheckEmail->bind_param("s", $emailValido);
                $stmtCheckEmail->execute();
                $stmtCheckEmail->bind_result($checkEmail);
                $stmtCheckEmail->fetch();
                echo $checkEmail;
                if (!$checkEmail) {
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
                    //$ultimoID = $mysqli->insert_id;
                    // Cerrar la conexión y liberar recursos
                    $stmt->close();
                    $mysqli->close();
                    }
            }

                

            // Verificar la conexión
            if ($mysqliInvited->connect_error) {
                die("Error de conexión a la base de datos: " . $mysqliInvited->connect_error);
            }

            foreach ($correosCorrectos as $emailValido) {
                // Conectar a tu base de datos para la tabla Poll_InvitedUsers
                $mysqliMail = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

                $idQuery = $mysqliMail->prepare("SELECT ID FROM Users WHERE Email = ?");
                $idQuery->bind_param("s", $emailValido);
                $idQuery->execute();
                $idQuery->bind_result($emailID);
                $idQuery->fetch();

                // Conectar a tu base de datos para la tabla Poll_InvitedUsers
                $mysqliInvited = new mysqli("localhost", $dbUser, $dbPass, "project_vota");

                // Preparar la consulta para insertar en Poll_InvitedUsers
                $stmtInvited = $mysqliInvited->prepare("INSERT INTO Poll_InvitedUsers (UserID, PollID, TokenQuestion) VALUES (?, ?, ?)");

                if (!$stmtInvited) {
                    die("Error preparing statement: " . $mysqliInvited->error);
                }

                // Sustituir 'OtroCampo' por el nombre real del otro campo que quieras insertar
                $otroCampoValor = $_GET['pollID'];
                $token = generarToken(); // Generar un token utilizando la función generarToken

                $stmtInvited->bind_param("iss", $emailID, $otroCampoValor, $token);

                if (!$stmtInvited->execute()) {
                    die("Error executing statement: " . $stmtInvited->error);
                }

                // Cerrar la conexión y liberar recursos
                $stmtInvited->close();
                $mysqliInvited->close();

            }*/

                echo '<script>showNotification("success", "¡Correos electrónicos almacenados con éxito!");</script>';
            
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
