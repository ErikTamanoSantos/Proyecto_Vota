<?php session_start(); ?>

 <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
    </head>

    <body>

        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'PHPMailer-master/src/Exception.php';
        require 'PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/src/SMTP.php';

        $destinatary = "contacto.javierbc@gmail.com";
        $title = "Bienvenido, Javier!";
        $content = "Bienvenido, user Valida tu cuenta accediendo a este enlace.";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $destinatario = "contacto.javierbc@gmail.com";
            $title = "Bienvenido, Javier!";
            $content = "Bienvenido, user Valida tu cuenta accediendo a este enlace.";
    
            $mail = new PHPMailer();
    
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPDebug = 1;
            $mail->SMTPAuth = TRUE;
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->Host = "smtp.gmail.com";
            $mail->Username = "jbernabeucaballero.cf@iesesteveterradas.cat";
            $mail->Password = "KekHut93";
    
            $mail->IsHTML(true);
            $mail->AddAddress($destinatario);
            $mail->SetFrom("jbernabeucaballero.cf@iesesteveterradas.cat", "Vota EJA");
    
            $mail->Subject = $title;
            $mail->MsgHTML($content);
    
            if (!$mail->Send()) {
                echo $mail->ErrorInfo;
            } else {
                echo 'ok';
            }
        }
        ?>

    </body>

    </html>
