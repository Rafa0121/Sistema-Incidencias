<?php
// controllers/login.php
session_start();
include "../config/db.php";

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) die("Introduce email y contraseña.");

$stmt = $conexion->prepare("SELECT id,nombre,email,password,rol FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) die("Usuario no encontrado.");
$user = $res->fetch_assoc();

if (!password_verify($password, $user['password'])) die("Contraseña incorrecta.");

$_SESSION['usuario'] = [
    'id' => $user['id'],
    'nombre' => $user['nombre'],
    'email' => $user['email'],
    'rol' => $user['rol']
];

if ($user['rol'] === 'admin') header("Location: ../public/admin-panel.php");
elseif ($user['rol'] === 'tecnico') header("Location: ../public/tecnico-panel.php");
else header("Location: ../public/index.php");
exit;
