<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="functions.js"></script>
    <script src="register.js"></script>
    <title>Document</title>
</head>
<body>
    <?php include("header.php")?>
    <div id="notificationContainer"></div>
    <form method="POST">
        
    </form>
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
            echo 'showNotification("error", "Inserte un nombre de usuario");\n';
            if (!$errorShown) {
                echo 'showStep(0)';
                $errorShown = true;
            }
        } else if (str_contains($username,';') or str_contains($username,'--') or str_contains($username,'/*') or str_contains($username, "*/")) {
            echo 'showNotification("error", "El nombre de usuario contiene carácteres no permitidos")';
            if (!$errorShown) {
                echo 'showStep(0)';
                $errorShown = true;
            }
        }

        if (strlen($password) < 8) {
            echo 'showNotification("error", "La contraseña debe tener un mínimo de 8 carácteres")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        } else if (str_contains($password,';') or str_contains($password,'--') or str_contains($password,'/*') or str_contains($password, "*/")) {
            echo 'showNotification("error", "La contraseña contiene carácteres no permitidos")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        } else if (!preg_match('/\d/', $password)) {
            echo 'showNotification("error", "La contraseña debe contener al menos un carácter numérico")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        } else if (!preg_match('[A-Z]', $password)) {
            echo 'showNotification("error", "La contraseña debe contener al menos una mayúscula")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        } else if (!preg_match('[a-z]', $password)) {
            echo 'showNotification("error", "La contraseña debe contener al menos una minúscula")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        } else if (!preg_match('/[ `!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~]/', $password)) {
            echo 'showNotification("error", "La contraseña debe contener al menos un carácter especial")';
            if (!$errorShown) {
                echo 'showStep(1)';
                $errorShown = true;
            }
        }

        if (!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)) {
            echo 'showNotification("error", "La dirección de correo electrónico no es válida")';
            if (!$errorShown) {
                echo 'showStep(3)';
                $errorShown = true;
            }
        } else if (str_contains($email,';') or str_contains($email,'--') or str_contains($email,'/*') or str_contains($email, "*/")) {
            echo 'showNotification("error", "La dirección de correo electrónico contiene carácteres no permitidos")';
            if (!$errorShown) {
                echo 'showStep(3)';
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Users WHERE Email = ?");
            $query->bindParam(1, $email);
            $row = $query -> fetch();
            if ($row) {
                echo 'showNotification("error", "La dirección de correo electrónico ya está enlazada a una cuenta")';
                if (!$errorShown) {
                    echo 'showStep(3)';
                    $errorShown = true;
                }
            }
        }

        if (strlen($phone) < 9) {
            echo 'showNotification("error", "Su número de teléfono debe de tener 9 dígitos")';
            if (!$errorShown) {
                echo 'showStep(4)';
                $errorShown = true;
            }
        } else {
            // TODO: CHECK PHONE PREFIX
        }

        if (strlen($country) == 0) {
            echo 'showNotification("error", "Inserte un país")';
            if (!$errorShown) {
                echo 'showStep(5)';
                $errorShown = true;
            }
        } else if (str_contains($country,';') or str_contains($country,'--') or str_contains($country,'/*') or str_contains($country, "*/")) {
            echo 'showNotification("error", "El país insertado contiene carácteres no permitidos")';
            if (!$errorShown) {
                echo 'showStep(5)';
                $errorShown = true;
            }
        } else {
            $query = $pdo -> prepare("SELECT * FROM Countries WHERE CountryName = ?");
            $query->bindParam(1, $country);
            $row = $query -> fetch();
            if (!$row) {
                echo 'showNotification("error", "El país insertado no existe")';
                if (!$errorShown) {
                    echo 'showStep(5)';
                    $errorShown = true;
                }
            }
        }

        if (strlen($city) == 0) {
            echo 'showNotification("error", "Inserte una ciudad")';
            if (!$errorShown) {
                echo 'showStep(6)';
                $errorShown = true;
            }
        }

        if (strlen($postalCode) == 0) {
            echo 'showNotification("error", "Inserte un código postal")'; 
            if (!$errorShown) {
                echo 'showStep(7)';
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