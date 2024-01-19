<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inici</title>
    <style>
        .notification {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 50px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            cursor: pointer;
        }

        .success {
            background-color: #4CAF50;
            color: #fff;
        }

        .error {
            background-color: #f44336;
            color: #fff;
        }

        .close-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="notificationContainer"></div>
    <h1>VOTA EJA</h1>
    <?php include 'footer.php'; ?>
    <script src="functions.js"></script>
</body>
</html>