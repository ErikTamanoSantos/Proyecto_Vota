<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Encuestas</title>
 
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>
</head>
<body>
    <h1>Listado de tus encuestas creadas</h1>
    <div id="notificationContainer"></div>
    <?php
        try {
            $dsn = "mysql:host=localhost;dbname=project_vota";
            $pdo = new PDO($dsn, 'root', 'Thyr10N191103!--');
            
            $query = $pdo->prepare("SELECT * FROM Polls");
            $query->execute();
            
            $row = $query->fetch();
            $correct = false;
            $questions = 0;
            echo "<ul>";
            while ($row) {
                $questions ++;
                echo "<li><div>";
                $creationDate = new DateTime($row["CreationDate"]);
                echo "<span>".$creationDate->format("d/m/Y")."</span>";
                echo "<span>".$row["Question"]."</span>";
                if ($row["QuestionVisibility"] == "hidden") {
                    echo "<span>Oculto</span>";
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
            echo "<script>showNotification('error', 'Vaya, parece que algo ha salido mal')</script>";
        }
    ?>
    
</body>
</html>