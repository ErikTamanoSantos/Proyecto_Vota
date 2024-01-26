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

try {
    $dsn = "mysql:host=localhost;dbname=project_vota";
    $pdo = new PDO($dsn, 'aleix', 'Caqjuueeemke64*');

    // Obtener el ID del usuario actual
    $userID = $_SESSION['UserID'];
   
} catch (PDOException $e){
    echo $e->getMessage();
    escribirEnLog("[ENVIAR] ".$e);
}
?>

<!-- Añadir el título -->
<h1>Invitar a usuarios</h1>

<div id="notificationContainer"></div>

<script>
    $(document).ready(function() {
        // Crear el textarea dinámicamente
        var textarea = $('<textarea></textarea>', {
            'id': 'emailTextarea',
            'name': 'emails',
            'placeholder': 'Introduce correos electrónicos separados por coma o por linea',
            'rows': '4',
            'cols': '50',
            'oninput': 'checkEmails()'
        });

        // Adjuntar el textarea al body
        $('body').append(textarea);

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

        $('body').append(enviarButton);

    });

    function checkEmails() {
        // Obtener el contenido del textarea
        var emails = $('#emailTextarea').val();

        // Obtener el botón de enviar
        var enviarButton = $('#enviarButton');

        // Mostrar u ocultar el botón de enviar según si hay algo en el textarea
        if (emails.trim() !== '') {
            enviarButton.show();
        } else {
            enviarButton.hide();
        }
    }

    function almacenarEnArray() {
        // Obtener el contenido del textarea
        var inputText = $('#emailTextarea').val();

        // Dividir las líneas
        var lines = inputText.split('\n');

        // Array para almacenar todos los correos electrónicos
        var allEmails = [];

        // Recorrer cada línea
        lines.forEach(function(line) {
            // Dividir los elementos en la línea usando comas como separador
            var elements = line.split(',');

            // Eliminar espacios en blanco alrededor de cada elemento
            elements = elements.map(function(element) {
                return element.trim();
            });

            // Agregar los elementos al array de correos electrónicos
            allEmails = allEmails.concat(elements);
        });

        // Eliminar espacios en blanco alrededor de cada dirección de correo electrónico
        allEmails = allEmails.map(function(email) {
            return email.trim();
        });

        // Mostrar el array en la consola
        console.log(allEmails);

        // Enviar correos electrónicos
        enviarCorreos(allEmails);
    }

    function validarEmails() {
        // Obtener el contenido del textarea
        var emails = $('#emailTextarea').val();

        // Dividir las direcciones de correo electrónico usando saltos de línea como separador
        var arrayEmails = emails.split('\n');

        // Eliminar espacios en blanco alrededor de cada dirección de correo electrónico
        arrayEmails = arrayEmails.map(function(email) {
            return email.trim();
        });

        // Expresión regular para validar el formato de un correo electrónico
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Almacenar los correos electrónicos incorrectos
        var correosIncorrectos = [];

        // Verificar cada elemento del array
        for (var i = 0; i < arrayEmails.length; i++) {
            if (!emailRegex.test(arrayEmails[i])) {
                correosIncorrectos.push(arrayEmails[i]);
            }
        }

        // Mostrar notificación según la cantidad de correos electrónicos incorrectos
        if (correosIncorrectos.length === 0) {
            enviarCorreo(arrayEmails);
            
        } else if (correosIncorrectos.length === 1) {
            showNotification('error', 'Este correo no es correcto: ' + correosIncorrectos[0]);
        } else if (correosIncorrectos.length > 1) {
            showNotification('error', 'Estos correos no son correctos: ' + correosIncorrectos.join(', '));
        }
    }

       
        <?php
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            // Incluir las librerías de PHPMailer
            require 'PHPMailer-master/src/Exception.php';
            require 'PHPMailer-master/src/PHPMailer.php';
            require 'PHPMailer-master/src/SMTP.php';

            function enviarCorreo($destinatario, $titulo, $contenido) {
                
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Mailer = "smtp";

                $mail->SMTPDebug  = 2;
                $mail->SMTPAuth   = TRUE;
                $mail->SMTPSecure = "tls";
                $mail->Port       = 587;
                $mail->Host       = "smtp.gmail.com";
                $mail->Username   = "navioaleix@gmail.com";
                $mail->Password   = "Caqjuueeemke64*";

                $mail->IsHTML(true);
                $mail->AddAddress($destinatario);
                $mail->SetFrom("navioaleix@gmail.com", "Vota EJA");

                $mail->Subject = $titulo;
                $mail->MsgHTML($contenido);

                if ($mail->Send()) {
                    echo '<script>showNotification("success", "¡Correo enviado!");</script>';
                    console.log("parece que si se ha enviado el correo.");
                } else {
                    console.log("parece que no se ha enviado el correo.");
                    echo '<script>showNotification("error", "Vaya, parece que no se ha enviado el correo. ' . $mail->ErrorInfo . '");</script>';
                }
            }

            
            // Dentro de tu código de manejo de POST...
            $destinatary = $email;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $destinatario = $destinatary;
                $title = "Bienvenido, " . $username . "!";
                $content = "Bienvenido, " . $username . ". Valida tu cuenta accediendo a este enlace.";

                // Llamada a la función para enviar el correo
                enviarCorreo($destinatario, $title, $content);
            }
        ?>

</script>

</body>
</html>
