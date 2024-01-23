<!DOCTYPE html>
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

    <?php
        /* if (isset($_SESSION['login'])) {
            echo "<h1>Bienvenido ".$_SESSION['username']."</h1>";
        } else {
            header("Location: ./error403.php");
        } */
    ?>
    <div id="notificationContainer"></div>
    <?php include 'header.php'; ?>

    <section class="dashboard">
        <div class="navDashboard">
            <div class="dashboardItem">
                <button id="createQuestion"><i class="fas fa-plus"></i></button>
            </div>
            <div class="dashboardItem">
                <a href="createQuestion.php"><i class="fas fa-minus"></i></a>
            </div>
        </div>
        <div class="pageDashboard">
            
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