<?php
// controllers/responder_incidencia.php
session_start();
include "../config/db.php";
include "../controllers/notificaciones.php";
include "../controllers/email.php";

if (!isset($_SESSION['usuario'])) die("login requerido");
$rol = $_SESSION['usuario']['rol'];
if (!in_array($rol, ['tecnico', 'admin'])) die("No tienes permiso.");

$incidencia_id = intval($_POST['incidencia_id'] ?? 0);
$respuesta = trim($_POST['respuesta'] ?? '');
$marcar_resuelta = isset($_POST['resuelta']);

if (!$incidencia_id || !$respuesta) die("Datos incompletos");

// Insertar comentario/respuesta
$stmt = $conexion->prepare("INSERT INTO respuestas (incidencia_id, tecnico_id, respuesta) VALUES (?,?,?)");
$stmt->bind_param("iis", $incidencia_id, $_SESSION['usuario']['id'], $respuesta);
if ($stmt->execute()) {

    // Obtener info de la incidencia y usuario creador
    $inc = $conexion->query("SELECT titulo, usuario_id FROM incidencias WHERE id='$incidencia_id'")->fetch_assoc();
    $usuario_id = $inc['usuario_id'];
    $titulo = $inc['titulo'];
    $correo = $conexion->query("SELECT email FROM usuarios WHERE id='$usuario_id'")->fetch_assoc()['email'];

    // Crear notificacion interna y enviar correo al usuario
    crearNotificacion($usuario_id, "Tu incidencia '$titulo' tiene una respuesta.");
    enviarCorreo(
        $correo,
        "Respuesta a tu incidencia: $titulo",
        "<p>Tu incidencia <b>$titulo</b> ha recibido una respuesta:</p>
         <blockquote>$respuesta</blockquote>
         <p><a href='http://localhost/sistema-incidencias/public/ver-incidencia.php?id=$incidencia_id'>Ver incidencia</a></p>"
    );

    // si marcar resuelta
    if ($marcar_resuelta) {
        $u = $conexion->prepare("UPDATE incidencias SET estado='resuelto', fecha_resolucion=NOW() WHERE id=?");
        $u->bind_param("i", $incidencia_id);
        $u->execute();
        crearNotificacion($usuario_id, "Tu incidencia '$titulo' ha sido marcada como resuelta.");
        enviarCorreo($correo, "Incidencia resuelta: $titulo", "<p>Tu incidencia <b>$titulo</b> ha sido marcada como resuelta.</p>");
    }

    header("Location: ../public/tecnico-panel.php");
    exit;
} else {
    echo "Error: " . $conexion->error;
}
