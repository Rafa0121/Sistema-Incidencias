<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";
include "../config/db.php";
include "notificaciones.php";

function notificarNuevaIncidencia($incidencia_id, $titulo, $descripcion, $tipo, $usuario_creador)
{
    global $conexion;

    // Obtener todos los técnicos
    $tecnicos = $conexion->query("SELECT * FROM usuarios WHERE rol='tecnico'");

    while ($tec = $tecnicos->fetch_assoc()) {

        // Crear notificación interna
        crearNotificacion($tec["id"], "Nueva incidencia: $titulo");

        // Enviar correo
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
            $mail->addAddress($tec["email"]);

            $mail->isHTML(true);
            $mail->Subject = "Nueva incidencia reportada";
            $mail->Body = "
                <h2>Nueva incidencia creada</h2>
                <p><strong>Título:</strong> $titulo</p>
                <p><strong>Descripción:</strong> $descripcion</p>
                <p><strong>Tipo:</strong> $tipo</p>
                <p><strong>Creada por:</strong> $usuario_creador</p>
                <p><a href='http://localhost/incidencias/public/tecnico-panel.php'>Ver incidencias</a></p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("ERROR EMAIL: " . $mail->ErrorInfo);
        }
    }
}
