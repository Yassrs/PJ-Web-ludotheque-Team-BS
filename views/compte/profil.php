<?php $roleLabels = ['non_membre'=>'Non-membre','membre'=>'Membre','admin'=>'Administrateur','president'=>'Président']; ?>
<?php $roleBadge = ['non_membre'=>'secondary','membre'=>'success','admin'=>'info','president'=>'warning']; ?>

<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/mon-compte" class="list-group-item list-group-item-action active"><i class="bi bi-person me-2"></i>Mon Profil</a>
            <a href="<?= SITE_URL ?>/mon-compte/emprunts" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i><?= isMembre() ? 'Mes Emprunts' : 'Mes Locations' ?></a>
            <a href="<?= SITE_URL ?>/mon-compte/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Mes Demandes</a>
            <?php if (isAdmin()): ?>
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action text-danger"><i class="bi bi-speedometer2 me-2"></i>Administration</a>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/deconnexion" class="list-group-item list-group-item-action text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a>
        </div>
    </div>

    <!-- Content -->
    <div class="col-md-9">
        <h2 class="mb-4">Mon Profil</h2>

        <!-- Informations personnelles -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nom</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($user['nom']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Prénom</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($user['prenom']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Statut</label>
                        <p class="mb-0"><span class="badge bg-<?= $roleBadge[$user['role']] ?? 'secondary' ?>"><?= $roleLabels[$user['role']] ?? $user['role'] ?></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 bg-warning bg-opacity-10 border-warning">
                    <div class="fs-3 fw-bold text-warning"><?= $stats['en_attente'] ?></div>
                    <small class="text-muted">En attente</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 bg-success bg-opacity-10 border-success">
                    <div class="fs-3 fw-bold text-success"><?= $stats['validee'] ?></div>
                    <small class="text-muted">Validée(s)</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 bg-danger bg-opacity-10 border-danger">
                    <div class="fs-3 fw-bold text-danger"><?= $stats['refusee'] ?></div>
                    <small class="text-muted">Refusée(s)</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card text-center p-3 bg-primary bg-opacity-10 border-primary">
                    <div class="fs-3 fw-bold text-primary"><?= $stats['total'] ?></div>
                    <small class="text-muted">Total demandes</small>
                </div>
            </div>
        </div>

        <!-- Emprunts/Locations en cours avec countdown -->
        <?php if (!empty($empruntsActifs)): ?>
        <h4 class="mb-3"><i class="bi bi-box-seam me-2"></i><?= isMembre() ? 'Emprunts en cours' : 'Locations en cours' ?></h4>
        <?php foreach ($empruntsActifs as $e):
            $dateFin = strtotime($e['date_fin']);
            $dateDebut = strtotime($e['date_debut']);
            $joursRestants = max(0, ceil(($dateFin - time()) / 86400));
            $joursTotal = max(1, ceil(($dateFin - $dateDebut) / 86400));
            $joursEcoules = $joursTotal - $joursRestants;
            $pctProgress = min(100, round(($joursEcoules / $joursTotal) * 100));
            $urgent = $joursRestants <= 2;
            $expire = $joursRestants === 0;
            $barColor = $expire ? 'bg-danger' : ($urgent ? 'bg-warning' : 'bg-primary');
        ?>
        <div class="card mb-3 <?= $urgent ? 'border-danger' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">
                            <a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $e['id_jeu'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($e['jeu_nom']) ?>
                            </a>
                        </h5>
                        <span class="badge bg-info"><?= ucfirst($e['type_demande']) ?></span>
                        <span class="text-muted small ms-2">
                            <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', $dateDebut) ?> → <?= date('d/m/Y', $dateFin) ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <?php if ($expire): ?>
                            <span class="badge bg-danger fs-6"><i class="bi bi-exclamation-triangle me-1"></i>À rendre aujourd'hui</span>
                        <?php elseif ($urgent): ?>
                            <span class="badge bg-warning text-dark fs-6"><?= $joursRestants ?> jour(s) restant(s)</span>
                        <?php else: ?>
                            <span class="fs-4 fw-bold text-primary"><?= $joursRestants ?></span>
                            <small class="text-muted d-block">jour(s) restant(s)</small>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Barre de progression -->
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar <?= $barColor ?>" role="progressbar" style="width: <?= $pctProgress ?>%"
                         aria-valuenow="<?= $pctProgress ?>" aria-valuemin="0" aria-valuemax="100"
                         data-bs-toggle="tooltip" title="<?= $joursEcoules ?>/<?= $joursTotal ?> jours écoulés">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Réservations à venir -->
        <?php if (!empty($reservations)): ?>
        <h4 class="mt-4 mb-3"><i class="bi bi-calendar-check me-2"></i>Réservations à venir</h4>
        <?php foreach ($reservations as $r): ?>
        <div class="card mb-2">
            <div class="card-body py-2 d-flex justify-content-between align-items-center">
                <div>
                    <strong><a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $r['id_jeu'] ?>" class="text-decoration-none"><?= htmlspecialchars($r['jeu_nom']) ?></a></strong>
                    <span class="text-muted small ms-2"><i class="bi bi-calendar3 me-1"></i>Jeudi <?= date('d/m/Y', strtotime($r['date_debut'])) ?></span>
                </div>
                <?php
                $statutBadgeR = ['en_attente'=>'warning','validee'=>'success'];
                $statutLabelR = ['en_attente'=>'En attente','validee'=>'Validée'];
                ?>
                <span class="badge bg-<?= $statutBadgeR[$r['statut']] ?? 'secondary' ?>"><?= $statutLabelR[$r['statut']] ?? $r['statut'] ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Dernières demandes -->
        <h4 class="mt-4 mb-3"><i class="bi bi-list-check me-2"></i>Demandes récentes</h4>
        <?php if (empty($recentDemandes)): ?>
            <p class="text-muted">Aucune demande pour le moment. <a href="<?= SITE_URL ?>/ludotheque">Parcourir les jeux</a></p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Jeu</th><th>Type</th><th>Dates</th><th>Statut</th></tr></thead>
                <tbody>
                <?php
                $statutBadge = ['en_attente'=>'warning','validee'=>'success','refusee'=>'danger'];
                $statutLabel = ['en_attente'=>'En attente','validee'=>'Validée','refusee'=>'Refusée'];
                foreach ($recentDemandes as $d): ?>
                <tr>
                    <td><a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $d['id_jeu'] ?>" class="text-decoration-none"><?= htmlspecialchars($d['jeu_nom']) ?></a></td>
                    <td><?= ucfirst($d['type_demande']) ?></td>
                    <td class="small"><?= date('d/m/Y', strtotime($d['date_debut'])) ?> → <?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                    <td><span class="badge bg-<?= $statutBadge[$d['statut']] ?? 'secondary' ?>"><?= $statutLabel[$d['statut']] ?? $d['statut'] ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="<?= SITE_URL ?>/mon-compte/demandes" class="btn btn-outline-primary btn-sm">Voir toutes mes demandes</a>
        <?php endif; ?>
    </div>
</div>
