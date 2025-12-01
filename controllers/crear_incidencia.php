<?php
// controllers/crear_incidencia.php
session_start();
include "../config/db.php";
include "../controllers/notificaciones.php";
include "../controllers/email.php";
include "../includes/permisos.php";
requireUsuario();

$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'general');
$prioridad = $_POST['prioridad'] ?? 'media';
$usuario_id = $_SESSION['usuario']['id'];

if (!$titulo || !$descripcion) {
    die("Rellena título y descripción.");
}

$stmt = $conexion->prepare("INSERT INTO incidencias (usuario_id,tipo,titulo,descripcion,prioridad) VALUES (?,?,?,?,?)");
$stmt->bind_param("issss", $usuario_id, $tipo, $titulo, $descripcion, $prioridad);
if ($stmt->execute()) {
    $incidencia_id = $stmt->insert_id;

    // Notificar a todos los técnicos (interna + email)
    $res = $conexion->query("SELECT id,nombre,email FROM usuarios WHERE rol='tecnico'");
    while ($t = $res->fetch_assoc()) {
        crearNotificacion($t['id'], "Nueva incidencia: $titulo");
        // enviar correo (opcional)
        enviarCorreo(
            $t['email'],
            "Nueva incidencia: $titulo",
            "<p>Se ha creado una nueva incidencia:</p>
             <p><strong>Título:</strong> $titulo<br>
             <strong>Tipo:</strong> $tipo<br>
             <strong>Prioridad:</strong> $prioridad</p>
             <p><a href='http://localhost/sistema-incidencias/public/tecnico-panel.php'>Ir al panel técnico</a></p>"
        );
    }

    // Notificar creador
    crearNotificacion($usuario_id, "Tu incidencia '$titulo' ha sido registrada.");
    enviarCorreo(
        $_SESSION['usuario']['email'],
        "Incidencia registrada: $titulo",
        "<p>Tu incidencia <b>$titulo</b> ha sido registrada correctamente.</p>"
    );

    header("Location: ../public/mis-incidencias.php");
    exit;
} else {
    echo "Error: " . $conexion->error;
}
