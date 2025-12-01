<?php
session_start();
include "../includes/permisos.php";
requireTecnico();
include "../includes/header.php";
include "../includes/navbar.php";
include "../config/db.php";

// Gráfico 1: tipos de incidencias activas (pendiente or en_proceso)
$q1 = "SELECT tipo, COUNT(*) AS cantidad FROM incidencias WHERE estado != 'resuelto' GROUP BY tipo";
$res1 = $conexion->query($q1);
$labels1 = [];
$data1 = [];
while ($r = $res1->fetch_assoc()) {
    $labels1[] = $r['tipo'];
    $data1[] = (int)$r['cantidad'];
}

// Gráfico 2: abiertas vs completadas por tipo
$q2 = "SELECT tipo,
SUM(CASE WHEN estado!='resuelto' THEN 1 ELSE 0 END) AS abiertas,
SUM(CASE WHEN estado='resuelto' THEN 1 ELSE 0 END) AS cerradas
FROM incidencias GROUP BY tipo";
$res2 = $conexion->query($q2);
$labels2 = [];
$abiertas = [];
$cerradas = [];
while ($r = $res2->fetch_assoc()) {
    $labels2[] = $r['tipo'];
    $abiertas[] = (int)$r['abiertas'];
    $cerradas[] = (int)$r['cerradas'];
}

// Listado de incidencias activas
$list = $conexion->query("SELECT i.*, u.nombre AS creador FROM incidencias i LEFT JOIN usuarios u ON i.usuario_id=u.id WHERE i.estado != 'resuelto' ORDER BY i.fecha_creacion DESC");
?>
<link rel="stylesheet" href="assets/css/style.css">
<h1>Panel Técnico</h1>

<h2>Incidencias activas</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Tipo</th>
        <th>Prioridad</th>
        <th>Creador</th>
        <th>Acción</th>
    </tr>
    <?php while ($r = $list->fetch_assoc()): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['titulo']) ?></td>
            <td><?= htmlspecialchars($r['tipo']) ?></td>
            <td><?= $r['prioridad'] ?></td>
            <td><?= htmlspecialchars($r['creador']) ?></td>
            <td>
                <a href="ver-incidencia.php?id=<?= $r['id'] ?>">Abrir</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<hr>
<h2>Gráficas</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h3>Incidencias activas por tipo</h3>
<canvas id="chart1" width="400" height="200"></canvas>

<h3>Abiertas vs Completadas por tipo</h3>
<canvas id="chart2" width="400" height="200"></canvas>

<script>
    const labels1 = <?= json_encode($labels1) ?>;
    const data1 = <?= json_encode($data1) ?>;
    new Chart(document.getElementById('chart1'), {
        type: 'bar',
        data: {
            labels: labels1,
            datasets: [{
                label: 'Activas',
                data: data1
            }]
        }
    });

    const labels2 = <?= json_encode($labels2) ?>;
    const abiertas = <?= json_encode($abiertas) ?>;
    const cerradas = <?= json_encode($cerradas) ?>;
    new Chart(document.getElementById('chart2'), {
        type: 'bar',
        data: {
            labels: labels2,
            datasets: [{
                    label: 'Abiertas',
                    data: abiertas
                },
                {
                    label: 'Completadas',
                    data: cerradas
                }
            ]
        }
    });
</script>

<?php include "../includes/footer.php"; ?>