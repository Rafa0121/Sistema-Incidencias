<?php
// controllers/tickets_create.php
session_start();
include "../config/db.php";
include "notificaciones.php";
include "email.php";

if (!isset($_SESSION['usuario'])) {
    die("Debes iniciar sesión.");
}

$usuario_id = $_SESSION['usuario']['id'];
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$prioridad = $_POST['prioridad'] ?? 'media';
$tipo = trim($_POST['tipo'] ?? 'general');

if (!$titulo || !$descripcion) die("Rellena título y descripción.");

// insertar incidencia
$stmt = $conexion->prepare("INSERT INTO incidencias (usuario_id,tipo,titulo,descripcion,prioridad) VALUES (?,?,?,?,?)");
$stmt->bind_param("issss", $usuario_id, $tipo, $titulo, $descripcion, $prioridad);
if ($stmt->execute()) {
    $incidencia_id = $stmt->insert_id;

    // notificación interna
    crearNotificacion($usuario_id, "Has creado la incidencia: $titulo");

    // notificación por email al usuario (confirmación)
    enviarCorreo($_SESSION['usuario']['email'], "Incidencia creada", "<p>Tu incidencia <b>$titulo</b> ha sido registrada.</p>");

    header("Location: ../public/lista-incidencias.php");
    exit;
} else {
    echo "Error al crear la incidencia: " . $conexion->error;
}
