<?php
    session_start();
    if (isset($_SESSION['UserID']) || isset($_SESSION['tokenQuestion']) || isset($_GET['tokenQuestion'])) {
        if (isset($_GET["tokenQuestion"])) {
            
            $tokenQuestion = $_GET["tokenQuestion"];

            include("config.php"); # codigo repetido ... sobra
            try {
                $dsn = "mysql:host=localhost;dbname=project_vota";
                $pdo = new PDO($dsn, $dbUser, $dbPass);
                
                $query = $pdo->prepare("SELECT * FROM Polls");
                $query->execute();
                
                $row = $query->fetch();
                $correct = false;
                $questions = 0;
                echo "<ul>";
                while ($row) {
                    $questions ++;
                    echo "<li><div class='pollItem'>";
                    $creationDate = new DateTime($row["CreationDate"]);
                    echo "<span class='datePollItem'>".$creationDate->format("d/m/Y")."</span>";
                    echo "<span class='nameQuestionPollItem'>".$row["Question"]."</span>";
                    if ($row["QuestionVisibility"] == "hidden") {
                        echo "<span class='visibilityPollItem'>Oculto</span>";
                    }
                    echo "</div></li>";
                    $row = $query->fetch();
                    $correct = true;
                }
                echo "</ul>";
                if (!$correct) {
                    echo "<script>showNotification('info', 'Vaya, parece que no tienes encuestas')</script>";
                }
            } catch (PDOException $e){
                echo $e->getMessage();
                escribirEnLog("[votePoll] ".$e);
                echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
            }

            $validationQuery = $pdo->prepare('SELECT * FROM poll_invitedusers WHERE tokenQuestion = :Token');

            $validationQuery->bindParam(':Token', $tokenQuestion);
            $validationQuery->execute();
            $userRow = $validationQuery->fetch();

            if ($userRow) {
                $_SESSION["tokenQuestion"] = $userRow["tokenQuestion"];
                header("Location:./votePoll.php"); 
            } else {
                echo "<script>showNotification('error', 'Token de validación inválido');</script>";
            }
        } else {
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
    <title>Votar  | Vota EJA</title>
</head>
<body>
    <div id="notificationContainer"></div>
    <?php include './components/header.php'; ?>

    <div class="questionDiv">
        <div class="question">

    <?php
        include("config.php");
        include 'log.php';
        
        try {
            $dsn = "mysql:host=localhost;dbname=test2";
            $pdo = new PDO($dsn, $dbUser, $dbPass);
            
            $query = $pdo->prepare("SELECT UserID, AnswerID FROM user_vote WHERE UserID = :UserID");

            $query = $pdo->prepare("SELECT * FROM poll_invitedusers piu JOIN polls p ON piu.PollID = p.ID WHERE tokenQuestion = :Token");
            $query->bindParam(':Token', $_SESSION['tokenQuestion']);
            $query->execute();
            $row = $query->fetch();
            $question = $row['Question'];
            echo "<h1>".$question."</h1>";
            echo '<div class="answers">';
            $query = $pdo->prepare("SELECT ans.ID, ans.Text, ans.PollID, ans.ImagePath, piu.UserID FROM answers AS ans JOIN poll_invitedusers AS piu ON ans.PollID = piu.PollID JOIN polls AS p ON piu.PollID = p.ID where piu.tokenQuestion = :Token");
            $query->bindParam(':Token', $_SESSION['tokenQuestion']);
            $query->execute();
            $row = $query->fetch();
            $userID = $row['UserID'];
            $pollID = $row['PollID'];

            $query2 = $pdo->prepare("SELECT * FROM user_vote WHERE UserID = :UserID and PollID = :PollID");
            $query2->bindParam(':UserID', $userID);
            $query2->bindParam(':PollID', $pollID);
            $query2->execute();
            $row2 = $query2->fetch();
            if ($row2) {
                header("Location:./index.php");
                $_SESSION['alreadyVoted'] = true;
            } else {
                $correct = false;
                $answers = 0;
                if ($row) {
                    echo '<form action="./votePoll.php" method="POST">';
                    while ($row) {
                        $answers ++;
                        echo "<div class='answer'>";
        

                        echo "<input type='radio' name='answer' id='answer".$answers."' value='".$row["ID"]."' required>";
                        echo "<label for='answer".$answers."'>".$row["Text"]."</label>";
                        if ($row["ImagePath"] != "") {
                            echo "<img src='".$row["ImagePath"]."' alt='Imagen de la respuesta'>";
                        }
                        echo "</div>";
                        $row = $query->fetch();
                        $correct = true;
                    }
                    echo '<input type="submit" value="Tramitar voto">';
                    echo '</form>';
                }
                
                echo "</div>";
                if (!$correct) {
                    echo "<script>showNotification('info', 'Vaya, parece que no tienes respuestas')</script>";
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['answer'])) {
                        $selectedAnswerId = $_POST['answer'];

                        try {
                            $updateQuery = $pdo->prepare("INSERT INTO `user_vote`(`UserID`, `AnswerID`, `PollID`) VALUES (?,?, ?)");
                            $updateQuery->bindParam(1, $userID);
                            $updateQuery->bindParam(2, $selectedAnswerId);
                            $updateQuery->bindParam(3, $pollID);
                            $updateQuery->execute();

                            $_SESSION['justVoted'] = true;
                            unset($_SESSION['tokenQuestion']);
                            header("Location:./index.php");
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                            escribirEnLog("[votePoll] ".$e);
                            echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
                        }
                    } else {
                        echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
                    }
                }
            }
            

        } catch (PDOException $e){
            echo $e->getMessage();
            escribirEnLog("[votePoll] ".$e);
            echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
        }
    ?>
        </div>
    </div>

    <?php include './components/footer.php'; ?>
</body>
</html>
<?php
    }} else {
        include('./errors/error403.php');
    }
?>