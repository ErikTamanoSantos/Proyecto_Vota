<?php
    session_start();
    /*if (!isset($_SESSION['UserID'])) {
        include('./errors/error403.php');
    } else {*/

?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>
    <title>Dashboard | Vota EJA</title>
</head>
<body>
    <?php include './components/header.php'; ?>
    
    <div id="notificationContainer"></div>
    <section class="dashboard">
        <div class="pageDashboard">
            <div class="userInfo">
            <?php
                include('config.php');
                try {
                    $dsn = "mysql:host=localhost;dbname=project_vota";
                    $pdo = new PDO($dsn, 'user777', '');
                    
                    $query = $pdo->prepare('SELECT * FROM Users WHERE ID = :UserID');
                    $query->bindParam(':UserID', $_SESSION['UserID']);
                    $query->execute();
                    
                    $row = $query->fetch();
                    $correct = false;
                    while ($row) {
                        echo "<h2> Nombre: ".$row["Username"]."</h2>";
                        echo "<h4> Email: ".$row["Email"]."</h4>";
                        echo "<h4> Telefono: ".$row["Phone"]."</h4>";
                        echo "<h4> Pais: ".$row["Country"]."</h4>";
                        echo "<h4> Ciudad: ".$row["City"]."</h4>";
                        //echo "<h4>".$row["Username"]."</h4>"; futuro es validated

                        $row = $query->fetch();
                        $correct = true;
                    }
                    echo "</ul>";
                    if (!$correct) {
                        echo "<script>showNotification('info', 'Vaya, parece que no deberias estar aqui')</script>";
                    }
                } catch (PDOException $e){
                    echo $e->getMessage();
                    echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
                }

                if (!isset($_SESSION["IsAuthenticated"])) {
                    ?>  
                    <div class="authValidation">
                        <div class="authCheck">
                            <div class="returnHome">
                                <a href="index.php"><i class="fas fa-home"></i></a>
                            </div>
                            <h2>Debes acceder los términos de uso para poder acceder a esta página</h2>
                            <form method="POST">
                                <input type="checkbox" name="authCheck" id="authCheck" required >
                                <label for="scales">Aceptar términos de uso</label>
                                <input type="submit" value="Aceptar">
                            </form>
                        </div>
                    </div>
                    <?php
                }
            ?>
            </div>
            <div class="navDashboard">
                <div class="dashboardItem">
                    <a href="create_poll.php" id="createQuestion" class="<?php if (!isset($_SESSION["IsAuthenticated"])) { echo 'disabledA'; } ?>">
                            <i class="fa-solid fa-plus"></i><p>Crear encuesta </p>
                    </a>
                </div>
                <div class="dashboardItem">
                    <a href="list_polls.php" id="createQuestion" class="<?php if (!isset($_SESSION["IsAuthenticated"])) { echo 'disabledA'; } ?>">
                        <i class="fa-solid fa-list-ul"></i><p>Listar encuestas</p>
                    </a>
                </div>
            </div>
        </div>
    </section> 
    <?php include './components/footer.php'; ?>
</body>
</html>
<?php
    echo "<script>showNotification('success', 'Login Correcto');</script>";/*}*/
?>