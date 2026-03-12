<h2 class="mb-4"><i class="bi bi-calendar-event me-2"></i>Événements</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <select class="form-select" onchange="window.location.href='<?= SITE_URL ?>/evenements?categorie='+this.value+'&periode=<?= $_GET['periode'] ?? 'a_venir' ?>'">
            <option value="">Toutes les catégories</option>
            <?php foreach (['salle_jeudi'=>'Salle du Jeudi','jeu_jeudi'=>'Jeu du Jeudi','soiree_jeux'=>'Soirée Jeux','occasionnel'=>'Occasionnel'] as $k=>$v): ?>
            <option value="<?= $k ?>" <?= ($_GET['categorie'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" onchange="window.location.href='<?= SITE_URL ?>/evenements?periode='+this.value+'&categorie=<?= $_GET['categorie'] ?? '' ?>'">
            <option value="a_venir" <?= ($_GET['periode'] ?? 'a_venir') === 'a_venir' ? 'selected' : '' ?>>À venir</option>
            <option value="passes" <?= ($_GET['periode'] ?? '') === 'passes' ? 'selected' : '' ?>>Passés</option>
            <option value="tous" <?= ($_GET['periode'] ?? '') === 'tous' ? 'selected' : '' ?>>Tous</option>
        </select>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="eventSearchInput" class="form-control" placeholder="Rechercher un événement...">
        </div>
    </div>
    <div class="col-md-2 d-flex align-items-center">
        <span class="text-muted small" id="eventCount"><?= count($evenements) ?> événement(s)</span>
    </div>
</div>

<div class="row g-4 event-grid">
    <?php if (empty($evenements)): ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-calendar-x display-4"></i>
            <p class="mt-3">Aucun événement trouvé.</p>
        </div>
    <?php endif; ?>
    <?php 
    $catColors = ['salle_jeudi'=>'primary','jeu_jeudi'=>'success','soiree_jeux'=>'warning','occasionnel'=>'danger'];
    $catLabels = ['salle_jeudi'=>'Salle du Jeudi','jeu_jeudi'=>'Jeu du Jeudi','soiree_jeux'=>'Soirée Jeux','occasionnel'=>'Occasionnel'];
    foreach ($evenements as $evt): ?>
    <div class="col-md-6 event-card"
         data-titre="<?= htmlspecialchars($evt['titre']) ?>"
         data-lieu="<?= htmlspecialchars($evt['lieu']) ?>"
         data-desc="<?= htmlspecialchars(substr($evt['description'], 0, 200)) ?>">
        <div class="card h-100 card-hover">
            <div class="card-body">
                <span class="badge bg-<?= $catColors[$evt['categorie']] ?? 'secondary' ?> mb-2"><?= $catLabels[$evt['categorie']] ?? $evt['categorie'] ?></span>
                <h5 class="card-title"><?= htmlspecialchars($evt['titre']) ?></h5>
                <p class="text-muted small mb-2">
                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($evt['date_evenement'])) ?> à <?= date('H\hi', strtotime($evt['heure'])) ?><br>
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($evt['lieu']) ?>
                </p>
                <p class="card-text"><?= htmlspecialchars(substr($evt['description'], 0, 150)) ?>...</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= SITE_URL ?>/evenements/<?= $evt['id'] ?>" class="btn btn-outline-<?= $catColors[$evt['categorie']] ?? 'secondary' ?> btn-sm">Voir les détails</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
