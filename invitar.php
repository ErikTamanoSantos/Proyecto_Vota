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
    <?php
    session_start();
    include 'log.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    try {
        $dsn = "mysql:host=localhost;dbname=project_vota";
        $pdo = new PDO($dsn, 'root', '');

        // Obtener el ID del usuario actual
        $userID = $_SESSION['UserID'];

    } catch (PDOException $e) {
        echo $e->getMessage();
        escribirEnLog("[ENVIAR] " . $e->getMessage());
    }

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $emailTextarea = $_POST["emails"];
    
        // Dividir las direcciones de correo electrónico usando comas y saltos de línea como separadores
        $arrayEmails = preg_split("/[\s,]+/", $emailTextarea);
    
        // Eliminar espacios en blanco alrededor de cada dirección de correo electrónico
        $arrayEmails = array_map('trim', $arrayEmails);
    
        // Expresión regular para validar el formato de un correo electrónico
        $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    
        // Almacenar los correos electrónicos incorrectos
        $correosIncorrectos = [];
    
        // Almacenar los correos electrónicos correctos
        $correosCorrectos = [];
    
        // Verificar cada elemento del array
        foreach ($arrayEmails as $email) {
            if (preg_match($emailRegex, $email)) {
                $correosCorrectos[] = $email;
            } else {
                $correosIncorrectos[] = $email;
            }
        }

        
        
        // Enviar correos electrónicos solo si no hay correos electrónicos incorrectos
        if (empty($correosIncorrectos)) {
            foreach ($correosCorrectos as $email) {
                // Obtener el nombre de usuario asociado al correo si es necesario
                // Puedes ajustar esta lógica según tus necesidades
                if (enviarCorreo($email, "Nombre de Usuario")) {
                    echo "Correo enviado a: $email<br>";
                } else {
                    echo "Error al enviar correo a: $email<br>";
                }
            }
        } else {
            // Mostrar notificación de correos incorrectos
            if (count($correosIncorrectos) === 1) {
                echo "Este correo no es correcto: " . $correosIncorrectos[0];
            } else {
                echo "Estos correos no son correctos: " . implode(', ', $correosIncorrectos);
            }
        }
    }
    ?>

    <!-- Añadir el título -->
    <h1>Invitar a usuarios</h1>

    <div id="notificationContainer"></div>

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
            'style': 'display:none' // Inicialmente oculto
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
