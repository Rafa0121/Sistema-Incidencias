<?php
// controllers/comment_add.php
session_start();
include "../config/db.php";
include "../controllers/notificaciones.php";
include "../controllers/email.php";

if (!isset($_SESSION['usuario'])) die("login requerido");
$uid = $_SESSION['usuario']['id'];
$rol = $_SESSION['usuario']['rol'];

$incidencia_id = intval($_POST['incidencia_id'] ?? 0);
$contenido = trim($_POST['contenido'] ?? '');

if (!$incidencia_id || !$contenido) die("Datos incompletos");

if (!in_array($rol, ['tecnico', 'admin'])) die("No tienes permiso para comentar.");

$stmt = $conexion->prepare("INSERT INTO comentarios (incidencia_id,user_id,contenido) VALUES (?,?,?)");
$stmt->bind_param("iis", $incidencia_id, $uid, $contenido);
if ($stmt->execute()) {
    $inc = $conexion->query("SELECT usuario_id,titulo FROM incidencias WHERE id='$incidencia_id'")->fetch_assoc();
    crearNotificacion($inc['usuario_id'], "Respuesta a tu incidencia: " . substr($contenido, 0, 80));
    $userEmail = $conexion->query("SELECT email FROM usuarios WHERE id='" . $inc['usuario_id'] . "'")->fetch_assoc()['email'];
    enviarCorreo($userEmail, "Respuesta a tu incidencia", "<p>Han respondido a tu incidencia <b>{$inc['titulo']}</b>.<br>Mensaje: $contenido</p>");
    header("Location: ../public/ver-incidencia.php?id={$incidencia_id}");
    exit;
} else {
    echo "Error: " . $conexion->error;
}
