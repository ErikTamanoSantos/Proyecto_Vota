<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesion</title>
</head>
<body>
    <h1>Inicia sesion</h1>
    <form method="POST">
        <input type="email" name="userEmail" placeholder="Email">
        <input type="password" name="pwd" placeholder="Contrasenya">
        <button type="submit">Enviar</button>
    </form>

    <?php
        session_start();
        if(isset($_POST['userEmail']) && isset($_POST['pwd'])){
            try {
                $pwd = $_POST['pwd'];
                $userEmail = $_POST['userEmail'];
                $dsn = "mysql:host=localhost;dbname=project_vota";
                $pdo = new PDO($dsn, 'root', 'Bbdd1nariO');
                
                $query = $pdo->prepare("SELECT Email FROM Users WHERE password = SHA2(:pwd, 512) AND Email = :userEmail");
                $query->bindParam(':pwd', $pwd, PDO::PARAM_STR);
                $query->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
                $query->execute();
                
                $row = $query->fetch();
                $correct = false;
                while ($row) {
                    $_SESSION["login"] = "correcto";
                    header("Location:./index.php");
                }
                if (!$correct) {
                    echo "Login incorrecto";
                }

            } catch (PDOException $e){
                echo $e->getMessage();
            }
        }
    ?>

</body>
</html>