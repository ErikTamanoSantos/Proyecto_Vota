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
                // Conectar a tu base de datos (reemplaza con tus credenciales)
                $mysqli = new mysqli("localhost", "root", "", "project_vota");
    
                // Verificar la conexión
                if ($mysqli->connect_error) {
                    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
                }
    
                // Preparar la consulta para insertar correos electrónicos en la tabla email_queue
                $stmt = $mysqli->prepare("INSERT INTO email_queue (email) VALUES (?)");
    
                // Bind parameters
                $stmt->bind_param("s", $email);
    
                // Insertar correos electrónicos en la tabla
                foreach ($correosCorrectos as $email) {
                    $stmt->execute();
                }
    
                // Cerrar la conexión y liberar recursos
                $stmt->close();
                $mysqli->close();
    
                echo "Correos electrónicos almacenados con éxito en la tabla email_queue.";
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