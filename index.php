<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/8946387bf5.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="functions.js"></script>

    <title>Vota EJA</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="notificationContainer"></div>
    <section class="firstSec flex flex-col justify-center">
        <h1>VOTA EJA</h1>
        <div class="firstSecFlex flex flex-row ">
            <div class="firstSecText">
                <p><strong>Vota EJA</strong> es un proyecto para lograr hacer un portal de votaciones que permita <strong>crear, distribuir y administrar</strong> votaciones en linea.
                Para nosotros <strong>la seguridad es lo primero</strong>, por eso todas las votaciones pasan por un <strong>sistema de seguridad y encriptacion</strong> riguroso 
                con tal de lograr que ninguno de los datos proporcionados por los votantes se pueda filtrar o llegar a saber su identidad. </p>        
                </div>
            <div class="firstSecImg">
                <img src="./img/votacion.png" alt="Imagen de la primera seccion" width="100%" height="100%" class="imgFirstSec">
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>