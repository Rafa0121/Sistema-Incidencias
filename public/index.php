<?php
session_start();
include "../includes/header.php";
include "../includes/navbar.php";
?>
<link rel="stylesheet" href="assets/css/style.css">
<h1>Bienvenido al Sistema de Incidencias</h1>

<?php if (!isset($_SESSION['usuario'])): ?>
    <p>Por favor, <a href="login.php">inicia sesión</a> o <a href="registro.php">regístrate</a>.</p>
<?php else: ?>
    <p>Has iniciado como <strong><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong> (<?= $_SESSION['usuario']['rol'] ?>)</p>
    <?php
    if ($_SESSION['usuario']['rol'] === 'admin') echo '<p><a href="admin-panel.php">Ir al panel de administrador</a></p>';
    if ($_SESSION['usuario']['rol'] === 'tecnico') echo '<p><a href="tecnico-panel.php">Ir al panel de técnico</a></p>';
    if ($_SESSION['usuario']['rol'] === 'usuario') echo '<p><a href="crear-incidencia.php">Crear nueva incidencia</a></p>';
    ?>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>