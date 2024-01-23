<!DOCTYPE html>
<html lang="en">
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

    <div class="navbarUpLogin">
        <div class="navItem">
            <a href="./"><i class="fas fa-home"></i></a>
        </div>
    </div>
    <section class="loginSection">
        <h1>Bienvenido de nuevo!</h1>
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

    <?php include("footer.php")?>

    <?php 
    if (isset($_POST["username"])) {

        try {
            $hostname = "localhost";
            $dbname = "project_vota";
            $username = "root";
            $pw = "Thyr10N191103!--";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
        } catch (PDOException $e) {
            echo "Failed to get DB handle: ". $e->getMessage();
            exit;
        }

        $errorShown = false;
        echo "<script>";
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $country = $_POST["country"];
        $city = $_POST["city"];
        $postalCode = $_POST["postalCode"];

        if (strlen($username) == 0) {
            echo "showNotification('error', 'Inserte un nombre de usuario');\n";
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        } else if (str_contains($username,';') or str_contains($username,'--') or str_contains($username,'/*') or str_contains($username, "*/")) {
            echo "showNotification('error', 'El nombre de usuario contiene carácteres no permitidos');\n";
            if (!$errorShown) {
                echo "showStep(0);\n";
                $errorShown = true;
            }
        }

        if (strlen($password) < 8) {
            echo "showNotification('error', 'La contraseña debe tener un mínimo de 8 carácteres');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        } else if (str_contains($password,';') or str_contains($password,'--') or str_contains($password,'/*') or str_contains($password, "*/")) {
            echo "showNotification('error', 'La contraseña contiene carácteres no permitidos');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/\d/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos un carácter numérico');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[A-Z]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos una mayúscula');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[a-z]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos una minúscula');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        } else if (!preg_match('/[ `!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~]/', $password)) {
            echo "showNotification('error', 'La contraseña debe contener al menos un carácter especial');\n";
            if (!$errorShown) {
                echo "showStep(1);\n";
                $errorShown = true;
            }
        }

        if (!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)) {
            echo "showNotification('error', 'La dirección de correo electrónico no es válida');\n";
            if (!$errorShown) {
                echo "showStep(3);\n";
                $errorShown = true;
            }
        } else if (str_contains($email,';') or str_contains($email,'--') or str_contains($email,'/*') or str_contains($email, "*/")) {
            echo "showNotification('error', 'La dirección de correo electrónico contiene carácteres no permitidos');\n";
            if (!$errorShown) {
                echo "showStep(3);\n";
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Users WHERE `Email` = ?");
            $query->bindParam(1, $email);
            $query -> execute();
            $row = $query -> fetch();
            if ($row) {
                echo "showNotification('error', 'La dirección de correo electrónico ya está enlazada a una cuenta');\n";
                if (!$errorShown) {
                    echo "showStep(3);\n";
                    $errorShown = true;
                }
            }
        }

        if (!preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im", $phone)) {
            echo "showNotification('error', 'Su número de teléfono debe de tener 9 dígitos y un prefijo');\n";
            if (!$errorShown) {
                echo "showStep(4);\n";
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
                if (!$errorShown) {
                    echo "showStep(4);\n";
                    $errorShown = true;
                }
            }
        }

        if (strlen($country) == 0) {
            echo "showNotification('error', 'Inserte un país');\n";
            if (!$errorShown) {
                echo "showStep(5);\n";
                $errorShown = true;
            }
        } else if (str_contains($country,';') or str_contains($country,'--') or str_contains($country,'/*') or str_contains($country, "*/")) {
            echo "showNotification('error', 'El país insertado contiene carácteres no permitidos');\n";
            if (!$errorShown) {
                echo "showStep(5);\n";
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Countries WHERE CountryName = ?");
            $query->bindParam(1, $country);
            $query -> execute();
            $row = $query -> fetch();
            if (!$row) {
                echo "showNotification('error', 'El país insertado no existe');\n";
                if (!$errorShown) {
                    echo "showStep(5);\n";
                    $errorShown = true;
                }
            }
        }

        if (strlen($city) == 0) {
            echo "showNotification('error', 'Inserte una ciudad');\n";
            if (!$errorShown) {
                echo "showStep(6);\n";
                $errorShown = true;
            }
        }

        if (strlen($postalCode) == 0) {
            echo "showNotification('error', 'Inserte un código postal');\n"; 
            if (!$errorShown) {
                echo "showStep(7);\n";
                $errorShown = true;
            }
        }
        echo '</script>';

        if (!$errorShown) {
            $query = $pdo -> prepare("INSERT INTO Users(`Username`, `Password`, `Phone`, `Email`, `Country`, `City`, `PostalCode`) VALUES (?, SHA2(?, 512), ?, ?, ?, ?, ?)");
            $query->bindParam(1, $username);
            $query->bindParam(2, $password);
            $query->bindParam(3, $phone);
            $query->bindParam(4, $email);
            $query->bindParam(5, $country);
            $query->bindParam(6, $city);
            $query->bindParam(7, $postalCode);
            $query -> execute();
        }
    }
    ?>
</body>
</html>