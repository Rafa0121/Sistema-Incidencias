<?php
// config/db.php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = ""; // si usas contraseña ponla aquí
$DB_NAME = "incidencias";

$conexion = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conexion->connect_errno) {
    die("Error de conexión MySQL: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
