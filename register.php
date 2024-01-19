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
        echo $_POST["username"];
    }
    ?>
</body>
</html>