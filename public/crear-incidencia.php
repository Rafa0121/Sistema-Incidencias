<?php
session_start();
include "../includes/permisos.php";
requireUsuario();
include "../includes/header.php";
include "../includes/navbar.php";
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2>Crear nueva incidencia</h2>
<form action="../controllers/crear_incidencia.php" method="post">
    <label>Título</label><br>
    <input type="text" name="titulo" required><br>

    <label>Tipo</label><br>
    <select name="tipo">
        <option value="hardware">Hardware</option>
        <option value="software">Software</option>
        <option value="red">Red</option>
        <option value="impresora">Impresora</option>
        <option value="cuentas">Cuentas</option>
        <option value="otros">Otros</option>
    </select><br>

    <label>Prioridad</label><br>
    <select name="prioridad">
        <option value="baja">Baja</option>
        <option value="media" selected>Media</option>
        <option value="alta">Alta</option>
    </select><br>

    <label>Descripción</label><br>
    <textarea name="descripcion" rows="6" required></textarea><br><br>

    <button type="submit">Crear incidencia</button>
</form>

<?php include "../includes/footer.php"; ?>