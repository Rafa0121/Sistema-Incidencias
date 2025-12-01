<?php
session_start();
include "../includes/header.php";
include "../includes/navbar.php";
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2>Registro</h2>
<form action="../controllers/registro.php" method="post">
    <label>Nombre</label><br>
    <input type="text" name="nombre" required><br>
    <label>Email</label><br>
    <input type="email" name="email" required><br>
    <label>Contraseña</label><br>
    <input type="password" name="password" required><br>
    <label>Rol</label><br>
    <select name="rol" required>
        <option value="usuario">Usuario</option>
        <option value="tecnico">Técnico</option>
        <option value="admin">Administrador</option>
    </select><br><br>
    <button type="submit">Registrarse</button>
</form>
<?php include "../includes/footer.php"; ?>