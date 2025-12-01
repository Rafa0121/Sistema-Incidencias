<?php
// includes/navbar.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>


<nav class="navbar">

    <a href="index.php">Inicio</a>
    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="login.php">Iniciar sesión</a>
        <a href="registro.php">Registrarse</a>
    <?php else: ?>
        <?php if ($_SESSION['usuario']['rol'] === 'usuario'): ?>
            <a href="crear-incidencia.php">Crear incidencia</a>
            <a href="mis-incidencias.php">Mis incidencias</a>
        <?php endif; ?>

        <?php if ($_SESSION['usuario']['rol'] === 'tecnico'): ?>
            <a href="tecnico-panel.php">Panel técnico</a>
        <?php endif; ?>

        <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
            <a href="admin-panel.php">Panel admin</a>
        <?php endif; ?>

        <a href="logout.php">Cerrar sesión (<?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>)</a>
        <a href="#" id="noti-link">Buzón (<span id="noti-count">0</span>)</a>
    <?php endif; ?>
</nav>