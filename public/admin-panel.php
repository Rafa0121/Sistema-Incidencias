<?php
session_start();
include "../includes/permisos.php";
requireAdmin();
include "../includes/header.php";
include "../includes/navbar.php";
include "../config/db.php";

// Estadísticas
$total = $conexion->query("SELECT COUNT(*) AS c FROM incidencias")->fetch_assoc()['c'];
$pend = $conexion->query("SELECT COUNT(*) AS c FROM incidencias WHERE estado='pendiente'")->fetch_assoc()['c'];
$proc = $conexion->query("SELECT COUNT(*) AS c FROM incidencias WHERE estado='en_proceso'")->fetch_assoc()['c'];
$resu = $conexion->query("SELECT COUNT(*) AS c FROM incidencias WHERE estado='resuelto'")->fetch_assoc()['c'];

$users = $conexion->query("SELECT id,nombre,email,rol,creado_en FROM usuarios ORDER BY creado_en DESC");
$tecnicos = $conexion->query("SELECT id,nombre FROM usuarios WHERE rol='tecnico'");

// gráficas
$q1 = "SELECT tipo, COUNT(*) AS cantidad FROM incidencias WHERE estado != 'resuelto' GROUP BY tipo";
$res1 = $conexion->query($q1);
$labels1 = [];
$data1 = [];
while ($r = $res1->fetch_assoc()) {
    $labels1[] = $r['tipo'];
    $data1[] = (int)$r['cantidad'];
}

$q2 = "SELECT tipo,
SUM(CASE WHEN estado!='resuelto' THEN 1 ELSE 0 END) AS abiertas,
SUM(CASE WHEN estado='resuelto' THEN 1 ELSE 0 END) AS cerradas
FROM incidencias GROUP BY tipo";
$res2 = $conexion->query($q2);
$labels2 = [];
$ab = [];
$ce = [];
while ($r = $res2->fetch_assoc()) {
    $labels2[] = $r['tipo'];
    $ab[] = (int)$r['abiertas'];
    $ce[] = (int)$r['cerradas'];
}
?>
<link rel="stylesheet" href="assets/css/style.css">

<h1>Panel Administrador</h1>



<hr>
<h2>Gestionar Usuarios</h2>
<form id="frm-add-user" action="../controllers/admin_users.php" method="post">
    <input type="hidden" name="action" value="add_user">
    <label>Nombre</label><input name="nombre" required>
    <label>Email</label><input name="email" required>
    <label>Contraseña</label><input name="password" required>
    <label>Rol</label>
    <select name="rol">
        <option value="usuario">Usuario</option>
        <option value="tecnico">Tecnico</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Añadir usuario</button>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['rol'] ?></td>
            <td>
                <form style="display:inline" action="../controllers/admin_users.php" method="post">
                    <input type="hidden" name="action" value="change_role">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <select name="rol">
                        <option value="usuario">Usuario</option>
                        <option value="tecnico">Tecnico</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Cambiar rol</button>
                </form>

                <form style="display:inline" action="../controllers/admin_users.php" method="post" onsubmit="return confirm('Borrar usuario?');">
                    <input type="hidden" name="action" value="del_user">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button type="submit">Borrar</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<hr>
<h2>Gráficas</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h3>Activas por tipo</h3><canvas id="chart1" width="400" height="200"></canvas>
<h3>Abiertas vs Completadas</h3><canvas id="chart2" width="400" height="200"></canvas>

<script>
    new Chart(document.getElementById('chart1'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels1) ?>,
            datasets: [{
                label: 'Activas',
                data: <?= json_encode($data1) ?>
            }]
        }
    });
    new Chart(document.getElementById('chart2'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels2) ?>,
            datasets: [{
                label: 'Abiertas',
                data: <?= json_encode($ab) ?>
            }, {
                label: 'Completadas',
                data: <?= json_encode($ce) ?>
            }]
        }
    });
</script>

<?php include "../includes/footer.php"; ?>