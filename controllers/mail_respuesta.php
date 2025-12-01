<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";
include "../config/db.php";
include "notificaciones.php";

function notificarRespuestaIncidencia($usuario_id, $correo_usuario, $titulo_incidencia, $respuesta)
{

    // Crear notificación interna
    crearNotificacion($usuario_id, "Un técnico ha respondido a tu incidencia: $titulo_incidencia");

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "TU_CORREO@gmail.com";
        $mail->Password = "TU_PASSWORD_APLICACION";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom("TU_CORREO@gmail.com", "Sistema de Incidencias");
        $mail->addAddress($correo_usuario);

        $mail->isHTML(true);
        $mail->Subject = "Tu incidencia ha sido respondida";
        $mail->Body = "
            <h2>Respuesta a tu incidencia</h2>
            <p><strong>Incidencia:</strong> $titulo_incidencia</p>
            <p><strong>Respuesta del técnico:</strong></p>
            <blockquote>$respuesta</blockquote>
            <p><a href='http://localhost/incidencias/public/index.php'>Ver incidencia</a></p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("ERROR EMAIL: " . $mail->ErrorInfo);
    }
}
