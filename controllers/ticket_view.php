<?php
// controllers/ticket_view.php
session_start();
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(["error" => "id faltante"]);
    exit;
}

$stmt = $conexion->prepare("SELECT i.*, u.nombre AS creador FROM incidencias i LEFT JOIN usuarios u ON i.usuario_id=u.id WHERE i.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$inc = $res->fetch_assoc();
if (!$inc) {
    echo json_encode(["error" => "no encontrado"]);
    exit;
}

// comentarios
$stmt2 = $conexion->prepare("SELECT c.*, u.nombre FROM comentarios c LEFT JOIN usuarios u ON c.user_id = u.id WHERE c.incidencia_id=? ORDER BY c.fecha ASC");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$comments = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

header("Content-Type: application/json");
echo json_encode(["incidencia" => $inc, "comentarios" => $comments]);
exit;
