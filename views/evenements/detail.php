<a href="<?= SITE_URL ?>/evenements" class="text-decoration-none text-muted mb-3 d-inline-block">
    <i class="bi bi-arrow-left me-1"></i>Retour aux événements
</a>

<?php
$catColors = ['salle_jeudi'=>'primary','jeu_jeudi'=>'success','soiree_jeux'=>'warning','occasionnel'=>'danger'];
$catLabels = ['salle_jeudi'=>'Salle du Jeudi','jeu_jeudi'=>'Jeu du Jeudi','soiree_jeux'=>'Soirée Jeux','occasionnel'=>'Occasionnel'];
?>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <span class="badge bg-<?= $catColors[$evenement['categorie']] ?? 'secondary' ?> mb-3"><?= $catLabels[$evenement['categorie']] ?? '' ?></span>
        <h1><?= htmlspecialchars($evenement['titre']) ?></h1>
        <div class="d-flex flex-wrap gap-3 text-muted mb-4">
            <span><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($evenement['date_evenement'])) ?></span>
            <span><i class="bi bi-clock me-1"></i><?= date('H\hi', strtotime($evenement['heure'])) ?></span>
            <span><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($evenement['lieu']) ?></span>
        </div>
        <hr>
        <div class="mt-3">
            <?= nl2br(htmlspecialchars($evenement['description'])) ?>
        </div>
    </div>
</div>
