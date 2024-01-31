<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>
    <script src="register.js"></script>
    <title>Registro | Vota EJA</title>
</head>
<body>
    <div id="notificationContainer"></div>

    <?php include("./components/log.php")?>
    <?php include './components/header.php'; ?>

    <section class="loginSection regSection">
        <h1>Bienvenido a Vota EJA!</h1>
        <div class="registerForm">
            <div class="circleRegisterForm">
                <div class="textReg">
                    <h3>Regístrate</h3>
                    <p>Completa todos los campos</p>
                </div>
                <form method="POST">
                    
                </form>
                <div class=""></div>
            </div>
        </div>

        <div class="loginImg">
            <img src="./img/register.svg">
        </div>
    </section>

    <?php include("./components/footer.php")?>

    <?php 
                
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include("config.php");
    try {
        $hostname = "localhost";
        $dbname = "project_vota";
        $username = $dbUser;
        $pw = $dbPass;
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: ". $e->getMessage();
        escribirEnLog("[REGISTER] ".$e);
        exit;
    }

    $query =  $pdo ->prepare("SELECT * FROM Countries");
    $query -> execute();
    $row = $query -> fetch();
    echo "<script>getCountryData({";
    while ($row) {
        echo "'".$row["CountryName"]."':'".$row["PhoneCode"]."',";
        $row = $query -> fetch();
    }
    echo "});</script>";
    

    if (isset($_POST["username"])) {
        $errorShown = false;
        echo "<script>";
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $country = $_POST["country"];
        $city = $_POST["city"];
        $postalCode = $_POST["postalCode"];
        $userIsGuest = false;
        if (strlen($username) == 0) {
            echo "showNotification('error', 'Inserte un nombre de usuario');\n";
            //log
            escribirEnLog("[REGISTER] Nombre de usuario inválido");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (str_contains($username,';') or str_contains($username,'--') or str_contains($username,'/*') or str_contains($username, "*/")) {
            echo "showNotification('error', 'El nombre de usuario contiene carácteres no permitidos');\n";
            //log
            escribirEnLog("[REGISTER] Nombre de usuario inválido (caracteres no permitidos)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        }
        if (strlen($password) < 8) {
            echo "showNotification('error', 'La contraseña debe tener un mínimo de 8 carácteres');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (menos de 8 caracteres)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (str_contains($password,';') or str_contains($password,'--') or str_contains($password,'/*') or str_contains($password, "*/")) {
            echo "showNotification('error', 'La contraseña contiene carácteres no permitidos');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (caracteres no permitidos)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/\d/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos un carácter numérico');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (sin carácter numérico)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[A-Z]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos una mayúscula');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (sin mayúscula)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[a-z]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos una minúscula');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (sin minúscula)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[ `!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos un carácter especial');\n";
            //log
            escribirEnLog("[REGISTER] Contraseña inválida (sin carácter especial)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        }

        if (!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)) {
            echo "showNotification('error', 'La dirección de correo electrónico no es válida');\n";
            //log
            escribirEnLog("[REGISTER] Email inválido");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (str_contains($email,';') or str_contains($email,'--') or str_contains($email,'/*') or str_contains($email, "*/")) {
            echo "showNotification('error', 'La dirección de correo electrónico contiene carácteres no permitidos');\n";
            //log
            escribirEnLog("[REGISTER] Email inválido (caracteres no permitidos)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Users WHERE `Email` = ?");
            $query->bindParam(1, $email);
            $query -> execute();
            $row = $query -> fetch();
            if ($row) {
                if ($row["password"] == "") {
                    $userIsGuest = true;
                } else {
                    echo "showNotification('error', 'La dirección de correo electrónico ya está enlazada a una cuenta');\n";
                    //log
                    escribirEnLog("[REGISTER] Email inválido (ya enlazado a una cuenta)");
                    if (!$errorShown) {
                        echo "showStep(0);\n";
                        $errorShown = true;
                    }
                }
            }
        }

        if (!preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im", $phone)) {
            echo "showNotification('error', 'Su número de teléfono debe de tener 9 dígitos y un prefijo');\n";
            //log
            escribirEnLog("[REGISTER] Número de teléfono inválido");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else {
            $numWithoutPrefix = substr($phone, 0 -9);
            $prefix = str_replace($numWithoutPrefix, "", $phone);
            $query = $pdo -> prepare("SELECT * FROM Countries WHERE PhoneCode = ? AND CountryName = ?;");
            $query->bindParam(1, $prefix);
            $query->bindParam(2, $country);
            $query -> execute();
            $row = $query -> fetch();
            if (!$row) {
                echo "showNotification('error', 'El prefijo del número de teléfono insertado no es válido');\n";
                //log
                escribirEnLog("[REGISTER] Prefijo de número de teléfono inválido");
                if (!$errorShown) {
                    echo "showStep(0);\n";
                    $errorShown = true;
                }
            }
        }

        if (strlen($country) == 0) {
            echo "showNotification('error', 'Inserte un país');\n";
            //log
            escribirEnLog("[REGISTER] País inválido");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (str_contains($country,';') or str_contains($country,'--') or str_contains($country,'/*') or str_contains($country, "*/")) {
            echo "showNotification('error', 'El país insertado contiene carácteres no permitidos');\n";
            //log
            escribirEnLog("[REGISTER] País inválido (caracteres no permitidos)");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Countries WHERE CountryName = ?");
            $query->bindParam(1, $country);
            $query -> execute();
            $row = $query -> fetch();
            if (!$row) {
                echo "showNotification('error', 'El país insertado no existe');\n";
                //log
                escribirEnLog("[REGISTER] País inválido (no existe)");
                if (!$errorShown) {
                    echo "showStep(0);\n";
                    $errorShown = true;
                }
            }
        }

        if (strlen($city) == 0) {
            echo "showNotification('error', 'Inserte una ciudad');\n";
            //log
            escribirEnLog("[REGISTER] Ciudad inválida");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        }

        if (strlen($postalCode) == 0) {
            echo "showNotification('error', 'Inserte un código postal');\n"; 
            //log
            escribirEnLog("[REGISTER] Código postal inválido");
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        }
        echo '</script>';

        if (!$errorShown) {

            $tokenLength = 40;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $token = '';
            for ($i = 0; $i < $tokenLength; $i++) {
                $randomIndex = rand(0, strlen($characters) - 1);
                $token .= $characters[$randomIndex];
            }

            $query = $pdo -> prepare("INSERT INTO Users(`Username`, `Password`, `Phone`, `Email`, `Country`, `City`, `PostalCode`, `ValidationToken`) VALUES (?, SHA2(?, 512), ?, ?, ?, ?, ?, ?)");
            $query->bindParam(1, $username);
            $query->bindParam(2, $password);
            $query->bindParam(3, $phone);
            $query->bindParam(4, $email);
            $query->bindParam(5, $country);
            $query->bindParam(6, $city);
            $query->bindParam(7, $postalCode);
            $query->bindParam(8, $token);
            
            $query -> execute();
            session_start();
            $query = $pdo -> prepare("SELECT * FROM Users WHERE `Email` = ?");
            $query->bindParam(1, $email);
            $query -> execute();
            $row = $query->fetch();



            require 'PHPMailer-master/src/Exception.php';
            require 'PHPMailer-master/src/PHPMailer.php';
            require 'PHPMailer-master/src/SMTP.php';

            $destinatary = $email;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $destinatario = $destinatary;
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
                $mail->Username   = "jbernabeucaballero.cf@iesesteveterradas.cat"; // Email de la cuenta de correo desde la que se enviaran los correos
                $mail->Password   = "KekHut93"; // Password de la cuenta de correo
    
                $mail->IsHTML(true);
                $mail->AddAddress($destinatario);
                $mail->SetFrom("jbernabeucaballero.cf@iesesteveterradas.cat", "Vota EJA");
    
                $mail->Subject = $title;
                $mail->MsgHTML($content);

                if ($mail->Send()) {
                    echo '<script>showNotification("success", "¡Registro completado!");</script>';
                } else {
                    echo '<script>showNotification("error", "Vaya, parece que no se ha enviado el correo. ' . $mail->ErrorInfo . '");</script>';
                    //log
                    escribirEnLog("[REGISTER] Error al enviar el correo de validación: " . $mail->ErrorInfo);
                }
            }

            if ($row) {
                header("Location:./index.php");
            }

        }
    }
    ?>
</body>
</html>