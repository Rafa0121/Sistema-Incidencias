<?php
session_start();
include "../includes/permisos.php";
requireUsuario();
include "../includes/header.php";
include "../includes/navbar.php";
include "../config/db.php";

$uid = $_SESSION['usuario']['id'];
$stmt = $conexion->prepare("SELECT i.*, u.nombre AS creador, t.nombre AS tecnico_nombre FROM incidencias i LEFT JOIN usuarios u ON i.usuario_id=u.id LEFT JOIN usuarios t ON i.asignado_a=t.id WHERE i.usuario_id=? ORDER BY i.fecha_creacion DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2>Mis incidencias</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Prioridad</th>
        <th>Acciones</th>
    </tr>
    <?php while ($r = $res->fetch_assoc()): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['titulo']) ?></td>
            <td><?= htmlspecialchars($r['tipo']) ?></td>
            <td><?= $r['estado'] ?></td>
            <td><?= $r['prioridad'] ?></td>

            <td><a href="ver-incidencia.php?id=<?= $r['id'] ?>">Ver</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include "../includes/footer.php"; ?>