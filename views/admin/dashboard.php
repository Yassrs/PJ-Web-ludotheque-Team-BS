<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/admin/jeux" class="list-group-item list-group-item-action"><i class="bi bi-controller me-2"></i>Gestion des Jeux</a>
            <a href="<?= SITE_URL ?>/admin/evenements" class="list-group-item list-group-item-action"><i class="bi bi-calendar-event me-2"></i>Gestion Événements</a>
            <a href="<?= SITE_URL ?>/admin/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Traitement Demandes</a>
            <?php if (isPresident()): ?>
            <a href="<?= SITE_URL ?>/president" class="list-group-item list-group-item-action text-warning"><i class="bi bi-star-fill me-2"></i>Gestion Bureau</a>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/mon-compte" class="list-group-item list-group-item-action"><i class="bi bi-person me-2"></i>Mon Profil</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Tableau de Bord</h2>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 border-success">
                    <div class="fs-2 fw-bold text-success"><?= $stats['en_stock'] ?></div>
                    <small class="text-muted">Jeux en stock</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 border-warning">
                    <div class="fs-2 fw-bold text-warning"><?= $stats['emprunte'] + $stats['loue'] ?></div>
                    <small class="text-muted">Empruntés/Loués</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 border-danger">
                    <div class="fs-2 fw-bold text-danger"><?= $pendingCount ?></div>
                    <small class="text-muted">Demandes en attente</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 border-primary">
                    <div class="fs-2 fw-bold text-primary"><?= $upcomingEvents ?></div>
                    <small class="text-muted">Événements à venir</small>
                </div>
            </div>
        </div>

        <!-- Demandes en attente -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Demandes en attente</h5>
                <a href="<?= SITE_URL ?>/admin/demandes" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingDemandes)): ?>
                    <p class="text-center text-muted py-4">Aucune demande en attente.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Utilisateur</th><th>Jeu</th><th>Type</th><th>Durée</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach (array_slice($pendingDemandes, 0, 5) as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['user_prenom'] . ' ' . $d['user_nom']) ?><br><small class="text-muted"><?= ucfirst($d['user_role']) ?></small></td>
                            <td><?= htmlspecialchars($d['jeu_nom']) ?></td>
                            <td><?= ucfirst($d['type_demande']) ?></td>
                            <td class="small"><?= date('d/m', strtotime($d['date_debut'])) ?> → <?= date('d/m', strtotime($d['date_fin'])) ?></td>
                            <td>
                                <a href="<?= SITE_URL ?>/admin/demandes/accepter/<?= $d['id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-check"></i></a>
                                <a href="<?= SITE_URL ?>/admin/demandes/refuser/<?= $d['id'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-x"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
