<?php
// controllers/ticket_update.php
session_start();
include "../config/db.php";
include "../controllers/notificaciones.php";
include "../controllers/email.php";

if (!isset($_SESSION['usuario'])) die("login requerido");
$rol = $_SESSION['usuario']['rol'];

$action = $_POST['action'] ?? '';
$incidencia_id = intval($_POST['incidencia_id'] ?? 0);

if (!$incidencia_id) die("id faltante");

if ($action === 'asignar') {
    if ($rol !== 'admin') die("Solo admin puede asignar.");
    $tecnico_id = intval($_POST['tecnico_id'] ?? 0);
    $stmt = $conexion->prepare("UPDATE incidencias SET asignado_a=? WHERE id=?");
    $stmt->bind_param("ii", $tecnico_id, $incidencia_id);
    $stmt->execute();

    $inc = $conexion->query("SELECT titulo, usuario_id FROM incidencias WHERE id='$incidencia_id'")->fetch_assoc();
    crearNotificacion($inc['usuario_id'], "Tu incidencia '{$inc['titulo']}' ha sido asignada.");
    $t = $conexion->query("SELECT email FROM usuarios WHERE id='$tecnico_id'")->fetch_assoc();
    if ($t) enviarCorreo($t['email'], "Se te ha asignado una incidencia", "<p>Tienes asignada la incidencia <b>{$inc['titulo']}</b></p>");

    header("Location: ../public/admin-panel.php");
    exit;
}

if ($action === 'cambiar_estado') {
    if ($rol !== 'admin') die("Solo admin.");
    $estado = $_POST['estado'] ?? 'pendiente';
    $stmt = $conexion->prepare("UPDATE incidencias SET estado=? WHERE id=?");
    $stmt->bind_param("si", $estado, $incidencia_id);
    $stmt->execute();

    $inc = $conexion->query("SELECT titulo, usuario_id FROM incidencias WHERE id='$incidencia_id'")->fetch_assoc();
    crearNotificacion($inc['usuario_id'], "El estado de '{$inc['titulo']}' cambió a $estado");
    header("Location: ../public/admin-panel.php");
    exit;
}

if ($action === 'marcar_resuelto') {
    if (!in_array($rol, ['tecnico', 'admin'])) die("No tienes permiso.");
    $stmt = $conexion->prepare("UPDATE incidencias SET estado='resuelto', fecha_resolucion=NOW() WHERE id=?");
    $stmt->bind_param("i", $incidencia_id);
    $stmt->execute();

    $inc = $conexion->query("SELECT titulo, usuario_id FROM incidencias WHERE id='$incidencia_id'")->fetch_assoc();
    crearNotificacion($inc['usuario_id'], "Tu incidencia '{$inc['titulo']}' ha sido marcada como resuelta.");
    $correo = $conexion->query("SELECT email FROM usuarios WHERE id='" . $inc['usuario_id'] . "'")->fetch_assoc()['email'];
    enviarCorreo($correo, "Incidencia resuelta: {$inc['titulo']}", "<p>Tu incidencia <b>{$inc['titulo']}</b> ha sido resuelta.</p>");
    header("Location: ../public/tecnico-panel.php");
    exit;
}

echo "Acción desconocida.";
