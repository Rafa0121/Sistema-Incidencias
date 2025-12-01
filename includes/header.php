<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<link rel="stylesheet" href="styles.css">

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Sistema de Incidencias</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body data-userid="<?= isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : '' ?>">