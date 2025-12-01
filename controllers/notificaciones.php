<?php
// controllers/notificaciones.php
session_start();
include "../config/db.php";

/**
 * crearNotificacion(usuario_id, mensaje)
 */
function crearNotificacion($usuario_id, $mensaje)
{
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO notificaciones (usuario_id, mensaje, leido, fecha) VALUES (?,?,0,NOW())");
    $stmt->bind_param("is", $usuario_id, $mensaje);
    $stmt->execute();
    $stmt->close();
}

/** Endpoints:
 * GET ?list=1 -> devuelve notificaciones no leidas (json)
 * POST mark_read=1 -> marca leídas
 */
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(["error" => "no auth"]);
    exit;
}
$uid = $_SESSION['usuario']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['list'])) {
    $stmt = $conexion->prepare("SELECT * FROM notificaciones WHERE usuario_id=? AND leido=0 ORDER BY fecha DESC");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while ($r = $res->fetch_assoc()) $out[] = $r;
    header("Content-Type: application/json");
    echo json_encode($out);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $stmt = $conexion->prepare("UPDATE notificaciones SET leido=1 WHERE usuario_id=?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    echo "OK";
    exit;
}
