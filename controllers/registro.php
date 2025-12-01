<?php
// controllers/registro.php
include "../config/db.php";

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$rol = $_POST['rol'] ?? 'usuario';

if (!$nombre || !$email || !$password) {
    die("Campos incompletos.");
}

$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    die("El correo ya está registrado.");
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conexion->prepare("INSERT INTO usuarios (nombre,email,password,rol) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
if ($stmt->execute()) {
    header("Location: ../public/login.php");
    exit;
} else {
    echo "Error: " . $conexion->error;
}
