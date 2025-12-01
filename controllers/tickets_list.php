<?php
// controllers/tickets_list.php
session_start();
include "../config/db.php";

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}
$uid = $_SESSION['usuario']['id'];
$stmt = $conexion->prepare("SELECT * FROM incidencias WHERE usuario_id=? ORDER BY fecha_creacion DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
while ($row = $res->fetch_assoc()) $out[] = $row;
header("Content-Type: application/json");
echo json_encode($out);
exit;
