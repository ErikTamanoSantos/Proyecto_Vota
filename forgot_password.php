<?php
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

try {
    $hostname = "localhost";
    $dbname = "project_vota";
    $username = $dbUser;
    $pw = $dbPass;
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage();
    escribirEnLog("[RECOVER_PASSWORD] " . $e);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_GET['validToken'])) {
    $destinatario = $_POST['userEmail'];

    // Verificar si el correo está registrado
    $emailCheckQuery = $pdo->prepare("SELECT * FROM Users WHERE Email = ?");
    $emailCheckQuery->execute([$destinatario]);
    $userData = $emailCheckQuery->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        // Verificar si ya existe una solicitud en la tabla password_recovery_requests
        $checkExistingQuery = $pdo->prepare("SELECT * FROM password_recovery_requests WHERE email = ?");
        $checkExistingQuery->execute([$destinatario]);
        $existingData = $checkExistingQuery->fetch(PDO::FETCH_ASSOC);

        if ($existingData) {
            // Tomar el token existente
            $token = $existingData['token'];
        } else {
            // Generar un nuevo token
            $token = generarToken();

            // Insertar en la tabla password_recovery_requests
            $insertTokenQuery = $pdo->prepare("INSERT INTO password_recovery_requests (email, token) VALUES (?, ?)");
            $insertTokenQuery->execute([$destinatario, $token]);
        }

        // Enviar el correo con el token
        $title = "Recuperación de Contraseña | Vota EJA";
        $content = "Cambia la contraseña accediendo a este enlace.<br><a class='btn' href='http://localhost/votaciones/Proyecto_Vota/forgot_password.php?validToken=" . $token . "'>Validar cuenta</a>.<br><br>Atentamente, el equipo de Vota EJA.";
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = ""; // Email de la cuenta de correo desde la que se enviarán los correos
        $mail->Password   = ""; // Password de la cuenta de correo

        $mail->IsHTML(true);
        $mail->AddAddress($destinatario);
        $mail->SetFrom("", "Vota EJA");

        $mail->Subject = $title;
        $mail->MsgHTML($content);

        if ($mail->Send()) {
            echo '<script>showNotification("success", "Se ha enviado un correo de prueba.");</script>';
            header("Location: forgot_password.php");
            exit;
        } else {
            // Manejar error de envío de correo
        }
    } else {
        echo '<script>showNotification("error", "El correo electrónico no está registrado.");</script>';
    }
} elseif (isset($_GET['validToken'])) {
    // Si se proporciona un token en la URL, procesar el cambio de contraseña
    $validToken = $_GET['validToken'];

    // Buscar el token en la tabla password_recovery_requests
    $checkTokenQuery = $pdo->prepare("SELECT * FROM password_recovery_requests WHERE token = ?");
    $checkTokenQuery->execute([$validToken]);
    $tokenData = $checkTokenQuery->fetch(PDO::FETCH_ASSOC);

    if ($tokenData) {
        // Mostrar el formulario de cambio de contraseña
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener la nueva contraseña del formulario
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Verificar que las contraseñas coincidan
            if ($newPassword !== $confirmPassword) {
                // Contraseñas no coinciden, mostrar mensaje de error
                echo '<script>showNotification("error", "Las contraseñas no coinciden.");</script>';
            } else {
                // Cifrar la contraseña con hash sha512
                $hashedPassword = hash('sha512', $newPassword);

                // Actualizar la contraseña en la base de datos
                $updatePasswordQuery = $pdo->prepare("UPDATE Users SET Password = ? WHERE Email = ?");
                $updatePasswordQuery->execute([$hashedPassword, $tokenData['email']]);

                // Eliminar la entrada en la tabla password_recovery_requests
                $deleteTokenQuery = $pdo->prepare("DELETE FROM password_recovery_requests WHERE token = ?");
                $deleteTokenQuery->execute([$validToken]);

                // Enviar un correo de confirmación u otro mensaje si es necesario
                echo '<script>showNotification("success", "Contraseña cambiada exitosamente.");</script>';
                header("Location: forgot_password.php");
            }
        } else {
            // Mostrar el formulario de cambio de contraseña
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="styles.css">
				<script src="functions.js"></script>
				<link rel="icon" href="./img/vota-si.png" />
				<script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
				<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
				<title>Cambiar Contraseña | Vota EJA</title>
				<script>
					function validatePassword() {
						var newPassword = document.getElementById("newPassword").value;
						var confirmPassword = document.getElementById("confirmPassword").value;

						// Agrega tus propias reglas de validación aquí
						var passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

						if (!passwordRegex.test(newPassword)) {
							showNotification('error', 'La contraseña debe contener al menos una mayúscula, un número y un carácter especial, y tener al menos 8 caracteres.');
							return false;
						}

						if (newPassword !== confirmPassword) {
							showNotification('error', 'Las contraseñas no coinciden.');
							return false;
						}

						return true;
					}
				</script>
			</head>
				<body>
                <?php include './components/header.php'; ?>
                <?php include './components/log.php'; ?>
                <div id="notificationContainer"></div>
                <section class="loginSection">
                    <h1>Cambiar Contraseña</h1>
                    <div class="loginForm">
                        <div class="circleLoginForm">
                            <h3>Ingresa tu nueva contraseña</h3>
                            <form method="POST" action="" onsubmit="return validatePassword()">
                                <input type="password" name="newPassword" id="newPassword" placeholder="Nueva Contraseña" required>
                                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirmar Contraseña" required>
                                <input type="hidden" name="token" value="<?= $validToken ?>">
                                <button type="submit" class="btnForm">Cambiar Contraseña</button>
                            </form>
                        </div>
                    </div>
                    <div class="loginImg">
                        <!-- Puedes personalizar esta parte según tus necesidades -->
                        <img src="./img/change_password.svg">
                    </div>
                </section>
                <?php include './components/footer.php'; ?>
            </body>
            </html>
            <?php
        }
    } else {
        // Redirigir a la página normal o mostrar un mensaje
        header("Location: forgot_password.php");
        
        exit;
    }
} else {
    // Código restante para el caso normal (sin token en la URL)
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <script src="functions.js"></script>
        <link rel="icon" href="./img/vota-si.png" />
        <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <title>Recuperar Contraseña | Vota EJA</title>
        <script>
             function validatePassword() {
                var newPassword = document.getElementById("newPassword").value;
                var confirmPassword = document.getElementById("confirmPassword").value;
                var passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!passwordRegex.test(newPassword)) {
                    showNotification('error', 'La contraseña debe contener al menos una mayúscula, un número y un carácter especial, y tener al menos 8 caracteres.');
                    return false;
                }
                if (newPassword !== confirmPassword) {
                    showNotification('error', 'Las contraseñas no coinciden.');
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body>
        <?php include './components/header.php'; ?>
        <?php include './components/log.php'; ?>
        <div id="notificationContainer"></div>
        <section class="loginSection">
            <h1>Recuperar Contraseña</h1>
            <div class="loginForm">
                <div class="circleLoginForm">
                    <h3>Ingresa tu correo electrónico</h3>
                    <form method="POST" action="" onsubmit="return validatePassword()">
                        <input type="email" name="userEmail" placeholder="Correo Electrónico" required>
                        <button type="submit" class="btnForm">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>
            <div class="loginImg">
                <img src="./img/forgot_password.svg">
            </div>
        </section>
        <?php include './components/footer.php'; ?>
    </body>
    </html>
    <?php
}
?>
