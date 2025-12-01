<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";

function notificarAsignacionTecnico($correo, $nombre, $incidencia_id)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "TU_CORREO@gmail.com";
        $mail->Password = "CONTRASEÑA_APP";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom("TU_CORREO@gmail.com", "Sistema de Incidencias");
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = "Nueva incidencia asignada";
        $mail->Body = "
            Hola <b>$nombre</b>,<br><br>
            Se te ha asignado la incidencia con ID <b>$incidencia_id</b>.<br>
            Entra al panel para verla en detalle.<br><br>
            <i>Sistema Automático</i>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error enviando correo asignación: " . $mail->ErrorInfo);
    }
}
