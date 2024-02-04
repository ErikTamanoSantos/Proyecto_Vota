<?php
include("config.php");

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinatario = $_POST['userEmail'];
 
    // Verificar si el correo está registrado
    $emailCheckQuery = $pdo->prepare("SELECT * FROM Users WHERE Email = ?");
    $emailCheckQuery->execute([$destinatario]);
    $userData = $emailCheckQuery->fetch(PDO::FETCH_ASSOC);
 
    if ($userData) {
       // Obtener la nueva contraseña del formulario
       $newPassword = $_POST['new_password'];
       $confirmPassword = $_POST['confirm_new_password'];
 
       // Verificar que las contraseñas coincidan
       if ($newPassword !== $confirmPassword) {
          echo '<script>showNotification("error", "Las contraseñas no coinciden.");</script>';
       } else {
          // Cifrar la contraseña con hash sha512
          $hashedPassword = hash('sha512', $newPassword);
 
          // Actualizar la contraseña en la base de datos
          $updatePasswordQuery = $pdo->prepare("UPDATE users SET Password = ? WHERE Email = ?");
          $updatePasswordQuery->execute([$hashedPassword, $destinatario]);
 
          // Mostrar mensaje de éxito
          echo '<script>showNotification("success", "La contraseña se ha actualizado correctamente.");</script>';
       }
    } else {
       // El correo no está registrado, mostrar mensaje de error
       echo '<script>showNotification("error", "El correo electrónico no está registrado.");</script>';
    }
 }
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
    <title>Recuperar Contraseña | Vota EJA</title>
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
        <h1>Recuperar Contraseña</h1>
        <div class="loginForm">
            <div class="circleLoginForm">
                <h3>Ingresa tu correo electrónico</h3>
                <form method="POST" action="" onsubmit="return validatePassword()">
                    <input type="email" name="userEmail" placeholder="Correo Electrónico" required>
                    <form method="POST" action="" onsubmit="return validatePassword()">
            
                    <input type="password" name="new_password" id="newPassword" placeholder="Nueva Contraseña" required>
                    <input type="password" name="confirm_new_password" id="confirmPassword" placeholder="Confirmar Contraseña" required>

                    <button type="submit" class="btnForm">Cambiar Contraseña</button>
</form>
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
