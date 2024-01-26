<?php
    session_start();
    if (!isset($_SESSION['UserID'])) {
        include('./errors/error403.php');
    } else {
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="functions.js"></script>
    <script src="newPoll.js"></script>
    <title>Nueva Encuesta | Vota EJA</title>
</head>
<body>
    <?php include("./components/header.php")?>
    <div id="notificationContainer"></div>
    <div class="createPollDiv">
        <div class="createPollForm">
            <h2>Crea una nueva encuesta</h2>
            <form method="POST">
                <input type="text" id="question" name="question" placeholder="Pregunta">
                <div id="answerContainer">
                    <input type="text" placeholder="Respuesta 1" name="answers[]">
                    <input type="text" placeholder="Respuesta 2" name="answers[]">
                </div>
                <div class="buttonsForm">
                    <button type="button" id="removeAnswer" disabled><i class="fa-solid fa-minus"></i></button>
                    <button type="button" id="addAnswer"><i class="fa-solid fa-plus"></i></button>
                </div>
                <label for="dateStart">Inicio Encuesta:</label>
                <input type="datetime-local" id="dateStart" name="dateStart">
                <label for="dateFinish">Final Encuesta:</label>
                <input type="datetime-local" id="dateFinish" name="dateFinish">
                <input type="submit" value="Validar encuesta">
            </form>
        </div>
        <div class="createPollImg">
            <img src="./img/createPoll.svg">
        </div>
    </div>

    <?php 
    include './components/footer.php';
    include 'log.php'; 
    ?>

    <?php 
        if (isset($_POST["question"])) {
            if (!isset($_POST["answers"][0]) || !isset($_POST["answers"][1]) || $_POST["answers"][0] == "" || $_POST["answers"][1] == "") {
                echo "
                <script>
                    showNotification('error', 'El cuestionario debe contener al menos 2 respuestas');
                </script>";
            } elseif (!isset($_POST["dateStart"]) || !isset($_POST["dateFinish"]) || !(bool)strtotime($_POST["dateStart"]) || !(bool)strtotime($_POST["dateFinish"])) {
                echo "
                <script>
                    showNotification('error', 'Las fechas insertadas no son válidas');
                </script>";
            } else {
                try {
                    $hostname = "localhost";
                    $dbname = "project_vota";
                    $username = "aleix";
                    $pw = "Caqjuueeemke64*";
                    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
                } catch (PDOException $e) {
                    escribirEnLog("[NEWPOLL] ".$e);
                    echo "Failed to get DB handle: ". $e->getMessage();
                    exit;
                }
                $date = new DateTime();
                $state = "not_begun";
                $visibility = "hidden";
                $query = $pdo -> prepare("INSERT INTO Polls(Question, CreationDate, StartDate, EndDate, `State`, CreatorID, QuestionVisibility) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $query->bindParam(1, $_POST["question"]);
                $query->bindParam(2, $date->format('Y-m-d H:i:s'));
                $query->bindParam(3, $_POST["dateStart"]);
                $query->bindParam(4, $_POST["dateFinish"]);
                $query->bindParam(5, $state);
                $query->bindParam(6, $_SESSION["UserID"]);
                $query->bindParam(7, $visibility);
                $query->execute();
                foreach ($_POST['answers'] as $key => $value) {
                    $query = $pdo -> prepare("SELECT * FROM Polls ORDER BY ID DESC");
                    $query->execute();
                    $pollID = "";
                    $row = $query -> fetch();
                    if ($row) {
                        $pollID = $row["ID"];
                    }
                    $query->bindParam(1, $_POST["question"]);
                    $query->bindParam(2, $date->format('Y-m-d H:i:s'));
                    $query->bindParam(3, $_POST["dateStart"]);
                    $query->bindParam(4, $_POST["dateFinish"]);
                    $query = $pdo -> prepare("INSERT INTO Answers(`Text`, PollID) VALUES (?, ?)");
                    $query->bindParam(1, $value);
                    $query->bindParam(2, $pollID);
                    $query->execute();
                }
                header("Location:./dashboard.php");

            }    
        }
    ?>
</body>
</html>
<?php
    }
?>