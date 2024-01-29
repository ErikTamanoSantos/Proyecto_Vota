<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/vota-si.png" />
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.js"></script>
    <script src="functions.js"></script>
    <script src="pollDetails.js"></script>
    <title>Detalles de Encuesta | EJA</title>
</head>
<body>
    <?php include("./components/header.php") ?>
    <section class="pollDetails">
    <?php 
        include("config.php");
        try {
            $dsn = "mysql:host=localhost;dbname=project_vota";
            $pdo = new PDO($dsn, $dbUser, $dbPass);
            
            $query = $pdo->prepare("SELECT * FROM Polls WHERE ID = ?");
            $query->bindParam(1, $_GET["id"]);
            $query->execute();
            
            $row = $query->fetch();
            $correct = false;
            $questions = 0;
            if ($row) {
                echo "<div class='pollTextDetails'>";
                echo "<h2>Pregunta: ".$row["Question"]."</h2>";
                $creationDate = new DateTime($row["CreationDate"]);
                echo "<h4>Fecha de creación: ".$creationDate->format("d/m/Y h:i")."</h4>";
                $startDate = new DateTime($row["StartDate"]);
                echo "<h4>Fecha de inicio: ".$startDate->format("d/m/Y h:i")."</h4>";
                $endDate = new DateTime($row["EndDate"]);
                echo "<h4>Fecha de fin: ".$endDate->format("d/m/Y h:i")."</h4>";
                $state = "";
                switch ($row["State"]) {
                    case "not_begun":
                        $state = "No iniciada";
                        break;
                }
                echo "<h4>Estado: ".$state."</h4>";
                echo "<h4>Visibilidad:";
                echo "<select>";
                echo "<option>Oculto</option>";
                echo "<option>Visible</option>";
                echo "</div>";
                echo "
                <div id='pollGraphs'>
                    <div id='pollGraphButtons'>
                        <button id='barChartButton' disabled><h2>Barras</h2></button>
                        <button id='pieChartButton'><h2>Pastel</h2></button>
                    </div>
                    <div id='graphContainer'>
                        <canvas id='graph'>
                        </canvas>
                    </div>
                </div>
                ";
            }

            $query = $pdo->prepare("SELECT a.ID, a.Text, count(uv.AnswerID) as counter FROM Answers a INNER JOIN Polls p on a.PollID = p.ID LEFT JOIN User_Vote uv ON uv.AnswerID = a.ID WHERE p.ID = ? GROUP BY a.ID;");
            $query->bindParam(1, $_GET["id"]);

            $query -> execute();
            $row = $query->fetch();
            echo "<script>\n";
            echo "getVotes([";
            
            while ($row) {
                echo "{answer:'".$row["Text"]."', count:".$row["counter"]."},";
                $row = $query->fetch();
            }
            echo "]);";
            echo "</script>";
        } catch (PDOException $e){
            echo $e->getMessage();
            echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
        }
    ?>
    </section>
    <?php include("./components/footer.php") ?>
</body>
</html>