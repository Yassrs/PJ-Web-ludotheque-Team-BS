<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/admin/jeux" class="list-group-item list-group-item-action"><i class="bi bi-controller me-2"></i>Gestion des Jeux</a>
            <a href="<?= SITE_URL ?>/admin/evenements" class="list-group-item list-group-item-action"><i class="bi bi-calendar-event me-2"></i>Gestion Événements</a>
            <a href="<?= SITE_URL ?>/admin/demandes" class="list-group-item list-group-item-action active"><i class="bi bi-list-check me-2"></i>Traitement Demandes</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4"><i class="bi bi-list-check me-2"></i>Traitement des Demandes</h2>
        
        <div class="row g-2 mb-4">
            <div class="col-auto">
                <select class="form-select form-select-sm" onchange="window.location.href='<?= SITE_URL ?>/admin/demandes?statut='+this.value+'&type=<?= $_GET['type'] ?? '' ?>'">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" <?= ($_GET['statut']??'')==='en_attente'?'selected':'' ?>>En attente</option>
                    <option value="validee" <?= ($_GET['statut']??'')==='validee'?'selected':'' ?>>Validées</option>
                    <option value="refusee" <?= ($_GET['statut']??'')==='refusee'?'selected':'' ?>>Refusées</option>
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select form-select-sm" onchange="window.location.href='<?= SITE_URL ?>/admin/demandes?type='+this.value+'&statut=<?= $_GET['statut'] ?? '' ?>'">
                    <option value="">Tous les types</option>
                    <option value="emprunt" <?= ($_GET['type']??'')==='emprunt'?'selected':'' ?>>Emprunt</option>
                    <option value="location" <?= ($_GET['type']??'')==='location'?'selected':'' ?>>Location</option>
                    <option value="reservation" <?= ($_GET['type']??'')==='reservation'?'selected':'' ?>>Réservation</option>
                </select>
            </div>
        </div>

        <?php if (empty($demandes)): ?>
            <div class="text-center py-5 text-muted"><i class="bi bi-inbox display-4"></i><p class="mt-3">Aucune demande.</p></div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>#</th><th>Utilisateur</th><th>Jeu</th><th>Type</th><th>Durée</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $statutBadge = ['en_attente'=>'warning','validee'=>'success','refusee'=>'danger'];
                $statutLabel = ['en_attente'=>'En attente','validee'=>'Validée','refusee'=>'Refusée'];
                foreach ($demandes as $d): ?>
                <tr>
                    <td class="text-muted">#<?= $d['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($d['user_prenom'] . ' ' . $d['user_nom']) ?><br>
                        <small class="text-muted"><?= ucfirst($d['user_role']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($d['jeu_nom']) ?></td>
                    <td><?= ucfirst($d['type_demande']) ?></td>
                    <td class="small"><?= date('d/m', strtotime($d['date_debut'])) ?> → <?= date('d/m', strtotime($d['date_fin'])) ?></td>
                    <td><span class="badge bg-<?= $statutBadge[$d['statut']] ?>"><?= $statutLabel[$d['statut']] ?></span></td>
                    <td>
                        <?php if ($d['statut'] === 'en_attente'): ?>
                        <a href="<?= SITE_URL ?>/admin/demandes/accepter/<?= $d['id'] ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Accepter"><i class="bi bi-check-lg"></i></a>
                        <button class="btn btn-danger btn-sm"
                                data-refuse-id="<?= $d['id'] ?>"
                                data-refuse-url="<?= SITE_URL ?>/admin/demandes/refuser/<?= $d['id'] ?>"
                                data-refuse-jeu="<?= htmlspecialchars($d['jeu_nom']) ?>"
                                data-refuse-user="<?= htmlspecialchars($d['user_prenom'] . ' ' . $d['user_nom']) ?>"
                                data-bs-toggle="tooltip" title="Refuser">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <?php else: ?>
                        <span class="text-muted small">Traité</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
