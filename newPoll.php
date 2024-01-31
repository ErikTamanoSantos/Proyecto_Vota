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
    <?php include("./components/log.php")?>
    <?php include("./components/header.php")?>
    <div id="notificationContainer"></div>
    <div class="createPollDiv">
        <div class="createPollForm">
            <h2>Crea una nueva encuesta</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="nameQuestionh1">
                    <input type="text" id="question" name="question" placeholder="Pregunta">
                    <div id="questionImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                    <input type="file" id="questionImage" name="questionImage" accept="image/png, image/gif, image/jpeg" >
                </div>

                <div id="answerContainer">
                    <div>
                        <input type="text" placeholder="Respuesta 1" name="answers[]">
                        <div id="answer1ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                        <input type="file" id="answer1Image" name="answerImage1" accept="image/png, image/gif, image/jpeg" >
                    </div>
                    <div>
                        <input type="text" placeholder="Respuesta 2" name="answers[]">
                        <div id="answer2ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                        <input type="file" id="answer2Image" name="answerImage2" accept="image/png, image/gif, image/jpeg" >
                    </div>
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

    <?php include("./components/footer.php")?>
    <?php 
        echo var_dump($_FILES);
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
            } elseif (new DateTime($_POST["dateStart"]) > new DateTime($_POST["dateFinish"])) {
                echo "
                <script>
                    showNotification('error', 'La fecha de inicio no debe ser mayor que la fecha de fin');
                </script>";
            } else {
                include("config.php");
                try {
                    $hostname = "localhost";
                    $dbname = "project_vota";
                    $username = $dbUser;
                    $pw = $dbPass;
                    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
                } catch (PDOException $e) {
                    echo "Failed to get DB handle: ". $e->getMessage();
                    escribirEnLog("[newPoll] ".$e);
                    exit;
                }
                $date = new DateTime();
                $state = "not_begun";
                $visibility = "hidden";
                $questionImage = null;
                if (isset($_FILES["questionImage"]) && $_FILES["questionImage"]["name"] != "") {
                    $questionImage = "./img/formImages/".basename($_FILES["questionImage"]["name"]);   
                    move_uploaded_file($_FILES['questionImage']['tmp_name'], $questionImage);
                }
                $query = $pdo -> prepare("INSERT INTO Polls(Question, CreationDate, StartDate, EndDate, `State`, CreatorID, QuestionVisibility, ImagePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $query->bindParam(1, $_POST["question"]);
                $query->bindParam(2, $date->format('Y-m-d H:i:s'));
                $query->bindParam(3, $_POST["dateStart"]);
                $query->bindParam(4, $_POST["dateFinish"]);
                $query->bindParam(5, $state);
                $query->bindParam(6, $_SESSION["UserID"]);
                $query->bindParam(7, $visibility);
                $query->bindParam(8, $questionImage);
                $query->execute();
                $index = 1;
                foreach ($_POST['answers'] as $key => $value) {
                    $query = $pdo -> prepare("SELECT * FROM Polls ORDER BY ID DESC");
                    $query->execute();
                    $pollID = "";
                    $row = $query -> fetch();
                    if ($row) {
                        $pollID = $row["ID"];
                    }
                    $answerImage = null;
                    if (isset($_FILES["answerImage".$index]) && $_FILES["answerImage".$index]["name"] != "") {
                        $answerImage = "./img/formImages/".basename($_FILES["answerImage".$index]["name"]);   
                        move_uploaded_file($_FILES['questionImage']['tmp_name'], $answerImage);
                    }
                    $query = $pdo -> prepare("INSERT INTO Answers(`Text`, PollID, ImagePath) VALUES (?, ?, ?)");
                    $query->bindParam(1, $value);
                    $query->bindParam(2, $pollID);
                    $query->bindParam(3, $answerImage);
                    $query->execute();
                    $index++;
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