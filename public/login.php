<?php
session_start();
include "../includes/header.php";
include "../includes/navbar.php";
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2>Iniciar sesión</h2>
<form action="../controllers/login.php" method="post">
    <label>Email</label><br>
    <input type="email" name="email" required><br>
    <label>Contraseña</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Entrar</button>
</form>
<?php include "../includes/footer.php"; ?>