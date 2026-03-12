<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/mon-compte" class="list-group-item list-group-item-action"><i class="bi bi-person me-2"></i>Mon Profil</a>
            <a href="<?= SITE_URL ?>/mon-compte/emprunts" class="list-group-item list-group-item-action active"><i class="bi bi-box-seam me-2"></i><?= isMembre() ? 'Mes Emprunts' : 'Mes Locations' ?></a>
            <a href="<?= SITE_URL ?>/mon-compte/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Mes Demandes</a>
            <a href="<?= SITE_URL ?>/deconnexion" class="list-group-item list-group-item-action text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4"><?= isMembre() ? 'Mes Emprunts' : 'Mes Locations' ?></h2>

        <?php if (empty($emprunts)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box-seam display-4"></i>
                <p class="mt-3">Aucun <?= isMembre() ? 'emprunt' : 'location' ?> en cours.</p>
                <a href="<?= SITE_URL ?>/ludotheque" class="btn btn-outline-primary btn-sm">Parcourir les jeux</a>
            </div>
        <?php else: ?>
        <?php foreach ($emprunts as $e):
            $dateFin = strtotime($e['date_fin']);
            $dateDebut = strtotime($e['date_debut']);
            $joursRestants = max(0, ceil(($dateFin - time()) / 86400));
            $joursTotal = max(1, ceil(($dateFin - $dateDebut) / 86400));
            $joursEcoules = $joursTotal - $joursRestants;
            $pctProgress = min(100, round(($joursEcoules / $joursTotal) * 100));
            $urgent = $joursRestants <= 2;
            $expire = $joursRestants === 0;
            $depasse = ($dateFin < time());
            $barColor = $depasse ? 'bg-danger' : ($urgent ? 'bg-warning' : 'bg-success');
        ?>
        <div class="card mb-3 <?= $urgent || $depasse ? 'border-danger' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">
                            <a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $e['id_jeu'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($e['jeu_nom']) ?>
                            </a>
                        </h5>
                        <span class="badge bg-info"><?= ucfirst($e['type_demande']) ?></span>
                    </div>
                    <div class="text-end">
                        <?php if ($depasse): ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-exclamation-triangle me-1"></i>En retard !</span>
                        <?php elseif ($expire): ?>
                            <span class="badge bg-danger fs-6">À rendre aujourd'hui</span>
                        <?php elseif ($urgent): ?>
                            <span class="badge bg-warning text-dark fs-6"><?= $joursRestants ?> jour(s)</span>
                        <?php else: ?>
                            <span class="fs-3 fw-bold text-success"><?= $joursRestants ?></span>
                            <small class="text-muted d-block">jour(s) restant(s)</small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dates détaillées -->
                <div class="row text-muted small mb-2">
                    <div class="col-auto">
                        <i class="bi bi-calendar-plus me-1"></i>Début : <strong><?= date('d/m/Y', $dateDebut) ?></strong>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-x me-1"></i>Retour prévu : <strong><?= date('d/m/Y', $dateFin) ?></strong>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-hourglass-split me-1"></i>Durée : <strong><?= $joursTotal ?> jours</strong>
                    </div>
                </div>

                <!-- Barre de progression du temps écoulé -->
                <div class="progress" style="height: 8px;" data-bs-toggle="tooltip" title="<?= $joursEcoules ?> jour(s) écoulé(s) sur <?= $joursTotal ?>">
                    <div class="progress-bar <?= $barColor ?> progress-bar-striped <?= $urgent ? 'progress-bar-animated' : '' ?>"
                         role="progressbar" style="width: <?= $pctProgress ?>%"
                         aria-valuenow="<?= $pctProgress ?>" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-muted"><?= date('d/m', $dateDebut) ?></small>
                    <small class="text-muted"><?= date('d/m', $dateFin) ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Réservations (Jeu du Jeudi) -->
        <?php if (!empty($reservations)): ?>
        <h3 class="mt-5 mb-3"><i class="bi bi-calendar-check me-2"></i>Mes Réservations</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Jeu</th><th>Date du Jeudi</th><th>Statut</th></tr></thead>
                <tbody>
                <?php
                $statutBadge = ['en_attente'=>'warning','validee'=>'success','refusee'=>'danger'];
                $statutLabel = ['en_attente'=>'En attente','validee'=>'Confirmée','refusee'=>'Refusée'];
                foreach ($reservations as $r): ?>
                <tr>
                    <td><a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $r['id_jeu'] ?>" class="text-decoration-none"><?= htmlspecialchars($r['jeu_nom']) ?></a></td>
                    <td><i class="bi bi-calendar3 me-1"></i>Jeudi <?= date('d/m/Y', strtotime($r['date_debut'])) ?></td>
                    <td><span class="badge bg-<?= $statutBadge[$r['statut']] ?? 'secondary' ?>"><?= $statutLabel[$r['statut']] ?? $r['statut'] ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
