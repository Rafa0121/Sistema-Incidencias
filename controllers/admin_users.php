<?php
// controllers/admin_users.php
session_start();
include "../config/db.php";
include "../controllers/notificaciones.php";
include "../controllers/email.php";
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') die("Acceso denegado.");

$action = $_POST['action'] ?? '';

if ($action === 'add_user') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre,email,password,rol) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
    $stmt->execute();
    header("Location: ../public/admin-panel.php");
    exit;
}

if ($action === 'del_user') {
    $uid = intval($_POST['user_id'] ?? 0);
    if ($uid) {
        $conexion->query("DELETE FROM usuarios WHERE id='$uid'");
        header("Location: ../public/admin-panel.php");
        exit;
    }
}

if ($action === 'change_role') {
    $uid = intval($_POST['user_id'] ?? 0);
    $rol = $_POST['rol'] ?? 'usuario';
    $stmt = $conexion->prepare("UPDATE usuarios SET rol=? WHERE id=?");
    $stmt->bind_param("si", $rol, $uid);
    $stmt->execute();
    header("Location: ../public/admin-panel.php");
    exit;
}

echo "Acción no reconocida.";
