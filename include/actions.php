<?php
//session_start();
$lastActions = $_SESSION['actions'] ?? [];
?>

<div class="container mt-4">
    <h3>Dernières actions effectuées</h3>
    <?php if (!empty($lastActions)): ?>
        <ul class="list-group">
            <?php foreach ($lastActions as $a): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($a['description']) ?></strong>
                    <span class="text-muted float-end"><?= $a['date'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune action récente.</p>
    <?php endif; ?>
</div>
