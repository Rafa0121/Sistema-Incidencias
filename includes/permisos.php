<?php
// includes/permisos.php
if (session_status() == PHP_SESSION_NONE) session_start();

function requireLogin()
{
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }
}
function requireAdmin()
{
    requireLogin();
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        die("Acceso denegado: administradores únicamente.");
    }
}
function requireTecnico()
{
    requireLogin();
    if ($_SESSION['usuario']['rol'] !== 'tecnico') {
        die("Acceso denegado: técnicos únicamente.");
    }
}
function requireUsuario()
{
    requireLogin();
    if ($_SESSION['usuario']['rol'] !== 'usuario') {
        die("Acceso denegado: usuarios únicamente.");
    }
}
