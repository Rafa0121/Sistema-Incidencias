<?php
session_start();
include "../includes/header.php";
include "../includes/navbar.php";
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "Incidencia no especificada.";
    exit;
}

$stmt = $conexion->prepare("SELECT i.*, u.nombre AS creador, t.nombre AS tecnico_nombre FROM incidencias i LEFT JOIN usuarios u ON i.usuario_id=u.id LEFT JOIN usuarios t ON i.asignado_a=t.id WHERE i.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$inc = $stmt->get_result()->fetch_assoc();
if (!$inc) {
    echo "No encontrada.";
    exit;
}

$stmt2 = $conexion->prepare("SELECT c.*, u.nombre FROM comentarios c LEFT JOIN usuarios u ON c.user_id=u.id WHERE c.incidencia_id=? ORDER BY c.fecha ASC");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$comments = $stmt2->get_result();

$stmtR = $conexion->prepare("SELECT r.*, u.nombre FROM respuestas r LEFT JOIN usuarios u ON r.tecnico_id=u.id WHERE r.incidencia_id=? ORDER BY r.fecha ASC");
$stmtR->bind_param("i", $id);
$stmtR->execute();
$respuestas = $stmtR->get_result();

$userRole = $_SESSION['usuario']['rol'] ?? null;
$userId = $_SESSION['usuario']['id'] ?? null;
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2>Incidencia #<?= $inc['id'] ?> - <?= htmlspecialchars($inc['titulo']) ?></h2>
<p><strong>Tipo:</strong> <?= htmlspecialchars($inc['tipo']) ?> | <strong>Estado:</strong> <?= $inc['estado'] ?> | <strong>Prioridad:</strong> <?= $inc['prioridad'] ?></p>
<p><?= nl2br(htmlspecialchars($inc['descripcion'])) ?></p>
<p><strong>Creador:</strong> <?= htmlspecialchars($inc['creador']) ?></p>



<h3>Respuestas de técnicos</h3>
<div id="respuestas">
    <?php while ($r = $respuestas->fetch_assoc()): ?>
        <div style="border:1px solid #cfc;padding:8px;margin-bottom:6px;">
            <small><strong><?= htmlspecialchars($r['nombre']) ?></strong> — <?= $r['fecha'] ?></small>
            <p><?= nl2br(htmlspecialchars($r['respuesta'])) ?></p>
        </div>
    <?php endwhile; ?>
</div>

<?php if (isset($_SESSION['usuario']) && in_array($userRole, ['tecnico', 'admin'])): ?>
    <hr>
    <h3>Añadir respuesta (técnico)</h3>
    <form action="../controllers/responder_incidencia.php" method="post">
        <input type="hidden" name="incidencia_id" value="<?= $inc['id'] ?>">
        <textarea name="respuesta" rows="4" required></textarea><br>
        <label><input type="checkbox" name="resuelta"> Marcar como resuelta</label><br>
        <button type="submit">Enviar respuesta</button>
    </form>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>