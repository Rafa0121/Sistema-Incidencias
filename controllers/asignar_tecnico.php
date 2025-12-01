<?php
session_start();

include "../config/db.php";
include "../includes/permisos.php";

requireAdmin();

// Recibir datos del formulario
$incidencia_id = $_POST["incidencia_id"];
$tecnico_id = $_POST["tecnico_id"];

// Guardar en la base de datos
$stmt = $conexion->prepare("UPDATE incidencias SET tecnico_id = ? WHERE id = ?");
$stmt->bind_param("ii", $tecnico_id, $incidencia_id);
$stmt->execute();

// Redirigir de vuelta
header("Location: ../public/admin-panel.php");
exit;
