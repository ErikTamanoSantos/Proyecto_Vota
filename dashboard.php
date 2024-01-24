<?php
    if (!isset($_SESSION['userID'])) {
        include('./errors/error403.php');
    } else {
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>
    <title>Dashboard</title>
</head>
<body>
    <section class="dashboard">
        <div class="pageDashboard">
            <div class="userInfo">
            <?php
                try {
                    $dsn = "mysql:host=localhost;dbname=project_vota";
                    $pdo = new PDO($dsn, 'root', 'Thyr10N191103!--');
                    
                    $query = $pdo->prepare('SELECT * FROM Users WHERE ID = :UserID');
                    $query->bindParam(':UserID', $_SESSION['UserID']);
                    $query->execute();
                    
                    $row = $query->fetch();
                    $correct = false;
                    while ($row) {
                        echo "<h2>".$row["Username"]."</h2>";
                        echo "<h4>".$row["Email"]."</h4>";
                        echo "<h4>".$row["Phone"]."</h4>";
                        echo "<h4>".$row["Country"]."</h4>";
                        echo "<h4>".$row["City"]."</h4>";
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
            ?>
                
            </div>
            <div class="navDashboard">
                <div class="dashboardItem">
                    <a href="create_poll.php" id="createQuestion"><i class="fas fa-plus"></i><p>Crear encuesta</p></a>
                </div>
                <div class="dashboardItem">
                <a href="list_polls.php" id="createQuestion"><i class="fas fa-minus"></i><p>Listar encuestas</p></a>
                </div>
            </div>  
        </div>
    </section> 

    <script>
        document.getElementById("createQuestion").addEventListener("click", function() {
            var createQuestionElement = '<div><h1>Crear pregunta</h1><form action="createQuestion.php" method="POST"><input type="text" name="question" placeholder="Pregunta"><input type="text" name="answer1" placeholder="Respuesta 1"><input type="text" name="answer2" placeholder="Respuesta 2"><input type="text" name="answer3" placeholder="Respuesta 3"><input type="text" name="answer4" placeholder="Respuesta 4"><input type="text" name="answer5" placeholder="Respuesta 5"><input type="text" name="answer6" placeholder="Respuesta 6"><input type="text" name="answer7" placeholder="Respuesta 7"><input type="text" name="answer8" placeholder="Respuesta 8"><input type="text" name="answer9" placeholder="Respuesta 9"><input type="text" name="answer10" placeholder="Respuesta 10"><input type="submit" value="Crear pregunta"></form></div>';
            document.querySelector(".pageDashboard").innerHTML = createQuestionElement;
        });
    </script>
</body>
</html>
<?php
    }
?>